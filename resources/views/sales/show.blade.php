@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Detail Nota Penjualan / 销售单详情</h2>

            <small class="text-muted">
                {{ $sale->invoice_number }}
            </small>

        </div>

        <a href="{{ route('sales.index') }}"
           class="btn btn-secondary">
            Kembali / 返回
        </a>

    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="card shadow-sm">

                <div class="card-header bg-success text-white">
                    Informasi Invoice / 发票信息
                </div>

                <div class="card-body">

                    <table class="table table-sm">

                        <tr>
                            <th width="130">Invoice / 发票</th>
                            <td>{{ $sale->invoice_number }}</td>
                        </tr>

                        <tr>
                            <th>Tanggal / 日期</th>
                            <td>{{ $sale->sale_date->format('d-m-Y') }}</td>
                        </tr>

                        <tr>
                            <th>Buyer / 买家</th>
                            <td>{{ $sale->buyer }}</td>
                        </tr>

                        <tr>
                            <th>Catatan / 备注</th>
                            <td>{{ $sale->note ?: '-' }}</td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    Ringkasan / 摘要
                </div>

                <div class="card-body">

                    <h5>Grand Total / 合计</h5>

                    <h3 class="text-success">
                        Rp {{ number_format($grandTotal,0,',','.') }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

    <div class="card mt-4 shadow-sm">

        <div class="card-header bg-success text-white">
            Detail Penjualan / 销售明细
        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>
                        <th width="60">No</th>
                        <th>Jenis Teripang / 海参种类</th>
                        <th class="text-end">Berat (Kg) / 重量</th>
                        <th class="text-end">Harga / Kg / 单价</th>
                        <th class="text-end">Subtotal / 小计</th>
                        <th class="text-center">Status / 状态</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($sale->items as $index => $item)

                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td>{{ $item->type->name }}</td>

                            <td class="text-end">
                                {{ number_format($item->weight,2) }}
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($item->price,0,',','.') }}
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($item->subtotal,0,',','.') }}
                            </td>

                            <td class="text-center">

                                @if($item->status === 'Selesai')

                                    <span class="badge bg-success">
                                        Selesai / 已完成
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Terjual Sebagian / 部分销售
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                class="text-center">
                                Belum ada item penjualan.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="d-flex justify-content-between mt-4">

        <a href="{{ route('sales.index') }}"
           class="btn btn-secondary">
            Daftar Penjualan / 销售列表
        </a>

        <div>

            <a href="{{ route('sales.edit',$sale) }}"
               class="btn btn-warning">
                Edit Invoice / 编辑发票
            </a>

            <form
                action="{{ route('sales.destroy',$sale) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Yakin ingin menghapus invoice ini?');">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger">
                    Hapus Invoice / 删除发票
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
