<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Shipment;
use App\Services\ShipmentService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    protected ShipmentService $shipmentService;

    public function __construct(ShipmentService $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $shipments = Shipment::with([
            'items.type',
            'purchases.purchase.supplier',
        ])
        ->latest()
        ->paginate(20);

        return view(
            'shipments.index',
            compact('shipments')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $purchases = Purchase::with([
                'supplier',
                'items.type',
            ])
            ->whereNotIn('id', function ($query) {

                $query->select('purchase_id')
                    ->from('shipment_purchases');

            })
            ->orderBy('purchase_date')
            ->get();

        return view(
            'shipments.create',
            compact('purchases')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'shipment_date' => ['required', 'date'],

            'destination' => ['required', 'string', 'max:255'],

            'purchase_ids' => ['required', 'array', 'min:1'],

            'purchase_ids.*' => ['exists:purchases,id'],

            'note' => ['nullable', 'string'],

        ]);

        $shipment = $this->shipmentService
            ->createShipment($validated);

        return redirect()
            ->route('shipments.show', $shipment)
            ->with(
                'success',
                'Shipment berhasil dibuat.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Shipment $shipment)
{
    $shipment->load([

        'items.type',

        'purchases.purchase.supplier',

        'purchases.purchase.items.type',

        'sales.items.type',

    ]);

    $summary = $this->shipmentService
        ->summary($shipment);

    $progressPerType = $this->shipmentService
        ->progressPerType($shipment);

    return view(
        'shipments.show',
        compact(
            'shipment',
            'summary',
            'progressPerType'
        )
    );
}

/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

public function edit(Shipment $shipment)
{
    $shipment->load([
        'purchases',
    ]);

    $selectedPurchaseIds = $shipment->purchases
        ->pluck('purchase_id')
        ->toArray();

    $purchases = Purchase::with([
            'supplier',
            'items.type',
        ])
        ->where(function ($query) use ($selectedPurchaseIds) {

            $query->whereIn(
                'id',
                $selectedPurchaseIds
            )

            ->orWhereNotIn('id', function ($sub) {

                $sub->select('purchase_id')
                    ->from('shipment_purchases');

            });

        })
        ->orderBy('purchase_date')
        ->get();

    return view(
        'shipments.edit',
        compact(
            'shipment',
            'purchases'
        )
    );
}

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/
public function update(
    Request $request,
    Shipment $shipment
)
{
    $validated = $request->validate([

        'shipment_date' => ['required', 'date'],

        'destination' => ['required', 'string', 'max:255'],

        'purchase_ids' => ['required', 'array', 'min:1'],

        'purchase_ids.*' => ['exists:purchases,id'],

        'note' => ['nullable', 'string'],

    ]);

    $shipment = $this->shipmentService
        ->updateShipment(
            $shipment,
            $validated
        );

    return redirect()
        ->route(
            'shipments.show',
            $shipment
        )
        ->with(
            'success',
            'Shipment berhasil diperbarui.'
        );
}

/*
|--------------------------------------------------------------------------
| DESTROY
|--------------------------------------------------------------------------
*/

public function destroy(Shipment $shipment)
{
    $this->shipmentService
        ->deleteShipment($shipment);

    return redirect()
        ->route('shipments.index')
        ->with(
            'success',
            'Shipment berhasil dihapus.'
        );
}
}