@extends('layouts.app')

@section('content')

<h1>Tambah Supplier / 新增供应商</h1>

<form action="{{ route('suppliers.store') }}" method="POST">

    @csrf

    <p>Nama Supplier / 供应商名称</p>
    <input type="text" name="name">

    <br><br>

    <p>Daerah / 地区</p>
    <input type="text" name="region">

    <br><br>

    <p>Telepon / 电话</p>
    <input type="text" name="phone">

    <br><br>

    <p>Catatan / 备注</p>
    <textarea name="note"></textarea>

    <br><br>

    <button type="submit">Simpan / 保存</button>

</form>

@endsection
