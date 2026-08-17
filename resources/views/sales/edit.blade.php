@extends('layouts.app')

@section('content')

@php
    $rows = old('type_id')
        ? collect(old('type_id'))->map(function ($typeId, $index) {
            return [
                'type_id' => $typeId,
                'weight' => old('weight.' . $index),
                'price' => old('price.' . $index),
                'status' => old('status.' . $index, 'Terjual Sebagian'),
            ];
        })
        : $sale->items->map(function ($item) {
            return [
                'type_id' => $item->sea_cucumber_type_id,
                'weight' => $item->weight,
                'price' => $item->price,
                'status' => $item->status,
            ];
        });

    if ($rows->isEmpty()) {
        $rows = collect([
            [
                'type_id' => '',
                'weight' => '',
                'price' => '',
                'status' => 'Terjual Sebagian',
            ],
        ]);
    }
@endphp

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Edit Nota Penjualan / 编辑销售单</h2>

            <small class="text-muted">
                Invoice / 发票 :
                <strong>{{ $sale->invoice_number }}</strong>
            </small>

        </div>

        <a href="{{ route('sales.show',$sale) }}"
           class="btn btn-secondary">
            Kembali / 返回
        </a>

    </div>

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('sales.update',$sale) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-success text-white">
                Informasi Nota Penjualan / 销售单信息
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">Nomor Invoice / 发票号</label>

                        <input
                            type="text"
                            name="invoice_number"
                            class="form-control"
                            value="{{ old('invoice_number',$sale->invoice_number) }}"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">Tanggal Penjualan / 销售日期</label>

                        <input
                            type="date"
                            name="sale_date"
                            class="form-control"
                            value="{{ old('sale_date',$sale->sale_date->format('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">Nama Pembeli / 买家姓名</label>

                        <input
                            type="text"
                            name="buyer"
                            class="form-control"
                            value="{{ old('buyer',$sale->buyer) }}"
                            required>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-12">

                        <label class="form-label">Catatan / 备注</label>

                        <textarea
                            name="note"
                            rows="3"
                            class="form-control">{{ old('note',$sale->note) }}</textarea>

                    </div>

                </div>

            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <strong>Detail Penjualan / 销售明细</strong>

                <button
                    type="button"
                    class="btn btn-light btn-sm"
                    id="addRow">
                    Tambah Baris / 新增行
                </button>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0"
                       id="saleTable">

                    <thead class="table-dark">

                        <tr>

                            <th width="30%">Jenis Teripang / 海参种类</th>

                            <th width="16%">Berat Dijual / 销售重量</th>

                            <th width="18%">Harga/Kg / 单价</th>

                            <th width="18%">Subtotal / 小计</th>

                            <th width="14%">Status / 状态</th>

                            <th width="6%">Aksi / 操作</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($rows as $row)

                            <tr>

                                <td>

                                    <select
                                        name="type_id[]"
                                        class="form-select"
                                        required>

                                        <option value="">Pilih / 选择</option>

                                        @foreach($types as $type)

                                            <option
                                                value="{{ $type->id }}"
                                                @selected((string) $row['type_id'] === (string) $type->id)>
                                                {{ $type->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="weight[]"
                                        class="form-control weight"
                                        value="{{ $row['weight'] }}"
                                        required>

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="price[]"
                                        class="form-control price"
                                        value="{{ $row['price'] }}"
                                        required>

                                </td>

                                <td>

                                    <input
                                        type="text"
                                        class="form-control subtotal"
                                        readonly>

                                </td>

                                <td>

                                    <select
                                        name="status[]"
                                        class="form-select">

                                        <option
                                            value="Terjual Sebagian"
                                            @selected($row['status'] === 'Terjual Sebagian')>
                                            Terjual Sebagian / 部分销售
                                        </option>

                                        <option
                                            value="Selesai"
                                            @selected($row['status'] === 'Selesai')>
                                            Selesai / 已完成
                                        </option>

                                    </select>

                                </td>

                                <td class="text-center">

                                    <button
                                        type="button"
                                        class="btn btn-danger removeRow">
                                        Hapus / 删除
                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="card-footer text-end">

                <h4>
                    Grand Total / 合计 :
                    <span id="grandTotal">Rp 0</span>
                </h4>

                <button
                    type="submit"
                    class="btn btn-success btn-lg">
                    Update Nota Penjualan / 更新销售单
                </button>

            </div>

        </div>

    </form>

</div>

<script>
function bindEvents(){
    document.querySelectorAll(".weight,.price").forEach(function(input){
        input.oninput = calculate;
    });

    document.querySelectorAll(".removeRow").forEach(function(button){
        button.onclick = function(){
            let tbody = document.querySelector("#saleTable tbody");

            if (tbody.rows.length === 1) {
                alert("Minimal harus ada satu item.");
                return;
            }

            this.closest("tr").remove();
            calculate();
        };
    });
}

document.getElementById("addRow").onclick = function(){
    let tbody = document.querySelector("#saleTable tbody");
    let row = tbody.rows[0].cloneNode(true);

    row.querySelectorAll("input").forEach(function(input){
        input.value = "";
    });

    row.querySelector("select[name='type_id[]']").value = "";
    row.querySelector("select[name='status[]']").value = "Terjual Sebagian";

    tbody.appendChild(row);

    bindEvents();
    calculate();
};

function calculate(){
    let grand = 0;

    document.querySelectorAll("#saleTable tbody tr").forEach(function(row){
        let weight = parseFloat(row.querySelector(".weight").value) || 0;
        let price = parseFloat(row.querySelector(".price").value) || 0;
        let subtotal = weight * price;

        row.querySelector(".subtotal").value =
            "Rp " + subtotal.toLocaleString("id-ID");

        grand += subtotal;
    });

    document.getElementById("grandTotal").innerText =
        "Rp " + grand.toLocaleString("id-ID");
}

bindEvents();
calculate();
</script>

@endsection
