<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;

class ShipmentService
{
    /**
     * Detail Shipment
     */
    public function detail(Shipment $shipment): array
    {
        $shipment->load([

            'items.purchase.supplier',

            'items.purchase.items.type',

            'sales.items.type'

        ]);

        return [

            'shipment' => $shipment,

            'purchaseSummary' => $this->purchaseSummary($shipment),

            'saleSummary' => $this->saleSummary($shipment),

            'invoiceSummary' => $this->invoiceSummary($shipment),

            'totalSoldKg' => $this->totalSoldKg($shipment),

            'totalInvoice' => $shipment->sales->count()

        ];
    }

    /**
     * Ringkasan pembelian
     */
    public function purchaseSummary(Shipment $shipment)
    {
        $summary = [];

        foreach ($shipment->items as $shipmentItem) {

            $purchase = $shipmentItem->purchase;

            foreach ($purchase->items as $item) {

                $typeId = $item->sea_cucumber_type_id;

                if (!isset($summary[$typeId])) {

                    $summary[$typeId] = [

                        'type_id' => $typeId,

                        'type_name' => $item->type->name,

                        'purchase_weight' => 0,

                        'sold_weight' => 0

                    ];

                }

                $summary[$typeId]['purchase_weight']
                    += $item->purchase_weight;
            }
        }

        foreach ($shipment->sales as $sale) {

            foreach ($sale->items as $item) {

                if(isset($summary[$item->sea_cucumber_type_id])){

                    $summary[$item->sea_cucumber_type_id]['sold_weight']
                        += $item->weight;

                }

            }

        }

        return collect($summary)
            ->sortBy('type_name')
            ->values();
    }
    /**
     * Ringkasan Penjualan
     */
    public function saleSummary(Shipment $shipment)
    {
        return $shipment->sales()
            ->with('items.type')
            ->orderBy('sale_date')
            ->get();
    }

    /**
     * Total Invoice
     */
    public function invoiceSummary(Shipment $shipment)
    {
        return $shipment->sales()
            ->orderBy('sale_date')
            ->get()
            ->map(function ($sale) {

                return [

                    'id' => $sale->id,

                    'invoice' => $sale->invoice_number,

                    'buyer' => $sale->buyer,

                    'date' => $sale->sale_date,

                    'total_kg' => $sale->items->sum('weight'),

                    'grand_total' => $sale->items->sum('subtotal')

                ];

            });

    }

    /**
     * Total Kg Terjual
     */
    public function totalSoldKg(Shipment $shipment): float
    {
        return (float) $shipment->sales()
            ->with('items')
            ->get()
            ->flatMap(function ($sale) {

                return $sale->items;

            })
            ->sum('weight');
    }

    /**
     * Total Berat Pembelian
     */
    public function totalPurchaseKg(Shipment $shipment): float
    {
        $total = 0;

        foreach ($shipment->items as $shipmentItem) {

            foreach ($shipmentItem->purchase->items as $item) {

                $total += $item->purchase_weight;

            }

        }

        return (float) $total;
    }

    /**
     * Progress Shipment
     */
    public function progress(Shipment $shipment): array
    {
        $purchase = $this->totalPurchaseKg($shipment);

        $sold = $this->totalSoldKg($shipment);

        if ($sold <= 0) {

            return [

                'status' => 'Belum Dijual',

                'percent' => 0

            ];

        }

        if ($sold >= $purchase) {

            return [

                'status' => 'Selesai',

                'percent' => 100

            ];

        }

        return [

            'status' => 'Terjual Sebagian',

            'percent' => round(

                ($sold / max($purchase, 1)) * 100,

                2

            )

        ];
    }
        /**
     * Jenis Teripang Yang Ada Di Shipment
     *
     * Dipakai untuk dropdown Tambah Penjualan.
     * Hanya menampilkan jenis yang benar-benar
     * berasal dari Purchase pada Shipment ini.
     */
    public function availableTypes(Shipment $shipment)
    {
        $types = [];

        foreach ($shipment->items as $shipmentItem) {

            foreach ($shipmentItem->purchase->items as $item) {

                $id = $item->sea_cucumber_type_id;

                if (!isset($types[$id])) {

                    $types[$id] = [

                        'id' => $id,

                        'name' => $item->type->name,

                        'purchase_weight' => 0,

                        'sold_weight' => 0,

                        'remaining_weight' => 0

                    ];

                }

                $types[$id]['purchase_weight']
                    += $item->purchase_weight;

            }

        }

        foreach ($shipment->sales as $sale) {

            foreach ($sale->items as $item) {

                if(isset($types[$item->sea_cucumber_type_id])){

                    $types[$item->sea_cucumber_type_id]['sold_weight']
                        += $item->weight;

                }

            }

        }

        foreach ($types as &$type){

            $type['remaining_weight']
                = $type['purchase_weight']
                - $type['sold_weight'];

        }

        return collect($types)
            ->sortBy('name')
            ->values();
    }

    /**
     * Validasi Berat Penjualan
     *
     * Mencegah berat jual melebihi
     * total berat pembelian.
     */
    public function validateSaleWeight(
        Shipment $shipment,
        int $typeId,
        float $weight
    ): bool
    {
        $types = $this->availableTypes($shipment);

        $type = $types->firstWhere('id', $typeId);

        if (!$type) {

            return false;

        }

        return $weight <= $type['remaining_weight'];
    }

    /**
     * Hitung Grand Total Invoice
     */
    public function calculateGrandTotal(array $items): float
    {
        $grandTotal = 0;

        foreach ($items as $item) {

            $grandTotal +=
                ($item['weight'] * $item['price']);

        }

        return $grandTotal;
    }
        /**
     * Update Status Shipment
     */
    public function updateShipmentStatus(Shipment $shipment): void
    {
        $progress = $this->progress($shipment);

        $shipment->status = $progress['status'];

        $shipment->save();
    }

    /**
     * Tandai Shipment Selesai
     */
    public function complete(Shipment $shipment): void
    {
        $shipment->status = 'Selesai';

        $shipment->save();
    }

    /**
     * Tandai Barang Sudah Sampai Gudang
     */
    public function arrived(Shipment $shipment): void
    {
        $shipment->status = 'Sampai Gudang';

        $shipment->save();
    }

    /**
     * Data Untuk Form Tambah Penjualan
     */
    public function saleForm(Shipment $shipment): array
    {
        return [

            'shipment' => $shipment,

            'types' => $this->availableTypes($shipment)

        ];
    }

    /**
     * Refresh Dashboard Shipment
     */
    public function refresh(Shipment $shipment): array
    {
        return [

            'summary' => $this->purchaseSummary($shipment),

            'invoice' => $this->invoiceSummary($shipment),

            'progress' => $this->progress($shipment),

            'soldKg' => $this->totalSoldKg($shipment),

            'purchaseKg' => $this->totalPurchaseKg($shipment)

        ];
    }

}