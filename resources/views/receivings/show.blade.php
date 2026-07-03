@extends('layouts.app')

@section('content')

<h2 class="mb-4">

Receiving

{{ $receiving->shipment->shipment_number }}

</h2>

<div class="card">

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Jenis</th>

<th>Berat Awal</th>

<th>Sisa Stok</th>

</tr>

</thead>

<tbody>

@foreach($receiving->items as $item)

<tr>

<td>

{{ $item->type->name }}

</td>

<td>

{{ $item->received_weight }}

kg

</td>

<td>

{{ $item->remaining_weight }}

kg

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection