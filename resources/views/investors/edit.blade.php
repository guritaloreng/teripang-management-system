@extends('layouts.app')

@section('content')

<h2 class="mb-4">

✏ Edit Investor / 编辑投资人

</h2>

<form
method="POST"
action="{{ route('investors.update',$investor) }}">

@csrf
@method('PUT')

<div class="card shadow">

<div class="card-body">

<div class="mb-3">

<label>Nama Investor</label>

<input
type="text"
name="name"
value="{{ $investor->name }}"
class="form-control"
required>

</div>

<div class="mb-3">

<label>No HP</label>

<input
type="text"
name="phone"
value="{{ $investor->phone }}"
class="form-control">

</div>

<div class="mb-3">

<label>Catatan</label>

<textarea
name="note"
rows="3"
class="form-control">{{ $investor->note }}</textarea>

</div>

<button class="btn btn-primary">

💾 Update

</button>

<a
href="{{ route('investors.index') }}"
class="btn btn-secondary">

Batal

</a>

</div>

</div>

</form>

@endsection