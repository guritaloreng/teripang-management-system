@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Stock Overview / 库存概览</h2>

            <small class="text-muted">
                Informasi pembelian per jenis teripang. Tidak digunakan untuk validasi penjualan.
            </small>

        </div>

        <div class="btn-group">

            <a
                href="{{ route('stock-overview.index', ['filter' => 'active']) }}"
                class="btn btn-sm {{ $filter === 'active' ? 'btn-primary' : 'btn-outline-primary' }}">
                Active
            </a>

            <a
                href="{{ route('stock-overview.index', ['filter' => 'sold']) }}"
                class="btn btn-sm {{ $filter === 'sold' ? 'btn-primary' : 'btn-outline-primary' }}">
                Sold
            </a>

            <a
                href="{{ route('stock-overview.index', ['filter' => 'all']) }}"
                class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                All
            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    <div class="card">

        <div class="card-header bg-primary text-white">
            Purchase Item Summary / 采购明细汇总
        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th width="60">No</th>
                        <th>Sea Cucumber Type / 海参种类</th>
                        <th class="text-end">Total Purchased Weight (Kg) / 采购总重量</th>
                        <th>Last Purchase Date / 最后采购日期</th>
                        <th>Status / 状态</th>
                        <th width="180">Action / 操作</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($items as $index => $item)

                        <tr>

                            <td>{{ $items->firstItem() + $index }}</td>

                            <td>
                                <strong>{{ $item->name }}</strong>
                            </td>

                            <td class="text-end">
                                {{ number_format((float) $item->total_purchased_weight, 2, ',', '.') }}
                            </td>

                            <td>
                                {{ \Illuminate\Support\Carbon::parse($item->last_purchase_date)->format('d-m-Y') }}
                            </td>

                            <td>
                                @if($item->stock_overview_status === 'Sold')
                                    <span class="badge bg-secondary">Sold</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>

                            <td>
                                <form
                                    action="{{ route('stock-overview.update-status', ['seaCucumberType' => $item->id, 'filter' => $filter]) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('PATCH')

                                    @if($item->stock_overview_status === 'Sold')

                                        <input type="hidden" name="status" value="Active">

                                        <button class="btn btn-success btn-sm">
                                            Mark as Active
                                        </button>

                                    @else

                                        <input type="hidden" name="status" value="Sold">

                                        <button class="btn btn-warning btn-sm">
                                            Mark as Sold
                                        </button>

                                    @endif

                                </form>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center">
                                Tidak ada data purchase item untuk filter ini.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($items->hasPages())

            <div class="card-footer">
                {{ $items->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
