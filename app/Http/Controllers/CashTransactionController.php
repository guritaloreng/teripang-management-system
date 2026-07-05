<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use Illuminate\Http\Request;

class CashTransactionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = CashTransaction::query();

        if ($request->filled('from')) {

            $query->whereDate(
                'transaction_date',
                '>=',
                $request->from
            );

        }

        if ($request->filled('to')) {

            $query->whereDate(
                'transaction_date',
                '<=',
                $request->to
            );

        }

        $transactions = $query
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();

        $runningBalance = 0;

        foreach ($transactions as $transaction) {

            $runningBalance += $transaction->cash_in;

            $runningBalance -= $transaction->cash_out;

            $transaction->running_balance = $runningBalance;

        }
                $summary = [

            'total_cash_in' => (float) $transactions->sum('cash_in'),

            'total_cash_out' => (float) $transactions->sum('cash_out'),

            'ending_balance' => (float) (
                $transactions->sum('cash_in')
                -
                $transactions->sum('cash_out')
            ),

            'total_transactions' => $transactions->count(),

        ];

        return view(

            'cash-transactions.index',

            compact(

                'transactions',

                'summary'

            )

        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        CashTransaction $cashTransaction
    )
    {
        return view(

            'cash-transactions.show',

            compact(

                'cashTransaction'

            )

        );
    }
        /*
    |--------------------------------------------------------------------------
    | DETAIL REFERENCE
    |--------------------------------------------------------------------------
    |
    | Nanti pada sprint berikutnya method ini akan dipakai untuk
    | membuka transaksi asal (Purchase, Sale, Expense, Investor)
    | berdasarkan reference_type dan reference_id.
    |
    */

    protected function referenceUrl(
        CashTransaction $cashTransaction
    ): ?string
    {
        return null;
    }
}