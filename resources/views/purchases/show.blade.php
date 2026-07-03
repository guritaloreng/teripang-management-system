@extends('layouts.app')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between mb-4">

<h2>

📥 Detail Nota Pembelian / 采购详情

</h2>

<a
href="{{ route('purchases.index') }}"
class="btn btn-secondary">

← Kembali

</a>

</div>

<div class="card mb-4">

<div class="card-body">

<table class="table">

<tr>

<th width="220">

Nomor Purchase

</th>

<td>

{{ $purchase->purchase_number }}

</td>

</tr>

<tr>

<th>

Tanggal

</th>

<td>

{{ $purchase->purchase_date->format('d-m-Y') }}

</td>

</tr>

<tr>

<th>

Supplier

</th>

<td>

{{ $purchase->supplier->name }}

</td>

</tr>

<tr>

<th>

Grand Total

</th>

<td>

Rp {{ number_format($purchase->grand_total,0,',','.') }}

</td>

</tr>

</table>

</div>

</div>

<div class="card">

<div class="card-header bg-success text-white">

📦 Detail Barang

</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<thead class="table-dark">

<tr>

<th>No</th>

<th>Jenis</th>

<th>Berat</th>

<th>Harga/kg</th>

<th>Subtotal</th>

</tr>

</thead>

<tbody>

@foreach($purchase->items as $item)

<tr>

<td>

{{ $loop->iteration }}

</td>

<td>

{{ $item->type->name }}

</td>

<td>

{{ number_format($item->purchase_weight,2) }}

Kg

</td>

<td>

Rp {{ number_format($item->price_per_kg,0,',','.') }}

</td>

<td>

Rp {{ number_format($item->subtotal,0,',','.') }}

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</div>

@endsection