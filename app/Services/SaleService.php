<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function __construct(
        protected CashService $cashService
    ) {
    }

    public function createSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {

            $this->validateItems($data);

            $sale = Sale::create([

                'shipment_id' => null,

                'invoice_number' => $data['invoice_number'],

                'sale_date' => $data['sale_date'],

                'buyer' => $data['buyer'],

                'note' => $data['note'] ?? null,

            ]);

            $this->syncSaleItems(
                $sale,
                $data
            );

            $this->cashService
                ->createFromSale($sale);

            return $sale->fresh([
                'items.type',
            ]);

        });
    }

    public function updateSale(
        Sale $sale,
        array $data
    ): Sale {
        return DB::transaction(function () use ($sale, $data) {

            $this->validateItems($data);

            $sale->update([

                'invoice_number' => $data['invoice_number'],

                'sale_date' => $data['sale_date'],

                'buyer' => $data['buyer'],

                'note' => $data['note'] ?? null,

            ]);

            $sale->items()->delete();

            $this->syncSaleItems(
                $sale,
                $data
            );

            $this->cashService
                ->updateFromSale($sale);

            return $sale->fresh([
                'items.type',
            ]);

        });
    }

    public function deleteSale(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {

            $this->cashService
                ->deleteFromSale($sale);

            $sale->items()->delete();

            $sale->delete();

        });
    }

    public function grandTotal(Sale $sale): float
    {
        $sale->loadMissing('items');

        return (float) $sale->items
            ->sum('subtotal');
    }

    public function soldTypeSummary(Sale $sale): array
    {
        $sale->loadMissing('items.type');

        return $sale->items
            ->map(function (SaleItem $item) {

                return [

                    'type_id' => $item->sea_cucumber_type_id,

                    'type_name' => $item->type->name,

                    'weight' => (float) $item->weight,

                    'status' => $item->status,

                    'subtotal' => (float) $item->subtotal,

                ];

            })
            ->values()
            ->toArray();
    }

    protected function syncSaleItems(
        Sale $sale,
        array $data
    ): void {
        foreach (array_keys($data['type_id']) as $index) {

            $weight = (float) $data['weight'][$index];

            $price = (float) $data['price'][$index];

            SaleItem::create([

                'sale_id' => $sale->id,

                'sea_cucumber_type_id' => $data['type_id'][$index],

                'shipment_item_id' => null,

                'weight' => $weight,

                'price' => $price,

                'subtotal' => $weight * $price,

                'status' => $data['status'][$index],

            ]);

        }
    }

    protected function validateItems(array $data): void
    {
        $typeIds = $data['type_id'] ?? [];

        if (count($typeIds) !== count(array_unique($typeIds))) {

            throw ValidationException::withMessages([

                'type_id' => 'Jenis teripang yang sama tidak boleh muncul dua kali dalam invoice yang sama.',

            ]);

        }
    }
}
