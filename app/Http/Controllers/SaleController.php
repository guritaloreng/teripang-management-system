<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create(Shipment $shipment)
    {
        $shipment->load([
            'items.purchase.items.type',
            'sales.items.type'
        ]);

        $types = [];

        foreach ($shipment->items as $shipmentItem) {

            foreach ($shipmentItem->purchase->items as $purchaseItem) {

                $id = $purchaseItem->sea_cucumber_type_id;

                if (!isset($types[$id])) {

                    $types[$id] = [

                        'id' => $id,

                        'name' => $purchaseItem->type->name,

                        'weight' => 0

                    ];

                }

                $types[$id]['weight'] += $purchaseItem->weight;

            }

        }

        return view('sales.create', [

            'shipment' => $shipment,

            'types' => $types

        ]);
    }

    public function store(Request $request, Shipment $shipment)
    {
        $request->validate([

            'invoice_number' => 'required',

            'buyer' => 'required',

            'sale_date' => 'required|date',

            'type_id' => 'required|array',

            'weight' => 'required|array',

            'price' => 'required|array'

        ]);

        DB::transaction(function () use ($request, $shipment) {

            $sale = Sale::create([

                'shipment_id' => $shipment->id,

                'invoice_number' => $request->invoice_number,

                'buyer' => $request->buyer,

                'sale_date' => $request->sale_date,

                'note' => $request->note

            ]);

            foreach ($request->type_id as $i => $type) {

                if (($request->weight[$i] ?? 0) <= 0) {
                    continue;
                }

                SaleItem::create([

                    'sale_id' => $sale->id,

                    'sea_cucumber_type_id' => $type,

                    'weight' => $request->weight[$i],

                    'price' => $request->price[$i],

                    'subtotal' =>
                        $request->weight[$i] *
                        $request->price[$i]

                ]);

            }

            if ($shipment->status == 'Belum Dijual') {

                $shipment->status = 'Terjual Sebagian';

                $shipment->save();

            }

        });

        return redirect()
            ->route('shipments.show', $shipment)
            ->with('success', 'Penjualan berhasil disimpan.');
    }
}