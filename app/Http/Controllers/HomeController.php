<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\InvestorLedger;
use App\Models\Purchase;
use App\Models\Sale;
use App\Services\CashService;
use App\Services\ProfitService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Throwable;

class HomeController extends Controller
{
    public function index(
        Request $request,
        CashService $cashService,
        ProfitService $profitService
    ) {
        $company = 'Teripang Management System';

        $cashSummary = $cashService->summary(
            $cashService->transactions()
        );

        $dashboardPeriod = $this->dashboardPeriod($request);

        $companyProfit = $profitService->companyProfit(
            $dashboardPeriod['start_date'],
            $dashboardPeriod['end_date']
        );

        $totalPurchase = $companyProfit['purchase'];

        $totalSale = $companyProfit['sale'];

        $grossProfit = $companyProfit['gross_profit'];

        $operationalExpense = $companyProfit['operational_expense'];

        $netProfit = $companyProfit['net_profit'];

        $investorBalances = InvestorLedger::query()
            ->select('investor_id')
            ->selectRaw("
                SUM(
                    CASE
                        WHEN transaction_type = 'deposit' THEN amount
                        WHEN transaction_type = 'withdraw' THEN -amount
                        ELSE 0
                    END
                ) as balance
            ")
            ->groupBy('investor_id')
            ->get();

        $activeInvestorBalances = $investorBalances
            ->filter(function ($ledger) {
                return (float) $ledger->balance > 0;
            });

        $investorSummary = [
            'active_investors' => $activeInvestorBalances->count(),
            'active_capital' => (float) $activeInvestorBalances->sum('balance'),
        ];

        $recentActivities = $this->recentActivities();

        return view(
            'home',
            compact(
                'company',
                'cashSummary',
                'companyProfit',
                'dashboardPeriod',
                'totalPurchase',
                'totalSale',
                'grossProfit',
                'operationalExpense',
                'netProfit',
                'investorSummary',
                'recentActivities'
            )
        );
    }

    protected function dashboardPeriod(Request $request): array
    {
        $period = $request->query('period', 'this_month');
        $today = now();

        if ($period === 'last_month') {
            $lastMonth = $today->copy()->subMonthNoOverflow();

            return [
                'period' => $period,
                'start_date' => $lastMonth->copy()->startOfMonth()->toDateString(),
                'end_date' => $lastMonth->copy()->endOfMonth()->toDateString(),
                'custom_start_date' => null,
                'custom_end_date' => null,
            ];
        }

        if ($period === 'this_year') {
            return [
                'period' => $period,
                'start_date' => $today->copy()->startOfYear()->toDateString(),
                'end_date' => $today->copy()->endOfYear()->toDateString(),
                'custom_start_date' => null,
                'custom_end_date' => null,
            ];
        }

        if ($period === 'all') {
            return [
                'period' => $period,
                'start_date' => null,
                'end_date' => null,
                'custom_start_date' => null,
                'custom_end_date' => null,
            ];
        }

        if ($period === 'custom') {
            $startDate = $this->parseDate($request->query('start_date'));
            $endDate = $this->parseDate($request->query('end_date'));

            return [
                'period' => $period,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'custom_start_date' => $startDate,
                'custom_end_date' => $endDate,
            ];
        }

        return [
            'period' => 'this_month',
            'start_date' => $today->copy()->startOfMonth()->toDateString(),
            'end_date' => $today->copy()->endOfMonth()->toDateString(),
            'custom_start_date' => null,
            'custom_end_date' => null,
        ];
    }

    protected function parseDate($value): ?string
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (Throwable) {
            return null;
        }
    }

    protected function recentActivities(): Collection
    {
        $purchases = Purchase::with('supplier')
            ->latest('purchase_date')
            ->limit(10)
            ->get()
            ->map(function (Purchase $purchase) {
                return [
                    'date' => $purchase->purchase_date,
                    'type' => 'Purchase',
                    'description' => $purchase->purchase_number,
                    'amount' => (float) $purchase->grand_total,
                ];
            });

        $sales = Sale::withSum('items', 'subtotal')
            ->latest('sale_date')
            ->limit(10)
            ->get()
            ->map(function (Sale $sale) {
                return [
                    'date' => $sale->sale_date,
                    'type' => 'Sale',
                    'description' => $sale->invoice_number,
                    'amount' => (float) $sale->items_sum_subtotal,
                ];
            });

        $expenses = Expense::latest('expense_date')
            ->limit(10)
            ->get()
            ->map(function (Expense $expense) {
                return [
                    'date' => $expense->expense_date,
                    'type' => 'Expense',
                    'description' => $expense->expense_name,
                    'amount' => (float) $expense->amount,
                ];
            });

        $investorLedgers = InvestorLedger::with('investor')
            ->latest('transaction_date')
            ->limit(10)
            ->get()
            ->map(function (InvestorLedger $ledger) {
                return [
                    'date' => $ledger->transaction_date,
                    'type' => 'Investor Ledger',
                    'description' => $ledger->investor->name,
                    'amount' => (float) $ledger->amount,
                ];
            });

        return $purchases
            ->merge($sales)
            ->merge($expenses)
            ->merge($investorLedgers)
            ->sortByDesc('date')
            ->take(10)
            ->values();
    }
}
