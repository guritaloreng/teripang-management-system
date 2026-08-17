<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected ExpenseService $expenseService;

    public function __construct(
        ExpenseService $expenseService
    )
    {
        $this->expenseService = $expenseService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $expenses = Expense::latest('expense_date')
            ->paginate(20);

        return view(
            'expenses.index',
            compact('expenses')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'expenses.create'
        );
    }

    public function show(Expense $expense)
    {
        return redirect()
            ->route('expenses.index');
    }

        /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'expense_date' => ['required', 'date'],

            'expense_name' => ['required', 'string', 'max:255'],

            'amount' => ['required', 'numeric', 'min:0'],

            'description' => ['required', 'string', 'max:255'],

            'note' => ['nullable', 'string'],

        ]);

        $expense = $this->expenseService
            ->createExpense($validated);

        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Pengeluaran berhasil disimpan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Expense $expense)
    {
        return view(
            'expenses.edit',
            compact('expense')
        );
    }
        /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Expense $expense
    )
    {
        $validated = $request->validate([

            'expense_date' => ['required', 'date'],

            'expense_name' => ['required', 'string', 'max:255'],

            'amount' => ['required', 'numeric', 'min:0'],

            'description' => ['required', 'string', 'max:255'],

            'note' => ['nullable', 'string'],

        ]);

        $this->expenseService->updateExpense(
            $expense,
            $validated
        );

        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Pengeluaran berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Expense $expense
    )
    {
        $this->expenseService
            ->deleteExpense($expense);

        return redirect()
            ->route('expenses.index')
            ->with(
                'success',
                'Pengeluaran berhasil dihapus.'
            );
    }
}
