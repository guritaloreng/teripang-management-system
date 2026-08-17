@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Edit Nota Pembelian / 编辑采购单</h2>

            <small class="text-muted">

                Perbarui data nota pembelian / 更新采购单资料

            </small>

        </div>

        <a
            href="{{ route('purchases.show', $purchase) }}"
            class="btn btn-secondary">

            Kembali / 返回

        </a>

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

    <div class="card">

        <div class="card-header bg-warning">

            Form Edit Pembelian / 编辑采购表单

        </div>

        <div class="card-body">

            <form
                action="{{ route('purchases.update', $purchase) }}"
                method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Tanggal Pembelian / 采购日期

                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            class="form-control"
                            value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Supplier / 供应商

                        </label>

                        <select
                            name="supplier_id"
                            class="form-select"
                            required>

                            @foreach($suppliers as $supplier)

                                <option
                                    value="{{ $supplier->id }}"
                                    @selected((string) old('supplier_id', $purchase->supplier_id) === (string) $supplier->id)>

                                    {{ $supplier->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            No. Invoice Supplier / 供应商单据号

                        </label>

                        <input
                            type="text"
                            name="supplier_invoice"
                            class="form-control"
                            value="{{ old('supplier_invoice', $purchase->supplier_invoice) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Catatan / 备注

                        </label>

                        <input
                            type="text"
                            name="note"
                            class="form-control"
                            value="{{ old('note', $purchase->note) }}">

                    </div>

                    <div class="col-12">

                        <hr>

                        <h5>Item Pembelian / 采购项目</h5>

                    </div>

                    <table class="table table-bordered">

                        <thead class="table-light">

                            <tr>

                                <th>Jenis Teripang / 海参种类</th>

                                <th width="180">Berat (Kg) / 重量</th>

                                <th width="180">Harga / Kg / 单价</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($purchase->items as $index => $item)

                                <tr>

                                    <td>

                                        <select
                                            name="type_id[]"
                                            class="form-select searchable-type"
                                            required>

                                            @foreach($types as $type)

                                                <option
                                                    value="{{ $type->id }}"
                                                    @selected((string) old('type_id.' . $index, $item->sea_cucumber_type_id) === (string) $type->id)>

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
                                            class="form-control"
                                            value="{{ old('purchase_weight.' . $index, $item->purchase_weight) }}"
                                            required>

                                    </td>

                                    <td>

                                        <input
                                            type="number"
                                            step="0.01"
                                            name="price_per_kg[]"
                                            class="form-control"
                                            value="{{ old('price_per_kg.' . $index, $item->price_per_kg) }}"
                                            required>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                    <div class="d-flex justify-content-end">

                        <a
                            href="{{ route('purchases.show', $purchase) }}"
                            class="btn btn-secondary me-2">

                            Batal / 取消

                        </a>

                        <button
                            type="submit"
                            class="btn btn-warning">

                            Update Nota Pembelian / 更新采购单

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>

document.querySelectorAll(".searchable-type").forEach(select => {

    new TomSelect(select, {
        create: false,
        maxItems: 1,
        searchField: ["text"],
        sortField: {
            field: "text",
            direction: "asc"
        }
    });

});

</script>

@endsection
