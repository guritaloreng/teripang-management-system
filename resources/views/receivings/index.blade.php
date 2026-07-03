@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

📦 Receiving Barang / 收货

</h2>

<a href="{{ route('receivings.create') }}" class="btn btn-primary">

+ Receiving Baru

</a>

</div>

<div class="card">

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>No</th>

<th>Shipment</th>

<th>Tanggal</th>

<th>Status</th>

<th>Aksi</th>

</tr>

</thead>

<tbody>

@forelse($receivings as $r)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $r->shipment->shipment_number }}</td>

<td>{{ $r->received_date->format('d-m-Y') }}</td>

<td>

<span class="badge bg-success">

{{ $r->status }}

</span>

</td>

<td>

<a
href="{{ route('receivings.show',$r) }}"
class="btn btn-sm btn-info">

Detail

</a>

</td>

</tr>

@empty

<tr>

<td colspan="5" class="text-center">

Belum ada Receiving

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

@endsection