@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2>Investor / 投资人</h2>

        <small>Master Data Investor / 投资人主数据</small>

    </div>

    <a href="{{ route('investors.create') }}" class="btn btn-primary">

        Tambah Investor / 新增投资人

    </a>

</div>

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

<div class="card shadow">

<div class="card-body p-0">

<table class="table table-bordered table-hover mb-0">

<thead class="table-dark">

<tr>

<th width="60">No</th>

<th>Nama Investor / 投资人姓名</th>

<th>No HP / 手机号</th>

<th>Catatan / 备注</th>

<th width="170">Aksi / 操作</th>

</tr>

</thead>

<tbody>

@forelse($investors as $investor)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $investor->name }}</td>

<td>{{ $investor->phone }}</td>

<td>{{ $investor->note }}</td>

<td>

<a
href="{{ route('investors.edit',$investor) }}"
class="btn btn-warning btn-sm">

Edit / 编辑

</a>

<form
action="{{ route('investors.destroy',$investor) }}"
method="POST"
style="display:inline;">

@csrf
@method('DELETE')

<button
onclick="return confirm('Hapus investor? / 删除投资人？')"
class="btn btn-danger btn-sm">

Hapus / 删除

</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="5" class="text-center">

Belum ada data investor. / 暂无投资人数据。

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

@endsection
