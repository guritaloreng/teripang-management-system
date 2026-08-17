@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Tambah Pengeluaran / 新增支出</h2>

            <small class="text-muted">

                Input pengeluaran operasional perusahaan / 录入公司运营支出

            </small>

        </div>

        <a href="{{ route('expenses.index') }}"
           class="btn btn-secondary">

            Kembali / 返回

        </a>

    </div>

    <div class="card">

        <div class="card-header bg-primary text-white">

            Form Pengeluaran / 支出表单

        </div>

        <div class="card-body">

            <form
                action="{{ route('expenses.store') }}"
                method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Tanggal / 日期</label>

                        <input
                            type="date"
                            name="expense_date"
                            class="form-control"
                            value="{{ old('expense_date', date('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Nama Pengeluaran / 支出名称</label>

                        <input
                            type="text"
                            name="expense_name"
                            class="form-control"
                            value="{{ old('expense_name') }}"
                            placeholder="Contoh : Solar"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Nominal / 金额</label>

                        <input
                            type="number"
                            name="amount"
                            class="form-control"
                            value="{{ old('amount') }}"
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
                            value="{{ old('description') }}"
                            placeholder="Contoh : Solar mobil pickup"
                            required>

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">Catatan / 备注</label>

                        <textarea
                            name="note"
                            rows="3"
                            class="form-control">{{ old('note') }}</textarea>

                    </div>

                </div>

                <div class="d-flex justify-content-end">

                    <a href="{{ route('expenses.index') }}"
                       class="btn btn-secondary me-2">

                        Batal / 取消

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Simpan Pengeluaran / 保存支出

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
