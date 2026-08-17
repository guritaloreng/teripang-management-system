@extends('layouts.app')

@section('content')

<h2>Tambah Jenis Teripang / 新增海参种类</h2>

<form method="POST"
      action="{{ route('sea-cucumber-types.store') }}">

    @csrf

    <div class="mb-3">

        <label>Nama Jenis / 种类名称</label>

        <input
            type="text"
            name="name"
            class="form-control"
            required>

    </div>

    <button class="btn btn-success">

        Simpan / 保存

    </button>

</form>

@endsection
