@extends('layouts.app')

@section('content')

<h2 class="mb-4">

📦 Receiving Baru

</h2>

<form action="{{ route('receivings.store') }}" method="POST">

@csrf

<div class="card mb-4">

<div class="card-body">

<div class="row">

<div class="col-md-4">

<label>

Shipment

</label>

<select
name="shipment_id"
class="form-control"
required>

<option value="">

Pilih Shipment

</option>

@foreach($shipments as $s)

<option value="{{ $s->id }}">

{{ $s->shipment_number }}

</option>

@endforeach

</select>

</div>

<div class="col-md-4">

<label>

Tanggal Receiving

</label>

<input
type="date"
name="received_date"
class="form-control"
value="{{ date('Y-m-d') }}">

</div>

</div>

</div>

</div>

<div class="card">

<div class="card-header">

Berat Akhir Setelah Kering

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Jenis</th>

<th>Berat (kg)</th>

</tr>

</thead>

<tbody>

@foreach($types as $t)

<tr>

<td>

{{ $t->name }}

<input
type="hidden"
name="type_id[]"
value="{{ $t->id }}">

</td>

<td>

<input
type="number"
step="0.01"
name="weight[]"
class="form-control">

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

<div class="mt-3">

<button class="btn btn-success">

💾 Simpan Receiving

</button>

</div>

</form>

@endsection