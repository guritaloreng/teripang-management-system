<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\SeaCucumberType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier')
            ->latest()
            ->paginate(20);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();

        $types = SeaCucumberType::orderBy('name')->get();

        return view('purchases.create', compact(
            'suppliers',
            'types'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'type_id' => 'required|array',
            'purchase_weight' => 'required|array',
            'price_per_kg' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            $purchase = Purchase::create([

                'purchase_number' => 'PO-' . now()->format('YmdHis'),

                'supplier_invoice' => $request->supplier_invoice,

                'purchase_date' => $request->purchase_date,

                'supplier_id' => $request->supplier_id,

                'grand_total' => 0,

                'photo' => null,

                'note' => $request->note

            ]);

            $grandTotal = 0;

            foreach ($request->type_id as $i => $type) {

                $subtotal =
                    $request->purchase_weight[$i]
                    *
                    $request->price_per_kg[$i];

                PurchaseItem::create([

                    'purchase_id' => $purchase->id,

                    'sea_cucumber_type_id' => $type,

                    'purchase_weight' => $request->purchase_weight[$i],

                    'price_per_kg' => $request->price_per_kg[$i],

                    'subtotal' => $subtotal

                ]);

                $grandTotal += $subtotal;
            }

            $purchase->update([

                'grand_total' => $grandTotal

            ]);

        });

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Pembelian berhasil disimpan.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load([
        'supplier',
        'items.type'
    ]);

    return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
    }

    public function update(Request $request, Purchase $purchase)
    {
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()
            ->back()
            ->with('success', 'Data berhasil dihapus.');
    }
}