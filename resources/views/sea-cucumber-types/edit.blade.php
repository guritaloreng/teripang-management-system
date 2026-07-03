@extends('layouts.app')

@section('content')

<h2>Edit Jenis Teripang</h2>

<form method="POST"
      action="{{ route('sea-cucumber-types.update',$seaCucumberType) }}">

    @csrf
    @method('PUT')

    <div class="mb-3">

        <label>Nama Jenis</label>

        <input
            type="text"
            name="name"
            value="{{ $seaCucumberType->name }}"
            class="form-control"
            required>

    </div>

    <button class="btn btn-primary">

        Update

    </button>

</form>

@endsection