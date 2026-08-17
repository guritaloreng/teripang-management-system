<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SeaCucumberType;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SaleController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {
    }

    public function index()
    {
        $sales = Sale::with([
                'items.type',
            ])
            ->latest()
            ->paginate(20);

        return view(
            'sales.index',
            compact('sales')
        );
    }

    public function create()
    {
        $types = SeaCucumberType::orderBy('name')
            ->get();

        return view(
            'sales.create',
            compact('types')
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validateSaleRequest($request);

        $sale = $this->saleService
            ->createSale($validated);

        return redirect()
            ->route('sales.show', $sale)
            ->with(
                'success',
                'Nota penjualan berhasil disimpan.'
            );
    }

    public function edit(Sale $sale)
    {
        $sale->load('items.type');

        $types = SeaCucumberType::orderBy('name')
            ->get();

        return view(
            'sales.edit',
            compact(
                'sale',
                'types'
            )
        );
    }

    public function update(
        Request $request,
        Sale $sale
    ) {
        $validated = $this->validateSaleRequest(
            $request,
            $sale
        );

        $sale = $this->saleService
            ->updateSale(
                $sale,
                $validated
            );

        return redirect()
            ->route('sales.show', $sale)
            ->with(
                'success',
                'Nota penjualan berhasil diperbarui.'
            );
    }

    public function show(Sale $sale)
    {
        $sale->load('items.type');

        $grandTotal = $this->saleService
            ->grandTotal($sale);

        return view(
            'sales.show',
            compact(
                'sale',
                'grandTotal'
            )
        );
    }

    public function destroy(Sale $sale)
    {
        $this->saleService
            ->deleteSale($sale);

        return redirect()
            ->route('sales.index')
            ->with(
                'success',
                'Nota penjualan berhasil dihapus.'
            );
    }

    protected function validateSaleRequest(
        Request $request,
        ?Sale $sale = null
    ): array {
        $invoiceNumberRule = Rule::unique(
            'sales',
            'invoice_number'
        );

        if ($sale) {

            $invoiceNumberRule->ignore($sale->id);

        }

        $validator = Validator::make(
            $request->all(),
            [
                'invoice_number' => [
                    'required',
                    'string',
                    'max:100',
                    $invoiceNumberRule,
                ],

                'sale_date' => [
                    'required',
                    'date',
                ],

                'buyer' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'note' => [
                    'nullable',
                    'string',
                ],

                'type_id' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'type_id.*' => [
                    'required',
                    'exists:sea_cucumber_types,id',
                ],

                'weight' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'weight.*' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'price' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'price.*' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'status' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'status.*' => [
                    'required',
                    'in:Terjual Sebagian,Selesai',
                ],
            ]
        );

        $validator->after(function ($validator) use ($request) {

            $typeIds = $request->input('type_id', []);

            $rowKeys = is_array($typeIds)
                ? array_keys($typeIds)
                : [];

            foreach ([
                'weight',
                'price',
                'status',
            ] as $field) {

                $values = $request->input($field, []);

                $fieldKeys = is_array($values)
                    ? array_keys($values)
                    : [];

                if ($fieldKeys !== $rowKeys) {

                    $validator->errors()->add(
                        $field,
                        'Jumlah data item penjualan tidak konsisten.'
                    );

                }

            }

        });

        return $validator->validate();
    }
}
