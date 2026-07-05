@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>🚚 Buat Shipment / 创建发货</h2>

            <small class="text-muted">

                Pilih Nota Pembelian yang akan dikirim

            </small>

        </div>

        <a href="{{ route('shipments.index') }}"
           class="btn btn-secondary">

            ← Kembali

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

    <form method="POST"
          action="{{ route('shipments.store') }}">

        @csrf

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">

                Informasi Shipment

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">

                            Tanggal Shipment

                        </label>

                        <input
                            type="date"
                            name="shipment_date"
                            class="form-control"
                            value="{{ old('shipment_date', date('Y-m-d')) }}"
                            required>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">

                            Tujuan

                        </label>

                        <input
                            type="text"
                            name="destination"
                            class="form-control"
                            value="{{ old('destination','Makassar') }}"
                            required>

                    </div>

                    <div class="col-md-12 mt-3">

                        <label class="form-label">

                            Catatan

                        </label>

                        <textarea
                            name="note"
                            rows="3"
                            class="form-control">{{ old('note') }}</textarea>

                    </div>

                </div>

            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-header bg-success text-white">

                Pilih Nota Pembelian

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th width="60">

                                Pilih

                            </th>

                            <th>

                                No Purchase

                            </th>

                            <th width="120">

                                Tanggal

                            </th>

                            <th>

                                Supplier

                            </th>

                            <th class="text-end">

                                Berat

                            </th>

                            <th class="text-end">

                                Grand Total

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($purchases as $purchase)

                        <tr>

                            <td class="text-center">

                                <input
                                    type="checkbox"
                                    name="purchase_ids[]"
                                    value="{{ $purchase->id }}">

                            </td>

                            <td>

                                {{ $purchase->purchase_number }}

                            </td>

                            <td>

                                {{ $purchase->purchase_date->format('d-m-Y') }}

                            </td>

                            <td>

                                {{ $purchase->supplier->name }}

                            </td>

                            <td class="text-end">

                                {{ number_format(
                                    $purchase->items->sum('purchase_weight'),
                                    2
                                ) }} Kg

                            </td>

                            <td class="text-end">

                                Rp {{ number_format(
                                    $purchase->grand_total,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-4">

                                Tidak ada Purchase yang siap dikirim.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="mt-4 text-end">

            <button
                type="submit"
                class="btn btn-primary btn-lg">

                💾 Simpan Shipment

            </button>

        </div>

    </form>

</div>

@endsection