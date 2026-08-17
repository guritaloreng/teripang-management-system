@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2>Nota Pembelian / 采购单</h2>

        <small>Daftar Pembelian / 采购列表</small>

    </div>

    <div>

        <a href="{{ route('ocr.index') }}" class="btn btn-success">

            Scan Nota / 扫描单据

        </a>

        <a href="{{ route('purchases.create') }}" class="btn btn-primary">

            Input Manual / 手动录入

        </a>

    </div>

</div>

@if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

@endif

<div class="card shadow">

    <div class="card-body p-0">

        <table class="table table-hover table-bordered mb-0">

            <thead class="table-dark">

                <tr>

                    <th>No</th>

                    <th>Tanggal / 日期</th>

                    <th>Supplier / 供应商</th>

                    <th>Jumlah Item / 项目数量</th>

                    <th>Total Berat / 总重量</th>

                    <th>Grand Total / 总计</th>

                    <th width="220">Aksi / 操作</th>

                </tr>

            </thead>

            <tbody>

                @forelse($purchases as $purchase)

                    <tr>

                        <td>{{ $purchase->purchase_number }}</td>

                        <td>{{ $purchase->purchase_date->format('d-m-Y') }}</td>

                        <td>{{ $purchase->supplier->name }}</td>

                        <td>{{ $purchase->items->count() }}</td>

                        <td>{{ number_format($purchase->total_weight, 2) }} Kg</td>

                        <td>

                            Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}

                        </td>

                        <td>

                            <a
                                href="{{ route('purchases.show', $purchase) }}"
                                class="btn btn-info btn-sm">

                                Detail / 详情

                            </a>

                            <a
                                href="{{ route('purchases.edit', $purchase) }}"
                                class="btn btn-warning btn-sm">

                                Edit / 编辑

                            </a>

                            <form
                                action="{{ route('purchases.destroy', $purchase) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus purchase ini? / 删除此采购单？')">

                                    Hapus / 删除

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            Belum ada data pembelian / 暂无采购数据

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
