@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Buku Kas / 现金簿</h2>

            <small class="text-muted">

                Riwayat seluruh transaksi kas perusahaan / 公司现金交易记录

            </small>

        </div>

    </div>

    <div class="row mb-4 g-3">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small>Total Kas Masuk / 现金收入总额</small>

                    <h4 class="text-success">

                        Rp {{ number_format($summary['total_cash_in'],0,',','.') }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small>Total Kas Keluar / 现金支出总额</small>

                    <h4 class="text-danger">

                        Rp {{ number_format($summary['total_cash_out'],0,',','.') }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small>Saldo Akhir / 期末余额</small>

                    <h4 class="text-primary">

                        Rp {{ number_format($summary['ending_balance'],0,',','.') }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <small>Total Transaksi / 交易总数</small>

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

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">

                            Dari Tanggal / 开始日期
                        </label>

                        <input
                            type="date"
                            name="from"
                            value="{{ request('from') }}"
                            class="form-control">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">

                            Sampai Tanggal / 结束日期

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

                            Tampilkan / 显示

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card">

        <div class="card-header bg-dark text-white">

            Buku Kas / 现金簿
        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th>Tanggal / 日期</th>

                        <th>Jenis / 类型</th>

                        <th>Keterangan / 说明</th>

                        <th class="text-end">Kas Masuk / 现金收入</th>

                        <th class="text-end">Kas Keluar / 现金支出</th>

                        <th class="text-end">Saldo / 余额</th>

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

                        <td class="text-end">

                            Rp {{ number_format($transaction->running_balance,0,',','.') }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center">

                            Belum ada transaksi kas. / 暂无现金交易。

                        </td>

                    </tr>

                @endforelse
                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
