@extends('layouts.app')

@section('content')

@php
    $ocrData = $ocrData ?? [];

    $rows = old('type_id')
        ? collect(old('type_id'))->map(function ($typeId, $index) {
            return [
                'type_id' => $typeId,
                'sea_cucumber_type' => '',
                'purchase_weight' => old('purchase_weight.' . $index),
                'price_per_kg' => old('price_per_kg.' . $index),
            ];
        })->values()->toArray()
        : ($ocrData['items'] ?? []);

    if (count($rows) === 0) {
        $rows = [[
            'type_id' => '',
            'sea_cucumber_type' => '',
            'purchase_weight' => '',
            'price_per_kg' => '',
        ]];
    }
@endphp

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Nota Pembelian / 采购单</h2>

            <small>Input Pembelian Teripang / 录入海参采购</small>

        </div>

        <div>

            <a href="{{ route('ocr.index') }}" class="btn btn-success">

                Upload Nota / 上传单据

            </a>

            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">

                Kembali / 返回

            </a>

        </div>

    </div>

    @if($errors->any())

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

    <form method="POST" action="{{ route('purchases.store') }}">

        @csrf

        <div class="card mb-4">

            <div class="card-header bg-primary text-white">

                Informasi Nota / 单据信息

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label>Tanggal / 日期</label>

                        <input
                            type="date"
                            name="purchase_date"
                            class="form-control"
                            value="{{ old('purchase_date', $ocrData['purchase_date'] ?? '') }}"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label>No Invoice / 发票号</label>

                        <input
                            type="text"
                            name="supplier_invoice"
                            class="form-control"
                            value="{{ old('supplier_invoice', $ocrData['supplier_invoice'] ?? '') }}">

                    </div>

                    <div class="col-md-4">

                        <label>Supplier / 供应商</label>

                        <select
                            name="supplier_id"
                            class="form-select"
                            required>

                            <option value="">Pilih Supplier / 选择供应商</option>

                            @foreach($suppliers as $supplier)

                                <option
                                    value="{{ $supplier->id }}"
                                    @selected((string) old('supplier_id', $ocrData['supplier_id'] ?? '') === (string) $supplier->id)>

                                    {{ $supplier->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-12 mt-3">

                        <label>Catatan / 备注</label>

                        <textarea
                            name="note"
                            rows="2"
                            class="form-control">{{ old('note') }}</textarea>

                    </div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-header bg-success text-white d-flex justify-content-between">

                <span>Detail Pembelian / 采购明细</span>

                <button
                    type="button"
                    id="addRow"
                    class="btn btn-light btn-sm">

                    Tambah Baris / 新增行

                </button>

            </div>

            <div class="card-body">

                <table class="table table-bordered" id="purchaseTable">

                    <thead class="table-dark">

                        <tr>

                            <th width="300">Jenis Teripang / 海参种类</th>

                            <th>Berat Beli (Kg) / 采购重量</th>

                            <th>Harga / Kg / 单价</th>

                            <th>Subtotal / 小计</th>

                            <th width="70">Aksi / 操作</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($rows as $row)

                            <tr>

                                <td>

                                    <select
                                        name="type_id[]"
                                        class="form-select type searchable-type"
                                        required>

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
                                        name="purchase_weight[]"
                                        class="form-control weight"
                                        value="{{ $row['purchase_weight'] ?? '' }}"
                                        required>

                                </td>

                                <td>

                                    <input
                                        type="number"
                                        step="0.01"
                                        name="price_per_kg[]"
                                        class="form-control price"
                                        value="{{ $row['price_per_kg'] ?? '' }}"
                                        required>

                                </td>

                                <td>

                                    <input
                                        type="text"
                                        class="form-control subtotal"
                                        readonly>

                                </td>

                                <td>

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

        </div>

        <div class="text-end mt-4">

            <h3>

                Grand Total / 总计 :

                <span id="grandTotal">Rp 0</span>

            </h3>

            <button type="submit" class="btn btn-primary btn-lg">

                Simpan Pembelian / 保存采购

            </button>

        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>

const tbody = document.querySelector("#purchaseTable tbody");

function initSearchableTypes(scope = document) {

    scope.querySelectorAll(".searchable-type").forEach(select => {

        if (select.tomselect) {

            return;

        }

        new TomSelect(select, {
            create: false,
            maxItems: 1,
            allowEmptyOption: true,
            searchField: ["text"],
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

    });

}

document.getElementById("addRow").onclick = function () {

    let row = tbody.rows[0].cloneNode(true);

    row.querySelectorAll(".ts-wrapper").forEach(wrapper => wrapper.remove());

    row.querySelectorAll("input").forEach(i => i.value = "");

    const select = row.querySelector("select");

    select.tomselect = null;

    select.classList.remove("tomselected", "ts-hidden-accessible");

    select.removeAttribute("tabindex");

    select.selectedIndex = 0;

    tbody.appendChild(row);

    initSearchableTypes(row);

    bindEvents();

};

function bindEvents() {

    document.querySelectorAll(".removeRow").forEach(btn => {

        btn.onclick = function () {

            if (tbody.rows.length > 1) {

                const row = this.closest("tr");

                const select = row.querySelector("select");

                if (select.tomselect) {

                    select.tomselect.destroy();

                }

                row.remove();

                calculate();

            }

        };

    });

    document.querySelectorAll(".weight,.price").forEach(input => {

        input.oninput = calculate;

    });

}

initSearchableTypes();

bindEvents();

calculate();

function calculate() {

    let grand = 0;

    tbody.querySelectorAll("tr").forEach(row => {

        let w = parseFloat(row.querySelector(".weight").value) || 0;

        let p = parseFloat(row.querySelector(".price").value) || 0;

        let s = w * p;

        row.querySelector(".subtotal").value = s.toLocaleString('id-ID');

        grand += s;

    });

    document.getElementById("grandTotal").innerHTML =
        "Rp " + grand.toLocaleString('id-ID');

}

</script>

@endsection
