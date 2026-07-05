@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>📤 Nota Penjualan</h2>

            <small class="text-muted">

                Shipment :
                <strong>{{ $shipment->shipment_number }}</strong>

            </small>

        </div>

        <a href="{{ route('shipments.show',$shipment) }}"
           class="btn btn-secondary">

            ← Kembali

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

    <form action="{{ route('sales.store',$shipment) }}"
          method="POST">

        @csrf

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-success text-white">

                Informasi Nota Penjualan

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">

                            Nomor Invoice

                        </label>

                        <input
                            type="text"
                            name="invoice_number"
                            class="form-control"
                            value="{{ old('invoice_number') }}"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">

                            Tanggal Penjualan

                        </label>

                        <input
                            type="date"
                            name="sale_date"
                            class="form-control"
                            value="{{ old('sale_date',date('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">

                            Nama Pembeli

                        </label>

                        <input
                            type="text"
                            name="buyer"
                            class="form-control"
                            value="{{ old('buyer') }}"
                            required>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-12">

                        <label class="form-label">

                            Catatan

                        </label>

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

                <strong>Detail Penjualan</strong>

                <button
                    type="button"
                    class="btn btn-light btn-sm"
                    id="addRow">

                    ➕ Tambah Baris

                </button>

            </div>

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover mb-0"
                    id="saleTable">

                    <thead class="table-dark">

                        <tr>

                            <th width="22%">
                                Jenis Teripang
                            </th>

                            <th width="14%">
                                Berat Shipment
                            </th>

                            <th width="14%">
                                Berat Dijual
                            </th>

                            <th width="15%">
                                Harga/Kg
                            </th>

                            <th width="15%">
                                Subtotal
                            </th>

                            <th width="14%">
                                Status
                            </th>

                            <th width="6%">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>

                                <select
                                    name="type_id[]"
                                    class="form-select typeSelect"
                                    required>

                                    <option value="">

                                        Pilih Jenis

                                    </option>

                                    @foreach($types as $type)

                                        <option
                                            value="{{ $type['id'] }}"
                                            data-weight="{{ $type['weight'] }}"
                                            data-status="{{ $type['status'] }}">

                                            {{ $type['name'] }}

                                        </option>

                                    @endforeach

                                </select>

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control shipmentWeight"
                                    readonly>

                            </td>

                            <td>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="weight[]"
                                    class="form-control weight"
                                    required>

                            </td>

                            <td>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="price[]"
                                    class="form-control price"
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

                                    <option value="Terjual Sebagian">

                                        Terjual Sebagian

                                    </option>

                                    <option value="Selesai">

                                        Selesai

                                    </option>

                                </select>

                            </td>

                            <td class="text-center">

                                <button
                                    type="button"
                                    class="btn btn-danger removeRow">

                                    🗑

                                </button>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="card-footer text-end">

                <h4>

                    Grand Total :

                    <span id="grandTotal">

                        Rp 0

                    </span>

                </h4>

                <button
                    type="submit"
                    class="btn btn-success btn-lg">

                    💾 Simpan Nota Penjualan

                </button>

            </div>

        </div>
                </form>

</div>

<script>

function bindEvents(){

    document.querySelectorAll(".weight,.price").forEach(function(input){

        input.oninput=calculate;

    });

    document.querySelectorAll(".typeSelect").forEach(function(select){

        select.onchange=function(){

            let option=this.options[this.selectedIndex];

            let row=this.closest("tr");

            row.querySelector(".shipmentWeight").value=
                option.dataset.weight
                ? option.dataset.weight+" Kg"
                : "";

        };

    });

    document.querySelectorAll(".removeRow").forEach(function(button){

        button.onclick=function(){

            let tbody=document.querySelector("#saleTable tbody");

            if(tbody.rows.length==1){

                alert("Minimal harus ada satu item.");

                return;

            }

            this.closest("tr").remove();

            calculate();

        };

    });

}

document.getElementById("addRow").onclick=function(){

    let tbody=document.querySelector("#saleTable tbody");

    let row=tbody.rows[0].cloneNode(true);

    row.querySelectorAll("input").forEach(function(input){

        input.value="";

    });

    row.querySelector(".shipmentWeight").value="";

    row.querySelector(".subtotal").value="";

    row.querySelector(".typeSelect").selectedIndex=0;

    row.querySelector("select[name='status[]']").value="Terjual Sebagian";

    tbody.appendChild(row);

    bindEvents();

};

function calculate(){

    let grand=0;

    document.querySelectorAll("#saleTable tbody tr").forEach(function(row){

        let weight=parseFloat(

            row.querySelector(".weight").value

        )||0;

        let price=parseFloat(

            row.querySelector(".price").value

        )||0;

        let subtotal=weight*price;

        row.querySelector(".subtotal").value=

            subtotal.toLocaleString(

                'id-ID',

                {

                    minimumFractionDigits:0,

                    maximumFractionDigits:2

                }

            );

        grand+=subtotal;

    });

    document.getElementById("grandTotal").innerHTML=

        "Rp "+grand.toLocaleString(

            'id-ID',

            {

                minimumFractionDigits:0,

                maximumFractionDigits:2

            }

        );

}

bindEvents();

calculate();

</script>

@endsection