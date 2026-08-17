<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\SaleItem;

class ProfitService
{
    public function purchaseTotal($startDate = null, $endDate = null): float
    {
        return (float) Purchase::query()
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('purchase_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('purchase_date', '<=', $endDate);
            })
            ->sum('grand_total');
    }

    public function saleTotal($startDate = null, $endDate = null): float
    {
        return (float) SaleItem::query()
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query
                    ->when($startDate, function ($saleQuery) use ($startDate) {
                        $saleQuery->whereDate('sale_date', '>=', $startDate);
                    })
                    ->when($endDate, function ($saleQuery) use ($endDate) {
                        $saleQuery->whereDate('sale_date', '<=', $endDate);
                    });
            })
            ->sum('subtotal');
    }

    public function operationalExpense($startDate = null, $endDate = null): float
    {
        return (float) Expense::query()
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('expense_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('expense_date', '<=', $endDate);
            })
            ->sum('amount');
    }

    public function companyProfit($startDate = null, $endDate = null): array
    {
        $purchase = $this->purchaseTotal($startDate, $endDate);
        $sale = $this->saleTotal($startDate, $endDate);
        $expense = $this->operationalExpense($startDate, $endDate);

        return [
            'gross_profit' => $sale - $purchase,
            'operational_expense' => $expense,
            'net_profit' => $sale - $purchase - $expense,
            'shipment_profit' => $sale - $purchase,
            'general_expense' => $expense,
            'company_profit' => $sale - $purchase - $expense,
            'purchase' => $purchase,
            'sale' => $sale,
            'expense' => $expense,
            'profit' => $sale - $purchase - $expense,
        ];
    }
}
