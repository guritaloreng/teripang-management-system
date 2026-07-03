@extends('layouts.app')

@section('content')
@if(session('success'))

<div style="background:#d4edda;padding:15px;margin-bottom:20px;border-radius:5px;">

{{ session('success') }}

</div>

@endif
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">

<div>

<h1>👨 Supplier / 供应商</h1>

<p>Master Supplier Teripang</p>

</div>

<div>

<a href="{{ route('suppliers.create') }}"
style="background:#2c7be5;color:white;padding:12px 20px;text-decoration:none;border-radius:6px;">

➕ Tambah Supplier

</a>

</div>

</div>

<table width="100%" border="1" cellspacing="0" cellpadding="10">

<thead>

<tr style="background:#34495e;color:white;">

<th width="5%">No</th>

<th>Nama Supplier</th>

<th>Daerah</th>

<th>Telepon</th>

<th>Catatan</th>

<th width="15%">Aksi</th>

</tr>

</thead>

<tbody>

@forelse($suppliers as $supplier)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $supplier->name }}</td>

<td>{{ $supplier->region }}</td>

<td>{{ $supplier->phone }}</td>

<td>{{ $supplier->note }}</td>

<td style="white-space: nowrap;">

    <a href="{{ route('suppliers.edit', $supplier->id) }}">✏️</a>

    <form action="{{ route('suppliers.destroy', $supplier->id) }}"
          method="POST"
          style="display:inline; margin:0;">

        @csrf
        @method('DELETE')

        <button type="submit"
                onclick="return confirm('Yakin hapus supplier ini?')"
                style="border:none;background:none;cursor:pointer;padding:0;">

            🗑️

        </button>

    </form>

</td>

</tr>

@empty

<tr>

<td colspan="6" align="center">

Belum ada supplier

</td>

</tr>

@endforelse

</tbody>

</table>

@endsection