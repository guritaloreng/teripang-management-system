@extends('layouts.app')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2>📥 Nota Pembelian / 采购单</h2>

<small>Input Pembelian Teripang</small>

</div>

<div>

<a href="{{ route('ocr.index') }}" class="btn btn-success">

📷 Upload Nota

</a>

<a href="{{ route('purchases.index') }}" class="btn btn-secondary">

← Kembali

</a>

</div>

</div>

<form method="POST" action="{{ route('purchases.store') }}">

@csrf

<div class="card mb-4">

<div class="card-header bg-primary text-white">

📋 Informasi Nota

</div>

<div class="card-body">

<div class="row">

<div class="col-md-4">

<label>Tanggal</label>

<input
type="date"
name="purchase_date"
class="form-control"
required>

</div>

<div class="col-md-4">

<label>No Invoice</label>

<input
type="text"
name="supplier_invoice"
class="form-control">

</div>

<div class="col-md-4">

<label>Supplier</label>

<select
name="supplier_id"
class="form-select"
required>

<option value="">Pilih Supplier</option>

@foreach($suppliers as $supplier)

<option value="{{ $supplier->id }}">

{{ $supplier->name }}

</option>

@endforeach

</select>

</div>

<div class="col-md-12 mt-3">

<label>Catatan</label>

<textarea
name="note"
rows="2"
class="form-control"></textarea>

</div>

</div>

</div>

</div>

<div class="card">

<div class="card-header bg-success text-white d-flex justify-content-between">

<span>

📦 Detail Pembelian

</span>

<button
type="button"
id="addRow"
class="btn btn-light btn-sm">

➕ Tambah Baris

</button>

</div>

<div class="card-body">

<table class="table table-bordered" id="purchaseTable">

<thead class="table-dark">

<tr>

<th width="300">

Jenis Teripang

</th>

<th>

Berat Beli (Kg)

</th>

<th>

Harga / Kg

</th>

<th>

Subtotal

</th>

<th width="70">

Aksi

</th>

</tr>

</thead>

<tbody>

<tr>

<td>

<select
name="type_id[]"
class="form-select type">

<option value="">Pilih</option>

@foreach($types as $type)

<option value="{{ $type->id }}">

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
class="form-control weight">

</td>

<td>

<input
type="number"
step="0.01"
name="price_per_kg[]"
class="form-control price">

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

🗑

</button>

</td>

</tr>

</tbody>

</table>

</div>

</div>

<div class="text-end mt-4">

<h3>

Grand Total :

<span id="grandTotal">

Rp 0

</span>

</h3>

<button class="btn btn-primary btn-lg">

💾 Simpan Pembelian

</button>

</div>

</form>

</div>

<script>

const tbody=document.querySelector("#purchaseTable tbody");

document.getElementById("addRow").onclick=function(){

let row=tbody.rows[0].cloneNode(true);

row.querySelectorAll("input").forEach(i=>i.value="");

row.querySelector("select").selectedIndex=0;

tbody.appendChild(row);

bindEvents();

};

function bindEvents(){

document.querySelectorAll(".removeRow").forEach(btn=>{

btn.onclick=function(){

if(tbody.rows.length>1){

this.closest("tr").remove();

calculate();

}

}

});

document.querySelectorAll(".weight,.price").forEach(input=>{

input.oninput=calculate;

});

}

function calculate(){

let grand=0;

tbody.querySelectorAll("tr").forEach(row=>{

let w=parseFloat(row.querySelector(".weight").value)||0;

let p=parseFloat(row.querySelector(".price").value)||0;

let s=w*p;

row.querySelector(".subtotal").value=s.toLocaleString('id-ID');

grand+=s;

});

document.getElementById("grandTotal").innerHTML=

"Rp "+grand.toLocaleString('id-ID');

}

bindEvents();

</script>

@endsection