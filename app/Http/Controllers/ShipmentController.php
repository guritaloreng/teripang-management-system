<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with('items.purchase.supplier')
            ->latest()
            ->paginate(20);

        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        $purchases = Purchase::with('supplier')
            ->whereNotIn('id', function ($query) {
                $query->select('purchase_id')
                    ->from('shipment_items');
            })
            ->orderBy('purchase_date')
            ->get();

        return view('shipments.create', compact('purchases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipment_date' => 'required|date',
            'destination' => 'required|string|max:255',
            'shipping_cost' => 'nullable|numeric|min:0',
            'purchase_ids' => 'required|array|min:1'
        ]);

        DB::transaction(function () use ($request) {

            $shipment = Shipment::create([

                'shipment_number' =>
                    'SHP-' . now()->format('YmdHis'),

                'shipment_date' =>
                    $request->shipment_date,

                'destination' =>
                    $request->destination,

                'status' =>
                    'Dalam Pengiriman',

                'shipping_cost' =>
                    $request->shipping_cost ?? 0,

                'note' =>
                    $request->note

            ]);

            foreach ($request->purchase_ids as $purchase) {

                ShipmentItem::create([

                    'shipment_id' => $shipment->id,

                    'purchase_id' => $purchase

                ]);

            }

        });

        return redirect()
            ->route('shipments.index')
            ->with('success', 'Shipment berhasil dibuat.');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load('items.purchase.supplier');

        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        abort(404);
    }

    public function update(Request $request, Shipment $shipment)
    {
        abort(404);
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return redirect()
            ->route('shipments.index')
            ->with('success', 'Shipment berhasil dihapus.');
    }
}