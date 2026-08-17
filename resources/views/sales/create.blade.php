@extends('layouts.app')

@section('content')

@php
    $ocrData = $ocrData ?? [];

    $rows = old('type_id')
        ? collect(old('type_id'))->map(function ($typeId, $index) {
            return [
                'type_id' => $typeId,
                'sea_cucumber_type' => '',
                'weight' => old('weight.' . $index),
                'price' => old('price.' . $index),
                'status' => old('status.' . $index, 'Terjual Sebagian'),
            ];
        })->values()->toArray()
        : ($ocrData['items'] ?? []);

    if (count($rows) === 0) {
        $rows = [[
            'type_id' => '',
            'sea_cucumber_type' => '',
            'weight' => '',
            'price' => '',
            'status' => 'Terjual Sebagian',
        ]];
    }
@endphp

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Nota Penjualan / 销售单</h2>

            <small class="text-muted">
                Input penjualan teripang / 录入海参销售
            </small>

        </div>

        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
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

    @if(! empty($ocrData))

        <div class="alert alert-info">
            Data OCR sudah diisi otomatis. Silakan periksa dan edit sebelum menyimpan. /
            OCR数据已自动填写。保存前请检查并编辑。
        </div>

    @endif

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-success text-white">
            Scan Sale Note / 扫描销售单
        </div>

        <div class="card-body">

            <form
                action="{{ route('sales.scan-note') }}"
                method="POST"
                enctype="multipart/form-data"
                class="row g-2 align-items-end">

                @csrf

                <div class="col-md-8">

                    <label class="form-label">Upload Foto Nota Penjualan / 上传销售单照片</label>

                    <input
                        type="file"
                        name="photo"
                        class="form-control"
                        accept="image/*"
                        required>

                </div>

                <div class="col-md-4">

                    <button type="submit" class="btn btn-success">
                        Scan Sale Note / 扫描销售单
                    </button>

                </div>

            </form>

        </div>

    </div>

    <form id="saleCreateForm" action="{{ route('sales.store') }}" method="POST">

        @csrf

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
                            value="{{ old('invoice_number', $ocrData['invoice_number'] ?? '') }}"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">Tanggal Penjualan / 销售日期</label>

                        <input
                            type="date"
                            name="sale_date"
                            class="form-control"
                            value="{{ old('sale_date', $ocrData['sale_date'] ?? date('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">Nama Pembeli / 买家姓名</label>

                        <input
                            type="text"
                            name="buyer"
                            class="form-control"
                            value="{{ old('buyer', $ocrData['buyer'] ?? '') }}"
                            required>

                    </div>

                    <div class="col-md-12 mt-3">

                        <label class="form-label">Catatan / 备注</label>

                        <textarea
                            name="note"
                            rows="3"
                            class="form-control">{{ old('note') }}</textarea>

                    </div>

                </div>

            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <strong>Detail Penjualan / 销售明细</strong>

                <button type="button" class="btn btn-light btn-sm" id="addRow">
                    Tambah Baris / 新增行
                </button>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0" id="saleTable">

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

                                    <select name="type_id[]" class="form-select" required>

                                        <option value="">
                                            {{ $row['sea_cucumber_type'] ?: 'Pilih / 选择' }}
                                        </option>

                                        @foreach($types as $type)

                                            <option
                                                value="{{ $type->id }}"
                                                @selected((string) ($row['type_id'] ?? '') === (string) $type->id)>
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
                                        value="{{ $row['weight'] ?? '' }}"
                                        required>

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="price[]"
                                        class="form-control price"
                                        value="{{ $row['price'] ?? '' }}"
                                        required>

                                </td>

                                <td>

                                    <input type="text" class="form-control subtotal" readonly>

                                </td>

                                <td>

                                    <select name="status[]" class="form-select">

                                        <option
                                            value="Terjual Sebagian"
                                            @selected(($row['status'] ?? '') === 'Terjual Sebagian')>
                                            Terjual Sebagian / 部分销售
                                        </option>

                                        <option
                                            value="Selesai"
                                            @selected(($row['status'] ?? '') === 'Selesai')>
                                            Selesai / 已完成
                                        </option>

                                    </select>

                                </td>

                                <td class="text-center">

                                    <button type="button" class="btn btn-danger removeRow">
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

                <button type="submit" form="saleCreateForm" class="btn btn-success btn-lg">
                    Simpan Nota Penjualan / 保存销售单
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
