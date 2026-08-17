<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;
use App\Models\SeaCucumberType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockOverviewController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'active');

        if (! in_array($filter, ['active', 'sold', 'all'], true)) {
            $filter = 'active';
        }

        $query = PurchaseItem::query()
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join(
                'sea_cucumber_types',
                'purchase_items.sea_cucumber_type_id',
                '=',
                'sea_cucumber_types.id'
            )
            ->select([
                'sea_cucumber_types.id',
                'sea_cucumber_types.name',
                'sea_cucumber_types.stock_overview_status',
                DB::raw('SUM(purchase_items.purchase_weight) as total_purchased_weight'),
                DB::raw('MAX(purchases.purchase_date) as last_purchase_date'),
            ])
            ->groupBy([
                'sea_cucumber_types.id',
                'sea_cucumber_types.name',
                'sea_cucumber_types.stock_overview_status',
            ])
            ->orderBy('sea_cucumber_types.name');

        if ($filter === 'active') {
            $query->where('sea_cucumber_types.stock_overview_status', 'Active');
        }

        if ($filter === 'sold') {
            $query->where('sea_cucumber_types.stock_overview_status', 'Sold');
        }

        $items = $query->paginate(20)
            ->withQueryString();

        return view(
            'stock-overview.index',
            compact(
                'items',
                'filter'
            )
        );
    }

    public function updateStatus(
        Request $request,
        SeaCucumberType $seaCucumberType
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Sold',
                ]),
            ],
        ]);

        $seaCucumberType->update([
            'stock_overview_status' => $validated['status'],
        ]);

        return redirect()
            ->route(
                'stock-overview.index',
                ['filter' => $request->query('filter', 'active')]
            )
            ->with(
                'success',
                'Status stock overview berhasil diperbarui.'
            );
    }
}
