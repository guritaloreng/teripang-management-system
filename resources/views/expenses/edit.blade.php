@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Edit Pengeluaran / 编辑支出</h2>

            <small class="text-muted">

                Perbarui data pengeluaran / 更新支出资料

            </small>

        </div>

        <a href="{{ route('expenses.index') }}"
           class="btn btn-secondary">

            Kembali / 返回

        </a>

    </div>

    <div class="card">

        <div class="card-header bg-warning">

            Form Edit Pengeluaran / 编辑支出表单

        </div>

        <div class="card-body">

            <form
                action="{{ route('expenses.update',$expense) }}"
                method="POST">

                @csrf

                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Tanggal / 日期</label>

                        <input
                            type="date"
                            name="expense_date"
                            class="form-control"
                            value="{{ old('expense_date',$expense->expense_date->format('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Nama Pengeluaran / 支出名称</label>

                        <input
                            type="text"
                            name="expense_name"
                            class="form-control"
                            value="{{ old('expense_name', $expense->expense_name) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Nominal / 金额</label>

                        <input
                            type="number"
                            name="amount"
                            class="form-control"
                            value="{{ old('amount', $expense->amount) }}"
                            min="0"
                            step="0.01"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Keterangan / 说明</label>

                        <input
                            type="text"
                            name="description"
                            class="form-control"
                            value="{{ old('description', $expense->description) }}"
                            required>

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">Catatan / 备注</label>

                        <textarea
                            name="note"
                            rows="3"
                            class="form-control">{{ old('note', $expense->note) }}</textarea>

                    </div>

                </div>

                <div class="d-flex justify-content-end">

                    <a href="{{ route('expenses.index') }}"
                       class="btn btn-secondary me-2">

                        Batal / 取消

                    </a>

                    <button
                        type="submit"
                        class="btn btn-warning">

                        Update Pengeluaran / 更新支出

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
