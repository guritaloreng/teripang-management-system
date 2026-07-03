@extends('layouts.app')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2>🚚 Buat Shipment / 创建发货</h2>

<small>Pilih Nota Pembelian yang akan dikirim</small>

</div>

<a href="{{ route('shipments.index') }}" class="btn btn-secondary">

← Kembali

</a>

</div>

<form method="POST" action="{{ route('shipments.store') }}">

@csrf

<div class="card mb-4">

<div class="card-header bg-primary text-white">

Informasi Shipment

</div>

<div class="card-body">

<div class="row">

<div class="col-md-3">

<label>Tanggal</label>

<input
type="date"
name="shipment_date"
class="form-control"
value="{{ date('Y-m-d') }}"
required>

</div>

<div class="col-md-3">

<label>Tujuan</label>

<input
type="text"
name="destination"
class="form-control"
value="Makassar"
required>

</div>

<div class="col-md-3">

<label>Ongkir</label>

<input
type="number"
step="0.01"
name="shipping_cost"
class="form-control"
value="0">

</div>

<div class="col-md-3">

<label>Status</label>

<input
type="text"
class="form-control"
value="Dalam Pengiriman"
readonly>

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

<div class="card-header bg-success text-white">

Pilih Nota Pembelian

</div>

<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">

<thead class="table-dark">

<tr>

<th width="60">

Pilih

</th>

<th>No Purchase</th>

<th>Tanggal</th>

<th>Supplier</th>

<th>Total</th>

</tr>

</thead>

<tbody>

@forelse($purchases as $purchase)

<tr>

<td>

<input
type="checkbox"
name="purchase_ids[]"
value="{{ $purchase->id }}">

</td>

<td>

{{ $purchase->purchase_number }}

</td>

<td>

{{ $purchase->purchase_date->format('d-m-Y') }}

</td>

<td>

{{ $purchase->supplier->name }}

</td>

<td>

Rp {{ number_format($purchase->grand_total,0,',','.') }}

</td>

</tr>

@empty

<tr>

<td colspan="5" class="text-center">

Semua nota sudah masuk shipment.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

<div class="mt-4 text-end">

<button class="btn btn-primary btn-lg">

💾 Simpan Shipment

</button>

</div>

</form>

</div>

@endsection