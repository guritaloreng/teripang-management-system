@extends('layouts.app')

@section('content')

<h2>Tambah Jenis Teripang</h2>

<form method="POST"
      action="{{ route('sea-cucumber-types.store') }}">

    @csrf

    <div class="mb-3">

        <label>Nama Jenis</label>

        <input
            type="text"
            name="name"
            class="form-control"
            required>

    </div>

    <button class="btn btn-success">

        Simpan

    </button>

</form>

@endsection