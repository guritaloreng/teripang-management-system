@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Pengeluaran / 支出</h2>

            <small class="text-muted">

                Daftar seluruh pengeluaran operasional perusahaan / 公司运营支出列表

            </small>

        </div>

        <a href="{{ route('expenses.create') }}"
           class="btn btn-primary">

            Tambah Pengeluaran / 新增支出

        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card">

        <div class="card-header bg-primary text-white">

            Daftar Pengeluaran / 支出列表

        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                <tr>

                    <th width="120">Tanggal / 日期</th>

                    <th>Nama / 名称</th>

                    <th class="text-end">Nominal / 金额</th>

                    <th>Keterangan / 说明</th>

                    <th width="150">Aksi / 操作</th>

                </tr>

                </thead>

                <tbody>
                @forelse($expenses as $expense)

                    <tr>

                        <td>

                            {{ $expense->expense_date->format('d-m-Y') }}

                        </td>

                        <td>

                            {{ $expense->expense_name }}

                        </td>

                        <td class="text-end">

                            Rp {{ number_format($expense->amount,0,',','.') }}

                        </td>

                        <td>

                            {{ $expense->description }}

                        </td>

                        <td>

                            <a href="{{ route('expenses.edit',$expense) }}"
                               class="btn btn-warning btn-sm">

                                Edit / 编辑

                            </a>

                            <form
                                action="{{ route('expenses.destroy',$expense) }}"
                                method="POST"
                                class="d-inline">

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus pengeluaran ini? / 删除此支出？')">

                                    Hapus / 删除

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="text-center">

                            Belum ada data pengeluaran. / 暂无支出数据。

                        </td>

                    </tr>

                @endforelse
                </tbody>

            </table>

        </div>

        <div class="card-footer">

            {{ $expenses->links() }}

        </div>

    </div>

</div>

@endsection
