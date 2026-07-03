@extends('layouts.app')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>🚚 Detail Shipment / 发货详情</h2>

<a href="{{ route('shipments.index') }}" class="btn btn-secondary">

← Kembali

</a>

</div>

<div class="card mb-4">

<div class="card-body">

<table class="table">

<tr>

<th width="220">No Shipment</th>

<td>{{ $shipment->shipment_number }}</td>

</tr>

<tr>

<th>Tanggal</th>

<td>{{ $shipment->shipment_date->format('d-m-Y') }}</td>

</tr>

<tr>

<th>Tujuan</th>

<td>{{ $shipment->destination }}</td>

</tr>

<tr>

<th>Status</th>

<td>{{ $shipment->status }}</td>

</tr>

<tr>

<th>Ongkir</th>

<td>

Rp {{ number_format($shipment->shipping_cost,0,',','.') }}

</td>

</tr>

<tr>

<th>Catatan</th>

<td>{{ $shipment->note }}</td>

</tr>

</table>

</div>

</div>

<div class="card">

<div class="card-header bg-success text-white">

Daftar Nota

</div>

<div class="card-body p-0">

<table class="table table-bordered mb-0">

<thead class="table-dark">

<tr>

<th>No Purchase</th>

<th>Supplier</th>

<th>Total</th>

</tr>

</thead>

<tbody>

@foreach($shipment->items as $item)

<tr>

<td>{{ $item->purchase->purchase_number }}</td>

<td>{{ $item->purchase->supplier->name }}</td>

<td>

Rp {{ number_format($item->purchase->grand_total,0,',','.') }}

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</div>

@endsection