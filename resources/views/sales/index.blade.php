@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Nota Penjualan / 销售单</h2>

            <small class="text-muted">
                Daftar seluruh invoice penjualan / 所有销售单列表
            </small>

        </div>

        <a href="{{ route('sales.create') }}" class="btn btn-primary">
            Tambah Penjualan / 新增销售
        </a>

    </div>

    <div class="card">

        <div class="card-header bg-success text-white">
            Daftar Invoice / 发票列表
        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th width="60">No</th>
                        <th>Invoice / 发票</th>
                        <th>Tanggal / 日期</th>
                        <th>Buyer / 买家</th>
                        <th class="text-end">Total / 合计</th>
                        <th width="220">Aksi / 操作</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($sales as $index => $sale)

                        <tr>

                            <td>{{ $sales->firstItem() + $index }}</td>

                            <td>
                                <strong>{{ $sale->invoice_number }}</strong>
                            </td>

                            <td>{{ $sale->sale_date->format('d-m-Y') }}</td>

                            <td>{{ $sale->buyer }}</td>

                            <td class="text-end">
                                Rp {{ number_format($sale->items->sum('subtotal'), 0, ',', '.') }}
                            </td>

                            <td>

                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-info btn-sm">
                                    Detail / 详情
                                </a>

                                <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning btn-sm">
                                    Edit / 编辑
                                </a>

                                <form
                                    action="{{ route('sales.destroy', $sale) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus invoice ini? / 删除此发票？')">
                                        Hapus / 删除
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center">
                                Belum ada invoice penjualan. / 暂无销售发票。
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($sales->hasPages())

            <div class="card-footer">
                {{ $sales->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
