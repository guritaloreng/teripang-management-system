@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Detail Nota Pembelian / 采购详情</h2>

        <div>

            <a
                href="{{ route('purchases.index') }}"
                class="btn btn-secondary">

                Kembali / 返回

            </a>

            <a
                href="{{ route('purchases.edit', $purchase) }}"
                class="btn btn-warning">

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
                    class="btn btn-danger"
                    onclick="return confirm('Hapus purchase ini? / 删除此采购单？')">

                    Hapus / 删除

                </button>

            </form>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-body">

            <table class="table">

                <tr>

                    <th width="220">Nomor Purchase / 采购编号</th>

                    <td>{{ $purchase->purchase_number }}</td>

                </tr>

                <tr>

                    <th>Tanggal / 日期</th>

                    <td>{{ $purchase->purchase_date->format('d-m-Y') }}</td>

                </tr>

                <tr>

                    <th>Supplier / 供应商</th>

                    <td>{{ $purchase->supplier->name }}</td>

                </tr>

                <tr>

                    <th>Grand Total / 总计</th>

                    <td>Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}</td>

                </tr>

            </table>

        </div>

    </div>

    <div class="card">

        <div class="card-header bg-success text-white">

            Detail Barang / 商品明细

        </div>

        <div class="card-body p-0">

            <table class="table table-bordered mb-0">

                <thead class="table-dark">

                    <tr>

                        <th>No</th>

                        <th>Jenis / 种类</th>

                        <th>Berat / 重量</th>

                        <th>Harga/kg / 单价</th>

                        <th>Subtotal / 小计</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($purchase->items as $item)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->type->name }}</td>

                            <td>{{ number_format($item->purchase_weight, 2) }} Kg</td>

                            <td>Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}</td>

                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
