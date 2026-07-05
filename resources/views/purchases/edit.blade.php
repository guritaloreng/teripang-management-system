@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>✏️ Edit Nota Pembelian</h2>

            <small class="text-muted">

                Perbarui data nota pembelian

            </small>

        </div>

        <a href="{{ route('purchases.show',$purchase) }}"
           class="btn btn-secondary">

            ← Kembali

        </a>

    </div>

    <div class="card">

        <div class="card-header bg-warning">

            Form Edit Pembelian

        </div>

        <div class="card-body">

            <form
                action="{{ route('purchases.update',$purchase) }}"
                method="POST">

                @csrf

                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Tanggal Pembelian

                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            class="form-control"
                            value="{{ old('purchase_date',$purchase->purchase_date->format('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Supplier

                        </label>

                        <select
                            name="supplier_id"
                            class="form-select"
                            required>

                            @foreach($suppliers as $supplier)

                                <option
                                    value="{{ $supplier->id }}"
                                    @selected(old('supplier_id',$purchase->supplier_id)==$supplier->id)>

                                    {{ $supplier->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>
                                        <div class="col-md-6 mb-3">

                        <label class="form-label">

                            No. Invoice Supplier

                        </label>

                        <input
                            type="text"
                            name="supplier_invoice"
                            class="form-control"
                            value="{{ old('supplier_invoice',$purchase->supplier_invoice) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Catatan

                        </label>

                        <input
                            type="text"
                            name="note"
                            class="form-control"
                            value="{{ old('note',$purchase->note) }}">

                    </div>

                    <div class="col-12">

                        <hr>

                        <h5>Item Pembelian</h5>

                    </div>

                    <table class="table table-bordered">

                        <thead class="table-light">

                            <tr>

                                <th>Jenis Teripang</th>

                                <th width="180">Berat (Kg)</th>

                                <th width="180">Harga / Kg</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($purchase->items as $item)

                                <tr>

                                    <td>

                                        <select
                                            name="type_id[]"
                                            class="form-select"
                                            required>

                                            @foreach($types as $type)

                                                <option
                                                    value="{{ $type->id }}"
                                                    @selected($item->sea_cucumber_type_id==$type->id)>

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
                                            value="{{ $item->purchase_weight }}"
                                            required>

                                    </td>

                                    <td>

                                        <input
                                            type="number"
                                            step="0.01"
                                            name="price_per_kg[]"
                                            class="form-control"
                                            value="{{ $item->price_per_kg }}"
                                            required>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>
                                    <div class="d-flex justify-content-end">

                    <a href="{{ route('purchases.show',$purchase) }}"
                       class="btn btn-secondary me-2">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="btn btn-warning">

                        💾 Update Nota Pembelian

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection