<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Shipment;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    protected SaleService $saleService;

    public function __construct(
        SaleService $saleService
    )
    {
        $this->saleService = $saleService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $sales = Sale::with([

            'shipment',

            'items.type',

        ])
        ->latest()
        ->paginate(20);

        return view(
            'sales.index',
            compact('sales')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Shipment $shipment
    )
    {
        $shipment->load([

            'items.type',

        ]);

        $types = $shipment->items

            ->map(function ($item) {

                return [

                    'id' => $item->sea_cucumber_type_id,

                    'name' => $item->type->name,

                    'status' => $item->status,

                    'weight' => $item->weight,

                ];

            })

            ->values();

        return view(

            'sales.create',

            compact(

                'shipment',

                'types'

            )

        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(
    Request $request,
    Shipment $shipment
)
{
    $validated = $request->validate([

        'invoice_number' => [
            'required',
            'string',
            'max:100',
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
        ],

        'weight.*' => [
            'required',
            'numeric',
            'min:0.01',
        ],

        'price' => [
            'required',
            'array',
        ],

        'price.*' => [
            'required',
            'numeric',
            'min:0',
        ],

        'status' => [
            'required',
            'array',
        ],

        'status.*' => [
            'required',
            'in:Terjual Sebagian,Selesai',
        ],

    ]);

    $sale = $this->saleService->createSale(
        $shipment,
        $validated
    );

    return redirect()
        ->route(
            'sales.show',
            $sale
        )
        ->with(
            'success',
            'Nota penjualan berhasil disimpan.'
        );
}

/*
|--------------------------------------------------------------------------
| SHOW
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| SHOW
|--------------------------------------------------------------------------
*/

public function show(Sale $sale)
{
    $sale->load([

        'shipment',

        'items.type',

    ]);

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

/*
|--------------------------------------------------------------------------
| DESTROY
|--------------------------------------------------------------------------
*/

public function destroy(Sale $sale)
{
    $shipment = $sale->shipment;

    DB::transaction(function () use ($sale) {

        $sale->items()->delete();

        $sale->delete();

    });

    $this->saleService
        ->updateShipmentStatus($shipment);

    return redirect()
        ->route('sales.index')
        ->with(
            'success',
            'Nota penjualan berhasil dihapus.'
        );
}
}