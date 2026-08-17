<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    protected CashService $cashService;

    public function __construct(
        CashService $cashService
    )
    {
        $this->cashService = $cashService;
    }

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

                'shipment_id' => null,

                'expense_name' => $data['expense_name'],

                'amount' => $data['amount'],

                'description' => $data['description'],

                'note' => $data['note'] ?? null,

            ]);

            $this->cashService
                ->createFromExpense($expense);

            return $expense->fresh();

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

                'shipment_id' => null,

                'expense_name' => $data['expense_name'],

                'amount' => $data['amount'],

                'description' => $data['description'],

                'note' => $data['note'] ?? null,

            ]);

            $this->cashService
                ->updateFromExpense($expense);

            return $expense->fresh();

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

            $this->cashService
                ->deleteFromExpense($expense);

            $expense->delete();

        });
    }
}
