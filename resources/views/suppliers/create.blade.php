@extends('layouts.app')

@section('content')

<h1>Tambah Supplier / 新增供应商</h1>

<form action="{{ route('suppliers.store') }}" method="POST">

    @csrf

    <p>Nama Supplier</p>
    <input type="text" name="name">

    <br><br>

    <p>Daerah</p>
    <input type="text" name="region">

    <br><br>

    <p>Telepon</p>
    <input type="text" name="phone">

    <br><br>

    <p>Catatan</p>
    <textarea name="note"></textarea>

    <br><br>

    <button type="submit">Simpan</button>

</form>

@endsection