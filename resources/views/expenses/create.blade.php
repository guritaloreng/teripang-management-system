@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>💰 Tambah Pengeluaran</h2>

            <small class="text-muted">

                Input pengeluaran perusahaan

            </small>

        </div>

        <a href="{{ route('expenses.index') }}"
           class="btn btn-secondary">

            ← Kembali

        </a>

    </div>

    <div class="card">

        <div class="card-header bg-primary text-white">

            Form Pengeluaran

        </div>

        <div class="card-body">

            <form
                action="{{ route('expenses.store') }}"
                method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Tanggal

                        </label>

                        <input
                            type="date"
                            name="expense_date"
                            class="form-control"
                            value="{{ old('expense_date', date('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Shipment (Opsional)

                        </label>

                        <select
                            name="shipment_id"
                            class="form-select">

                            <option value="">

                                -- Pengeluaran Umum --

                            </option>

                            @foreach($shipments as $shipment)

                                <option
                                    value="{{ $shipment->id }}">

                                    {{ $shipment->shipment_number }}
                                    -
                                    {{ $shipment->destination }}

                                </option>

                            @endforeach

                        </select>

                    </div>
                                        <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Nama Pengeluaran

                        </label>

                        <input
                            type="text"
                            name="expense_name"
                            class="form-control"
                            value="{{ old('expense_name') }}"
                            placeholder="Contoh : Solar"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Nominal

                        </label>

                        <input
                            type="number"
                            name="amount"
                            class="form-control"
                            value="{{ old('amount') }}"
                            min="0"
                            step="0.01"
                            required>

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">

                            Keterangan

                        </label>

                        <input
                            type="text"
                            name="description"
                            class="form-control"
                            value="{{ old('description') }}"
                            placeholder="Contoh : Solar mobil pickup">

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">

                            Catatan

                        </label>

                        <textarea
                            name="note"
                            rows="3"
                            class="form-control">{{ old('note') }}</textarea>

                    </div>
                                    </div>

                <div class="d-flex justify-content-end">

                    <a href="{{ route('expenses.index') }}"
                       class="btn btn-secondary me-2">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        💾 Simpan Pengeluaran

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection