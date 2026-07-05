<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\ShipmentPurchase;
use Illuminate\Support\Facades\DB;

class ShipmentService
{
    /*
    |--------------------------------------------------------------------------
    | CREATE SHIPMENT
    |--------------------------------------------------------------------------
    */

    public function createShipment(array $data): Shipment
    {
        return DB::transaction(function () use ($data) {

            $shipment = Shipment::create([

                'shipment_number' => $this->generateShipmentNumber(),

                'shipment_date'   => $data['shipment_date'],

                'destination'     => $data['destination'],

                'status'          => Shipment::STATUS_DRAFT,

                'note'            => $data['note'] ?? null,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Simpan daftar Purchase yang tergabung dalam Shipment
            |--------------------------------------------------------------------------
            */

            foreach ($data['purchase_ids'] as $purchaseId) {

                ShipmentPurchase::create([

                    'shipment_id' => $shipment->id,

                    'purchase_id' => $purchaseId,

                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Ambil seluruh Purchase Item lalu gabungkan berdasarkan Jenis
            |--------------------------------------------------------------------------
            */

            $summary = [];

            foreach ($data['purchase_ids'] as $purchaseId) {

                $purchase = Purchase::with('items')
                    ->findOrFail($purchaseId);

                foreach ($purchase->items as $item) {

                    $typeId = $item->sea_cucumber_type_id;

                    if (! isset($summary[$typeId])) {

                        $summary[$typeId] = [

                            'weight' => 0,

                        ];

                    }

                    $summary[$typeId]['weight']
                        += (float) $item->purchase_weight;

                }

            }

            /*
            |--------------------------------------------------------------------------
            | Simpan ringkasan Shipment per Jenis Teripang
            |--------------------------------------------------------------------------
            */

            foreach ($summary as $typeId => $value) {

                ShipmentItem::create([

                    'shipment_id' => $shipment->id,

                    'sea_cucumber_type_id' => $typeId,

                    'weight' => $value['weight'],

                    'status' => 'Belum Dijual',

                ]);

            }

            return $shipment->fresh([
                'items',
                'purchases',
            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE SHIPMENT
    |--------------------------------------------------------------------------
    */
    public function updateShipment(
    Shipment $shipment,
    array $data
): Shipment
{
    return DB::transaction(function () use ($shipment, $data) {

        /*
        |--------------------------------------------------------------------------
        | Update Header Shipment
        |--------------------------------------------------------------------------
        */

        $shipment->update([

            'shipment_date' => $data['shipment_date'],

            'destination'   => $data['destination'],

            'note'          => $data['note'] ?? null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Hapus seluruh relasi lama
        |--------------------------------------------------------------------------
        */

        ShipmentPurchase::where(
            'shipment_id',
            $shipment->id
        )->delete();

        ShipmentItem::where(
            'shipment_id',
            $shipment->id
        )->delete();

        /*
        |--------------------------------------------------------------------------
        | Simpan Purchase baru
        |--------------------------------------------------------------------------
        */

        foreach ($data['purchase_ids'] as $purchaseId) {

            ShipmentPurchase::create([

                'shipment_id' => $shipment->id,

                'purchase_id' => $purchaseId,

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Hitung ulang seluruh jenis
        |--------------------------------------------------------------------------
        */

        $summary = [];

        foreach ($data['purchase_ids'] as $purchaseId) {

            $purchase = Purchase::with('items')
                ->findOrFail($purchaseId);

            foreach ($purchase->items as $item) {

                $typeId = $item->sea_cucumber_type_id;

                if (! isset($summary[$typeId])) {

                    $summary[$typeId] = [

                        'weight' => 0,

                    ];

                }

                $summary[$typeId]['weight']
                    += (float) $item->purchase_weight;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Shipment Item baru
        |--------------------------------------------------------------------------
        */

        foreach ($summary as $typeId => $value) {

            ShipmentItem::create([

                'shipment_id' => $shipment->id,

                'sea_cucumber_type_id' => $typeId,

                'weight' => $value['weight'],

                'status' => 'Belum Dijual',

            ]);

        }

        return $shipment->fresh([
            'items',
            'purchases',
        ]);

    });
}

/*
|--------------------------------------------------------------------------
| DELETE SHIPMENT
|--------------------------------------------------------------------------
*/

public function deleteShipment(
    Shipment $shipment
): void
{
    DB::transaction(function () use ($shipment) {

        ShipmentPurchase::where(
            'shipment_id',
            $shipment->id
        )->delete();

        ShipmentItem::where(
            'shipment_id',
            $shipment->id
        )->delete();

        $shipment->delete();

    });
}

/*
|--------------------------------------------------------------------------
| SHIPMENT SUMMARY
|--------------------------------------------------------------------------
*/
public function summary(
    Shipment $shipment
): array
{
    $shipment->loadMissing([
        'items.type',
        'purchases.purchase',
        'sales.items',
    ]);

    return [

        'purchase_count' => $this->purchaseSummary($shipment),

        'type_count' => $this->typeSummary($shipment),

        'total_weight' => $this->totalShipmentWeight($shipment),

        'grand_total' => $this->grandTotal($shipment),

    ];
}

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

public function purchaseSummary(
    Shipment $shipment
): int
{
    return $shipment->purchases->count();
}

public function typeSummary(
    Shipment $shipment
): int
{
    return $shipment->items->count();
}

/*
|--------------------------------------------------------------------------
| TOTAL BERAT SHIPMENT
|--------------------------------------------------------------------------
*/

public function totalShipmentWeight(
    Shipment $shipment
): float
{
    return (float) $shipment->items
        ->sum('weight');
}

/*
|--------------------------------------------------------------------------
| TOTAL MODAL PEMBELIAN
|--------------------------------------------------------------------------
*/

public function grandTotal(
    Shipment $shipment
): float
{
    return (float) $shipment->purchases
        ->sum(function ($shipmentPurchase) {

            return $shipmentPurchase
                ->purchase
                ->grand_total;

        });
}

/*
|--------------------------------------------------------------------------
| PROGRESS PER TYPE
|--------------------------------------------------------------------------
*/

public function progressPerType(
    Shipment $shipment
): array
{
    $shipment->loadMissing([
        'items.type',
    ]);

    return $shipment->items
        ->map(function ($item) {

            return [

                'shipment_item_id' => $item->id,

                'type_id' => $item->sea_cucumber_type_id,

                'type_name' => $item->type->name,

                'weight' => (float) $item->weight,

                'status' => $item->status,

            ];

        })
        ->values()
        ->toArray();
}

/*
|--------------------------------------------------------------------------
| GENERATE SHIPMENT NUMBER
|--------------------------------------------------------------------------
*/

protected function generateShipmentNumber(): string
{
    $last = Shipment::latest('id')->first();

    $next = $last
        ? $last->id + 1
        : 1;

    return 'SHP-'
        . str_pad(
            $next,
            6,
            '0',
            STR_PAD_LEFT
        );
}
}