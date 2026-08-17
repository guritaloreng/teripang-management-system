@extends('layouts.app')

@section('content')

<h2>Edit Jenis Teripang / 编辑海参种类</h2>

<form method="POST"
      action="{{ route('sea-cucumber-types.update',$seaCucumberType) }}">

    @csrf
    @method('PUT')

    <div class="mb-3">

        <label>Nama Jenis / 种类名称</label>

        <input
            type="text"
            name="name"
            value="{{ $seaCucumberType->name }}"
            class="form-control"
            required>

    </div>

    <button class="btn btn-primary">

        Update / 更新

    </button>

</form>

@endsection
