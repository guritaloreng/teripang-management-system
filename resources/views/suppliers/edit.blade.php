@extends('layouts.app')

@section('content')

<h1>Edit Supplier</h1>

<form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">

    @csrf
    @method('PUT')

    <p>Nama Supplier</p>
    <input type="text" name="name" value="{{ $supplier->name }}">

    <br><br>

    <p>Daerah</p>
    <input type="text" name="region" value="{{ $supplier->region }}">

    <br><br>

    <p>Telepon</p>
    <input type="text" name="phone" value="{{ $supplier->phone }}">

    <br><br>

    <p>Catatan</p>
    <textarea name="note">{{ $supplier->note }}</textarea>

    <br><br>

    <button type="submit">Update</button>

</form>

@endsection