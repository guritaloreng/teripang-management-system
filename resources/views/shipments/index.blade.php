@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>🚚 Shipment / 发货</h2>
        <small>Daftar Pengiriman Teripang</small>
    </div>

    <a href="{{ route('shipments.create') }}" class="btn btn-primary">
        ➕ Buat Shipment
    </a>

</div>

@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

<div class="card shadow">

<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">

<thead class="table-dark">

<tr>

<th>No Shipment</th>

<th>Tanggal</th>

<th>Tujuan</th>

<th>Jumlah Nota</th>

<th>Ongkir</th>

<th>Status</th>

<th width="170">Aksi</th>

</tr>

</thead>

<tbody>

@forelse($shipments as $shipment)

<tr>

<td>{{ $shipment->shipment_number }}</td>

<td>{{ $shipment->shipment_date->format('d-m-Y') }}</td>

<td>{{ $shipment->destination }}</td>

<td>{{ $shipment->items->count() }}</td>

<td>

Rp {{ number_format($shipment->shipping_cost,0,',','.') }}

</td>

<td>

<span class="badge bg-primary">

{{ $shipment->status }}

</span>

</td>

<td>

<a
href="{{ route('shipments.show',$shipment) }}"
class="btn btn-info btn-sm">

👁 Detail

</a>

<form
action="{{ route('shipments.destroy',$shipment) }}"
method="POST"
style="display:inline;">

@csrf
@method('DELETE')

<button
onclick="return confirm('Hapus shipment ini?')"
class="btn btn-danger btn-sm">

🗑

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="7" class="text-center">

Belum ada Shipment

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

@if($shipments->hasPages())

<div class="mt-3">

{{ $shipments->links() }}

</div>

@endif

@endsection