<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\Expense;
use App\Models\InvestorLedger;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Support\Collection;

class CashService
{
    /*
    |--------------------------------------------------------------------------
    | SALE CASH
    |--------------------------------------------------------------------------
    */

    public function createFromSale(Sale $sale): CashTransaction
    {
        $sale->loadMissing('items');

        return CashTransaction::create([

            'transaction_date' => $sale->sale_date,

            'transaction_type' => 'Penjualan',

            'reference_type' => 'sale',

            'reference_id' => $sale->id,

            'description' => $this->saleDescription($sale),

            'cash_in' => $this->saleAmount($sale),

            'cash_out' => 0,

            'note' => $sale->note,

        ]);
    }

    public function updateFromSale(Sale $sale): CashTransaction
    {
        $sale->loadMissing('items');

        $cash = CashTransaction::where('reference_type', 'sale')
            ->where('reference_id', $sale->id)
            ->where('transaction_type', 'Penjualan')
            ->where('cash_in', '>', 0)
            ->oldest('id')
            ->first();

        if (! $cash) {

            return $this->createFromSale($sale);

        }

        $cash->update([

            'transaction_date' => $sale->sale_date,

            'description' => $this->saleDescription($sale),

            'cash_in' => $this->saleAmount($sale),

            'cash_out' => 0,

            'note' => $sale->note,

        ]);

        return $cash->fresh();
    }

    public function deleteFromSale(Sale $sale): void
    {
        CashTransaction::where('reference_type', 'sale')
            ->where('reference_id', $sale->id)
            ->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | INVESTOR CASH
    |--------------------------------------------------------------------------
    */

    public function createFromInvestorLedger(
        InvestorLedger $ledger
    ): CashTransaction
    {
        $ledger->loadMissing('investor');

        return CashTransaction::create([

            'transaction_date' => $ledger->transaction_date,

            'transaction_type' => $ledger->transaction_type === 'deposit'
                ? 'Modal Investor'
                : 'Penarikan Modal',

            'reference_type' => 'investor_ledger',

            'reference_id' => $ledger->id,

            'description' => $this->investorDescription($ledger),

            'cash_in' => $ledger->transaction_type === 'deposit'
                ? $ledger->amount
                : 0,

            'cash_out' => $ledger->transaction_type === 'withdraw'
                ? $ledger->amount
                : 0,

            'note' => $ledger->note,

        ]);
    }

    public function updateFromInvestorLedger(
        InvestorLedger $ledger
    ): CashTransaction
    {
        $ledger->loadMissing('investor');

        $cash = CashTransaction::where('reference_type', 'investor_ledger')
            ->where('reference_id', $ledger->id)
            ->oldest('id')
            ->first();

        if (! $cash) {

            return $this->createFromInvestorLedger($ledger);

        }

        $cash->update([

            'transaction_date' => $ledger->transaction_date,

            'transaction_type' => $ledger->transaction_type === 'deposit'
                ? 'Modal Investor'
                : 'Penarikan Modal',

            'description' => $this->investorDescription($ledger),

            'cash_in' => $ledger->transaction_type === 'deposit'
                ? $ledger->amount
                : 0,

            'cash_out' => $ledger->transaction_type === 'withdraw'
                ? $ledger->amount
                : 0,

            'note' => $ledger->note,

        ]);

        return $cash->fresh();
    }

    public function deleteFromInvestorLedger(
        InvestorLedger $ledger
    ): void
    {
        CashTransaction::where('reference_type', 'investor_ledger')
            ->where('reference_id', $ledger->id)
            ->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | PURCHASE CASH
    |--------------------------------------------------------------------------
    */

    public function createFromPurchase(Purchase $purchase): CashTransaction
    {
        $purchase->loadMissing('supplier');

        return CashTransaction::create([

            'transaction_date' => $purchase->purchase_date,

            'transaction_type' => 'Pembelian',

            'reference_type' => 'purchase',

            'reference_id' => $purchase->id,

            'description' => $this->purchaseDescription($purchase),

            'cash_in' => 0,

            'cash_out' => $purchase->grand_total,

            'note' => $purchase->note,

        ]);
    }

    public function updateFromPurchase(Purchase $purchase): CashTransaction
    {
        $purchase->loadMissing('supplier');

        $cash = CashTransaction::where('reference_type', 'purchase')
            ->where('reference_id', $purchase->id)
            ->where('transaction_type', 'Pembelian')
            ->where('cash_out', '>', 0)
            ->oldest('id')
            ->first();

        if (! $cash) {

            return $this->createFromPurchase($purchase);

        }

        $cash->update([

            'transaction_date' => $purchase->purchase_date,

            'description' => $this->purchaseDescription($purchase),

            'cash_in' => 0,

            'cash_out' => $purchase->grand_total,

            'note' => $purchase->note,

        ]);

        return $cash->fresh();
    }

    public function deleteFromPurchase(
        Purchase $purchase
    ): void
    {
        CashTransaction::where('reference_type', 'purchase')
            ->where('reference_id', $purchase->id)
            ->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | EXPENSE CASH
    |--------------------------------------------------------------------------
    */

    public function createFromExpense(Expense $expense): CashTransaction
    {
        return CashTransaction::create([

            'transaction_date' => $expense->expense_date,

            'transaction_type' => 'Operasional',

            'reference_type' => 'expense',

            'reference_id' => $expense->id,

            'description' => $this->expenseDescription($expense),

            'cash_in' => 0,

            'cash_out' => $expense->amount,

            'note' => $expense->description,

        ]);
    }

    public function updateFromExpense(Expense $expense): CashTransaction
    {
        $cash = CashTransaction::where('reference_type', 'expense')
            ->where('reference_id', $expense->id)
            ->where('transaction_type', 'Operasional')
            ->where('cash_out', '>', 0)
            ->oldest('id')
            ->first();

        if (! $cash) {

            return $this->createFromExpense($expense);

        }

        $cash->update([

            'transaction_date' => $expense->expense_date,

            'description' => $this->expenseDescription($expense),

            'cash_in' => 0,

            'cash_out' => $expense->amount,

            'note' => $expense->description,

        ]);

        return $cash->fresh();
    }

    public function deleteFromExpense(
        Expense $expense
    ): void
    {
        CashTransaction::where('reference_type', 'expense')
            ->where('reference_id', $expense->id)
            ->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | CASH BOOK
    |--------------------------------------------------------------------------
    */

    public function transactions(array $filters = []): Collection
    {
        $query = CashTransaction::query();

        if (! empty($filters['from'])) {

            $query->whereDate(
                'transaction_date',
                '>=',
                $filters['from']
            );

        }

        if (! empty($filters['to'])) {

            $query->whereDate(
                'transaction_date',
                '<=',
                $filters['to']
            );

        }

        if (! empty($filters['transaction_type'])) {

            $query->where(
                'transaction_type',
                $filters['transaction_type']
            );

        }

        if (! empty($filters['reference_type'])) {

            $query->where(
                'reference_type',
                $filters['reference_type']
            );

        }

        return $query
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();
    }

    public function summary(Collection $transactions): array
    {
        $totalCashIn = (float) $transactions->sum('cash_in');

        $totalCashOut = (float) $transactions->sum('cash_out');

        return [

            'total_cash_in' => $totalCashIn,

            'total_cash_out' => $totalCashOut,

            'ending_balance' => $totalCashIn - $totalCashOut,

            'total_transactions' => $transactions->count(),

        ];
    }

    public function runningBalance(Collection $transactions): Collection
    {
        $runningBalance = 0;

        return $transactions->map(function (CashTransaction $transaction) use (&$runningBalance) {

            $runningBalance += (float) $transaction->cash_in;

            $runningBalance -= (float) $transaction->cash_out;

            $transaction->running_balance = $runningBalance;

            return $transaction;

        });
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    protected function saleAmount(Sale $sale): float
    {
        $sale->loadMissing('items');

        return (float) $sale->items
            ->sum('subtotal');
    }

    protected function saleDescription(Sale $sale): string
    {
        return 'Invoice '
            . $sale->invoice_number
            . ' - '
            . $sale->buyer;
    }

    protected function investorDescription(
        InvestorLedger $ledger
    ): string
    {
        $type = $ledger->transaction_type === 'deposit'
            ? 'Modal Investor'
            : 'Penarikan Modal';

        return $type
            . ' - '
            . $ledger->investor->name;
    }

    protected function expenseDescription(Expense $expense): string
    {
        return $expense->expense_name;
    }

    protected function purchaseDescription(Purchase $purchase): string
    {
        return 'Purchase '
            . $purchase->purchase_number
            . ' - '
            . ($purchase->supplier->name ?? '-');
    }
}
