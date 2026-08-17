@extends('layouts.app')

@section('content')

@php
    $periodOptions = [
        'this_month' => 'Bulan Ini / 本月',
        'last_month' => 'Bulan Lalu / 上个月',
        'this_year' => 'Tahun Ini / 今年',
        'all' => 'Semua / 全部',
        'custom' => 'Custom / 自定义',
    ];
@endphp

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

        <div>

            <h2>Dashboard / 仪表盘</h2>

            <small class="text-muted">

                {{ $company }}

            </small>

        </div>

        <form method="GET" action="{{ route('home') }}" class="card shadow-sm">

            <div class="card-body">

                <div class="row g-2 align-items-end">

                    <div class="col-md-4">

                        <label for="period" class="form-label">Periode / 时间范围</label>

                        <select id="period" name="period" class="form-select">

                            @foreach($periodOptions as $value => $label)

                                <option value="{{ $value }}" @selected($dashboardPeriod['period'] === $value)>

                                    {{ $label }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label for="start_date" class="form-label">Dari Tanggal / 开始日期</label>

                        <input
                            id="start_date"
                            type="date"
                            name="start_date"
                            value="{{ $dashboardPeriod['custom_start_date'] }}"
                            class="form-control"
                        >

                    </div>

                    <div class="col-md-3">

                        <label for="end_date" class="form-label">Sampai Tanggal / 结束日期</label>

                        <input
                            id="end_date"
                            type="date"
                            name="end_date"
                            value="{{ $dashboardPeriod['custom_end_date'] }}"
                            class="form-control"
                        >

                    </div>

                    <div class="col-md-2 d-grid">

                        <button type="submit" class="btn btn-primary">

                            Terapkan / 应用

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">Current Cash / 当前现金</small>

                    <h5 class="mt-2 mb-0">

                        Rp {{ number_format($cashSummary['ending_balance'],0,',','.') }}

                    </h5>

                </div>

            </div>

        </div>

        <div class="col-xl-2 col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">Total Purchase / 采购总额</small>

                    <h5 class="mt-2 mb-0">

                        Rp {{ number_format($totalPurchase,0,',','.') }}

                    </h5>

                </div>

            </div>

        </div>

        <div class="col-xl-2 col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">Total Sale / 销售总额</small>

                    <h5 class="mt-2 mb-0">

                        Rp {{ number_format($totalSale,0,',','.') }}

                    </h5>

                </div>

            </div>

        </div>

        <div class="col-xl-2 col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">Gross Profit / 毛利润</small>

                    <h5 class="mt-2 mb-0">

                        Rp {{ number_format($grossProfit,0,',','.') }}

                    </h5>

                </div>

            </div>

        </div>

        <div class="col-xl-2 col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">Operational Expense / 运营费用</small>

                    <h5 class="mt-2 mb-0">

                        Rp {{ number_format($operationalExpense,0,',','.') }}

                    </h5>

                </div>

            </div>

        </div>

        <div class="col-xl-2 col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">Net Profit / 净利润</small>

                    <h5 class="mt-2 mb-0">

                        Rp {{ number_format($netProfit,0,',','.') }}

                    </h5>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-3 mb-4">

        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-success text-white">

                    Ringkasan Investor / 投资人汇总

                </div>

                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-md-6">

                            <h4>{{ $investorSummary['active_investors'] }}</h4>

                            <small class="text-muted">Investor Aktif / 活跃投资人</small>

                        </div>

                        <div class="col-md-6">

                            <h4>

                                Rp {{ number_format($investorSummary['active_capital'],0,',','.') }}

                            </h4>

                            <small class="text-muted">Modal Aktif / 当前投资金额</small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">

            Aktivitas Terbaru / 最新活动

        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="60">No</th>

                        <th>Tanggal / 日期</th>

                        <th>Jenis / 类型</th>

                        <th>Keterangan / 说明</th>

                        <th class="text-end">Nominal / 金额</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($recentActivities as $index => $activity)

                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td>

                                {{ $activity['date']->format('d-m-Y') }}

                            </td>

                            <td>

                                {{ $activity['type'] }}

                            </td>

                            <td>

                                {{ $activity['description'] }}

                            </td>

                            <td class="text-end">

                                Rp {{ number_format($activity['amount'],0,',','.') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center">

                                Belum ada aktivitas. / 暂无活动。

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
