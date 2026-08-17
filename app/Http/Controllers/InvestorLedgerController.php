<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Models\InvestorLedger;
use App\Services\CashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestorLedgerController extends Controller
{
    protected CashService $cashService;

    public function __construct(
        CashService $cashService
    )
    {
        $this->cashService = $cashService;
    }

    public function index()
    {
        $ledgers = InvestorLedger::with('investor')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->get();

        return view('investor-ledgers.index', compact('ledgers'));
    }

    public function create()
    {
        $investors = Investor::orderBy('name')->get();

        return view('investor-ledgers.create', compact('investors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:deposit,withdraw',
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable'
        ]);

        DB::transaction(function () use ($request) {

            $ledger = InvestorLedger::create($request->all());

            $this->cashService
                ->createFromInvestorLedger($ledger);

        });

        return redirect()
            ->route('investor-ledgers.index')
            ->with('success', 'Transaksi modal berhasil disimpan.');
    }

    public function show(InvestorLedger $investorLedger)
    {
        return redirect()->route('investor-ledgers.index');
    }

    public function edit(InvestorLedger $investorLedger)
    {
        return redirect()->route('investor-ledgers.index');
    }

    public function update(Request $request, InvestorLedger $investorLedger)
    {
        return redirect()->route('investor-ledgers.index');
    }

    public function destroy(InvestorLedger $investorLedger)
    {
        DB::transaction(function () use ($investorLedger) {

            $this->cashService
                ->deleteFromInvestorLedger($investorLedger);

            $investorLedger->delete();

        });

        return redirect()
            ->route('investor-ledgers.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
