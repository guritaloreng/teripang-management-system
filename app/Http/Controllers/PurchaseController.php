<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\SeaCucumberType;
use App\Models\Supplier;
use App\Services\CashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function __construct(
        protected CashService $cashService
    ) {
    }

    public function index()
    {
        $purchases = Purchase::with([
            'supplier',
            'items.type',
        ])
            ->latest('purchase_date')
            ->latest('id')
            ->get();

        return view(
            'purchases.index',
            compact('purchases')
        );
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')
            ->get();

        $types = SeaCucumberType::orderBy('name')
            ->get();

        return view(
            'purchases.create',
            compact(
                'suppliers',
                'types'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validatePurchase($request);

        $purchase = DB::transaction(function () use ($validated) {

            $purchase = Purchase::create([
                'purchase_number' => $this->generatePurchaseNumber(),
                'supplier_invoice' => $validated['supplier_invoice'] ?? null,
                'purchase_date' => $validated['purchase_date'],
                'supplier_id' => $validated['supplier_id'],
                'grand_total' => 0,
                'note' => $validated['note'] ?? null,
            ]);

            $grandTotal = $this->syncItems(
                $purchase,
                $validated
            );

            $purchase->update([
                'grand_total' => $grandTotal,
            ]);

            $this->cashService->createFromPurchase(
                $purchase->fresh('supplier')
            );

            return $purchase;

        });

        return redirect()
            ->route('purchases.show', $purchase)
            ->with(
                'success',
                'Nota pembelian berhasil disimpan.'
            );
    }

    public function show(Purchase $purchase)
    {
        $purchase->load([
            'supplier',
            'items.type',
        ]);

        return view(
            'purchases.show',
            compact('purchase')
        );
    }

    public function edit(Purchase $purchase)
    {
        $purchase->load('items');

        $suppliers = Supplier::orderBy('name')
            ->get();

        $types = SeaCucumberType::orderBy('name')
            ->get();

        return view(
            'purchases.edit',
            compact(
                'purchase',
                'suppliers',
                'types'
            )
        );
    }

    public function update(
        Request $request,
        Purchase $purchase
    ) {
        $validated = $this->validatePurchase($request);

        DB::transaction(function () use ($purchase, $validated) {

            $purchase->update([
                'supplier_invoice' => $validated['supplier_invoice'] ?? null,
                'purchase_date' => $validated['purchase_date'],
                'supplier_id' => $validated['supplier_id'],
                'note' => $validated['note'] ?? null,
            ]);

            $purchase->items()->delete();

            $grandTotal = $this->syncItems(
                $purchase,
                $validated
            );

            $purchase->update([
                'grand_total' => $grandTotal,
            ]);

            $this->cashService->updateFromPurchase(
                $purchase->fresh('supplier')
            );

        });

        return redirect()
            ->route('purchases.show', $purchase)
            ->with(
                'success',
                'Nota pembelian berhasil diperbarui.'
            );
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {

            $this->cashService->deleteFromPurchase($purchase);

            $purchase->items()->delete();

            $purchase->delete();

        });

        return redirect()
            ->route('purchases.index')
            ->with(
                'success',
                'Nota pembelian berhasil dihapus.'
            );
    }

    protected function validatePurchase(Request $request): array
    {
        $validated = $request->validate([
            'purchase_date' => ['required', 'date'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'supplier_invoice' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'type_id' => ['required', 'array', 'min:1'],
            'type_id.*' => ['required', 'exists:sea_cucumber_types,id'],
            'purchase_weight' => ['required', 'array', 'min:1'],
            'purchase_weight.*' => ['required', 'numeric', 'min:0.01'],
            'price_per_kg' => ['required', 'array', 'min:1'],
            'price_per_kg.*' => ['required', 'numeric', 'min:0'],
        ]);

        $this->validatePurchaseItemArrays($validated);

        return $validated;
    }

    protected function validatePurchaseItemArrays(array $data): void
    {
        $typeKeys = array_keys($data['type_id'] ?? []);

        foreach ([
            'purchase_weight',
            'price_per_kg',
        ] as $field) {

            $fieldKeys = array_keys($data[$field] ?? []);

            if ($fieldKeys !== $typeKeys) {

                throw ValidationException::withMessages([
                    $field => 'Jumlah data item pembelian tidak konsisten.',
                ]);

            }

        }
    }

    protected function syncItems(
        Purchase $purchase,
        array $data
    ): float {
        $grandTotal = 0;

        foreach ($data['type_id'] as $index => $typeId) {

            $weight = (float) $data['purchase_weight'][$index];

            $price = (float) $data['price_per_kg'][$index];

            $subtotal = $weight * $price;

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'sea_cucumber_type_id' => $typeId,
                'purchase_weight' => $weight,
                'price_per_kg' => $price,
                'subtotal' => $subtotal,
            ]);

            $grandTotal += $subtotal;

        }

        return $grandTotal;
    }

    protected function generatePurchaseNumber(): string
    {
        $last = Purchase::latest('id')
            ->first();

        $next = $last
            ? $last->id + 1
            : 1;

        return 'PUR-'
            . str_pad(
                $next,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}
