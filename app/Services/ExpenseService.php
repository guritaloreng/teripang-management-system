<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    /*
    |--------------------------------------------------------------------------
    | CREATE EXPENSE
    |--------------------------------------------------------------------------
    */

    public function createExpense(array $data): Expense
    {
        return DB::transaction(function () use ($data) {

            $expense = Expense::create([

                'expense_date' => $data['expense_date'],

                'shipment_id' => $data['shipment_id'] ?? null,

                'expense_name' => $data['expense_name'],

                'amount' => $data['amount'],

                'description' => $data['description'],

                'note' => $data['note'] ?? null,

            ]);

            $this->createCashTransaction($expense);

            return $expense->fresh('shipment');

        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE EXPENSE
    |--------------------------------------------------------------------------
    */

    public function updateExpense(
        Expense $expense,
        array $data
    ): Expense
    {
        return DB::transaction(function () use ($expense, $data) {

            $expense->update([

                'expense_date' => $data['expense_date'],

                'shipment_id' => $data['shipment_id'] ?? null,

                'expense_name' => $data['expense_name'],

                'amount' => $data['amount'],

                'description' => $data['description'],

                'note' => $data['note'] ?? null,

            ]);

            $this->updateCashTransaction($expense);

            return $expense->fresh('shipment');

        });
    }
        /*
    |--------------------------------------------------------------------------
    | DELETE EXPENSE
    |--------------------------------------------------------------------------
    */

    public function deleteExpense(
        Expense $expense
    ): void
    {
        DB::transaction(function () use ($expense) {

            $this->deleteCashTransaction($expense);

            $expense->delete();

        });
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE CASH TRANSACTION
    |--------------------------------------------------------------------------
    */

    protected function createCashTransaction(
        Expense $expense
    ): void
    {
        CashTransaction::create([

            'transaction_date' => $expense->expense_date,

            'transaction_type' => 'Operasional',

            'reference_type' => 'expense',

            'reference_id' => $expense->id,

            'description' => $expense->expense_name,

            'cash_in' => 0,

            'cash_out' => $expense->amount,

            'note' => $expense->description,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE CASH TRANSACTION
    |--------------------------------------------------------------------------
    */

    protected function updateCashTransaction(
        Expense $expense
    ): void
    {
        $cash = CashTransaction::where(

            'reference_type',
            'expense'

        )->where(

            'reference_id',
            $expense->id

        )->first();

        if (! $cash) {

            $this->createCashTransaction($expense);

            return;

        }

        $cash->update([

            'transaction_date' => $expense->expense_date,

            'transaction_type' => 'Operasional',

            'description' => $expense->expense_name,

            'cash_in' => 0,

            'cash_out' => $expense->amount,

            'note' => $expense->description,

        ]);
    }
        /*
    |--------------------------------------------------------------------------
    | DELETE CASH TRANSACTION
    |--------------------------------------------------------------------------
    */

    protected function deleteCashTransaction(
        Expense $expense
    ): void
    {
        CashTransaction::where(

            'reference_type',
            'expense'

        )->where(

            'reference_id',
            $expense->id

        )->delete();
    }
}