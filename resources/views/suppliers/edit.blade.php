@extends('layouts.app')

@section('content')

<h1>Edit Supplier / 编辑供应商</h1>

<form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">

    @csrf
    @method('PUT')

    <p>Nama Supplier / 供应商名称</p>
    <input type="text" name="name" value="{{ $supplier->name }}">

    <br><br>

    <p>Daerah / 地区</p>
    <input type="text" name="region" value="{{ $supplier->region }}">

    <br><br>

    <p>Telepon / 电话</p>
    <input type="text" name="phone" value="{{ $supplier->phone }}">

    <br><br>

    <p>Catatan / 备注</p>
    <textarea name="note">{{ $supplier->note }}</textarea>

    <br><br>

    <button type="submit">Update / 更新</button>

</form>

@endsection
