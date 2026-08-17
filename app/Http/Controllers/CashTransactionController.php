<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Services\CashService;
use Illuminate\Http\Request;

class CashTransactionController extends Controller
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
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $transactions = $this->cashService
            ->transactions(
                $request->only([
                    'from',
                    'to',
                    'transaction_type',
                    'reference_type',
                ])
            );

        $summary = $this->cashService
            ->summary($transactions);

        $transactions = $this->cashService
            ->runningBalance($transactions);

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
