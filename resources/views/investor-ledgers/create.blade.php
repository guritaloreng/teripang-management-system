@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Tambah Transaksi Investor / 新增投资人交易</h2>

            <small class="text-muted">

                Deposit atau penarikan modal / 存入或提取资金

            </small>

        </div>

        <a href="{{ route('investor-ledgers.index') }}"
           class="btn btn-secondary">

            Kembali / 返回

        </a>

    </div>

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="card shadow-sm">

        <div class="card-body">

            <form method="POST"
                  action="{{ route('investor-ledgers.store') }}">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Investor / 投资人

                        </label>

                        <select
                            name="investor_id"
                            class="form-select"
                            required>

                            <option value="">Pilih Investor / 选择投资人</option>

                            @foreach($investors as $investor)

                                <option
                                    value="{{ $investor->id }}"
                                    @selected(old('investor_id') == $investor->id)>

                                    {{ $investor->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Tanggal / 日期

                        </label>

                        <input
                            type="date"
                            name="transaction_date"
                            class="form-control"
                            value="{{ old('transaction_date', date('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Jenis Transaksi / 交易类型

                        </label>

                        <select
                            name="transaction_type"
                            class="form-select"
                            required>

                            <option value="deposit" @selected(old('transaction_type') === 'deposit')>

                                Deposit / 存入

                            </option>

                            <option value="withdraw" @selected(old('transaction_type') === 'withdraw')>

                                Withdraw / 提取

                            </option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Nominal / 金额

                        </label>

                        <input
                            type="number"
                            name="amount"
                            class="form-control"
                            value="{{ old('amount') }}"
                            min="1"
                            step="0.01"
                            required>

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">

                            Catatan / 备注

                        </label>

                        <textarea
                            name="note"
                            rows="3"
                            class="form-control">{{ old('note') }}</textarea>

                    </div>

                </div>

                <div class="text-end">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Simpan / 保存

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
