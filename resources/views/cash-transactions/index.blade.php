@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>💵 Buku Kas</h2>

            <small class="text-muted">

                Riwayat seluruh transaksi kas perusahaan

            </small>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <small>Total Kas Masuk</small>

                    <h4 class="text-success">

                        Rp {{ number_format($summary['total_cash_in'],0,',','.') }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <small>Total Kas Keluar</small>

                    <h4 class="text-danger">

                        Rp {{ number_format($summary['total_cash_out'],0,',','.') }}

                    </h4>

                </div>

            </div>

        </div>
                <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <small>Saldo Akhir</small>

                    <h4 class="text-primary">

                        Rp {{ number_format($summary['ending_balance'],0,',','.') }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <small>Total Transaksi</small>

                    <h4>

                        {{ $summary['total_transactions'] }}

                    </h4>

                </div>

            </div>

        </div>

    </div>

    <div class="card mb-3">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('cash-book.index') }}">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">

                            Dari Tanggal

                        </label>

                        <input
                            type="date"
                            name="from"
                            value="{{ request('from') }}"
                            class="form-control">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">

                            Sampai Tanggal

                        </label>

                        <input
                            type="date"
                            name="to"
                            value="{{ request('to') }}"
                            class="form-control">

                    </div>

                    <div class="col-md-4 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Tampilkan

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card">

        <div class="card-header bg-dark text-white">

            Buku Kas

        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th>Tanggal</th>

                        <th>Jenis</th>

                        <th>Keterangan</th>

                        <th class="text-end">Kas Masuk</th>

                        <th class="text-end">Kas Keluar</th>

                        <th class="text-end">Saldo</th>

                    </tr>

                </thead>

                <tbody>
                                    @forelse($transactions as $transaction)

                    <tr>

                        <td>

                            {{ $transaction->transaction_date->format('d-m-Y') }}

                        </td>

                        <td>

                            {{ $transaction->transaction_type }}

                        </td>

                        <td>

                            @switch($transaction->reference_type)

                                @case('purchase')

                                    <a href="{{ route('purchases.show', $transaction->reference_id) }}">
                                        {{ $transaction->description }}
                                    </a>
                                    @break

                                @case('sale')

                                    <a href="{{ route('sales.show', $transaction->reference_id) }}">
                                        {{ $transaction->description }}
                                    </a>
                                    @break

                                @case('expense')

                                    {{ $transaction->description }}
                                    @break

                                @default

                                    {{ $transaction->description }}

                            @endswitch

                        </td>

                        <td class="text-end text-success">

                            @if($transaction->cash_in > 0)

                                Rp {{ number_format($transaction->cash_in,0,',','.') }}

                            @else

                                -

                            @endif

                        </td>

                        <td class="text-end text-danger">

                            @if($transaction->cash_out > 0)

                                Rp {{ number_format($transaction->cash_out,0,',','.') }}

                            @else

                                -

                            @endif

                        </td>

                        <td class="text-end fw-bold">

                            Rp {{ number_format($transaction->running_balance,0,',','.') }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-4">

                            Belum ada transaksi kas.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection