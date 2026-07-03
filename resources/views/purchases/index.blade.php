@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2>📥 Nota Pembelian / 采购单</h2>

<small>Daftar Pembelian</small>

</div>

<div>

<a href="{{ route('ocr.index') }}" class="btn btn-success">

📷 Scan Nota

</a>

<a href="{{ route('purchases.create') }}" class="btn btn-primary">

➕ Input Manual

</a>

</div>

</div>

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

<div class="card shadow">

<div class="card-body p-0">

<table class="table table-hover table-bordered mb-0">

<thead class="table-dark">

<tr>

<th>No</th>

<th>Tanggal</th>

<th>Supplier</th>

<th>Jumlah Item</th>

<th>Total Berat</th>

<th>Grand Total</th>

<th>Aksi</th>

</tr>

</thead>

<tbody>

@forelse($purchases as $purchase)

<tr>

<td>{{ $purchase->purchase_number }}</td>

<td>{{ $purchase->purchase_date->format('d-m-Y') }}</td>

<td>{{ $purchase->supplier->name }}</td>

<td>{{ $purchase->items->count() }}</td>

<td>{{ number_format($purchase->total_weight,2) }} Kg</td>

<td>

Rp {{ number_format($purchase->grand_total,0,',','.') }}

</td>

<td>

<a
href="{{ route('purchases.show',$purchase) }}"
class="btn btn-info btn-sm">

👁 Detail

</a>

</td>

</tr>

@empty

<tr>

<td colspan="7" class="text-center">

Belum ada data pembelian

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

@endsection