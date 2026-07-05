@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>✏️ Edit Pengeluaran</h2>

            <small class="text-muted">

                Perbarui data pengeluaran

            </small>

        </div>

        <a href="{{ route('expenses.index') }}"
           class="btn btn-secondary">

            ← Kembali

        </a>

    </div>

    <div class="card">

        <div class="card-header bg-warning">

            Form Edit Pengeluaran

        </div>

        <div class="card-body">

            <form
                action="{{ route('expenses.update',$expense) }}"
                method="POST">

                @csrf

                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Tanggal

                        </label>

                        <input
                            type="date"
                            name="expense_date"
                            class="form-control"
                            value="{{ old('expense_date',$expense->expense_date->format('Y-m-d')) }}"
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
                                    value="{{ $shipment->id }}"
                                    @selected(old('shipment_id',$expense->shipment_id)==$shipment->id)>

                                    {{ $shipment->shipment_number }}

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
                            value="{{ old('expense_name', $expense->expense_name) }}"
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
                            value="{{ old('amount', $expense->amount) }}"
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
                            value="{{ old('description', $expense->description) }}"
                            required>

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">

                            Catatan

                        </label>

                        <textarea
                            name="note"
                            rows="3"
                            class="form-control">{{ old('note', $expense->note) }}</textarea>

                    </div>
                                    </div>

                <div class="d-flex justify-content-end">

                    <a href="{{ route('expenses.index') }}"
                       class="btn btn-secondary me-2">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="btn btn-warning">

                        💾 Update Pengeluaran

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection