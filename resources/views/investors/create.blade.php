@extends('layouts.app')

@section('content')

<h2 class="mb-4">

Tambah Investor / 新增投资人

</h2>

<form method="POST" action="{{ route('investors.store') }}">

@csrf

<div class="card shadow">

<div class="card-body">

<div class="mb-3">

<label>Nama Investor / 投资人姓名</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>

<div class="mb-3">

<label>No HP / 手机号</label>

<input
type="text"
name="phone"
class="form-control">

</div>

<div class="mb-3">

<label>Catatan / 备注</label>

<textarea
name="note"
rows="3"
class="form-control"></textarea>

</div>

<button class="btn btn-success">

Simpan / 保存

</button>

<a
href="{{ route('investors.index') }}"
class="btn btn-secondary">

Batal / 取消

</a>

</div>

</div>

</form>

@endsection
