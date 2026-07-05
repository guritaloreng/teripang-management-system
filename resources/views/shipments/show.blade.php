@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>🚚 Detail Shipment</h2>

            <small class="text-muted">

                {{ $shipment->shipment_number }}

            </small>

        </div>

        <div>

            <a href="{{ route('shipments.index') }}"
               class="btn btn-secondary">

                ← Kembali

            </a>

            <a href="{{ route('sales.create',$shipment) }}"
               class="btn btn-success">

                + Tambah Penjualan

            </a>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-primary text-white">

                    Informasi Shipment

                </div>

                <div class="card-body">

                    <table class="table table-sm">

                        <tr>

                            <th width="140">Nomor</th>

                            <td>{{ $shipment->shipment_number }}</td>

                        </tr>

                        <tr>

                            <th>Tanggal</th>

                            <td>{{ $shipment->shipment_date->format('d-m-Y') }}</td>

                        </tr>

                        <tr>

                            <th>Tujuan</th>

                            <td>{{ $shipment->destination }}</td>

                        </tr>

                        <tr>

                            <th>Status</th>

                            <td>

                                <span class="badge bg-primary">

                                    {{ $shipment->status }}

                                </span>

                            </td>

                        </tr>

                        <tr>

                            <th>Catatan</th>

                            <td>

                                {{ $shipment->note ?: '-' }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="card shadow-sm text-center">

                        <div class="card-body">

                            <h3>

                                {{ $summary['purchase_count'] }}

                            </h3>

                            <small>Total Nota</small>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card shadow-sm text-center">

                        <div class="card-body">

                            <h3>

                                {{ $summary['type_count'] }}

                            </h3>

                            <small>Jenis Teripang</small>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card shadow-sm text-center">

                        <div class="card-body">

                            <h5>

                                {{ number_format($summary['total_weight'],2) }}

                                Kg

                            </h5>

                            <small>Total Berat</small>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card shadow-sm text-center">

                        <div class="card-body">

                            <h6>

                                Rp {{ number_format($summary['grand_total'],0,',','.') }}

                            </h6>

                            <small>Total Modal</small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <div class="card mt-4 shadow-sm">

    <div class="card-header bg-warning">

        <strong>Progress Penjualan per Jenis</strong>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-hover mb-0">

            <thead class="table-dark">

                <tr>

                    <th width="60">No</th>

                    <th>Jenis Teripang</th>

                    <th class="text-end">Berat Shipment</th>

                    <th class="text-center">Status</th>

                </tr>

            </thead>

            <tbody>

                @forelse($progressPerType as $index => $item)

                    <tr>

                        <td>

                            {{ $index + 1 }}

                        </td>

                        <td>

                            {{ $item['type_name'] }}

                        </td>

                        <td class="text-end">

                            {{ number_format($item['weight'],2) }} Kg

                        </td>

                        <td class="text-center">

                            @switch($item['status'])

                                @case('Belum Dijual')

                                    <span class="badge bg-secondary">

                                        Belum Dijual

                                    </span>

                                    @break

                                @case('Terjual Sebagian')

                                    <span class="badge bg-warning text-dark">

                                        Terjual Sebagian

                                    </span>

                                    @break

                                @case('Selesai')

                                    <span class="badge bg-success">

                                        Selesai

                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-info">

                                        {{ $item['status'] }}

                                    </span>

                            @endswitch

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center">

                            Belum ada data jenis teripang.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
<div class="card mt-4 shadow-sm">

    <div class="card-header bg-success text-white">

        <strong>Daftar Purchase dalam Shipment</strong>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-hover mb-0">

            <thead class="table-dark">

                <tr>

                    <th width="60">No</th>

                    <th>No Purchase</th>

                    <th>Tanggal</th>

                    <th>Supplier</th>

                    <th class="text-end">Total Berat</th>

                    <th class="text-end">Grand Total</th>

                </tr>

            </thead>

            <tbody>

                @forelse($shipment->purchases as $index => $shipmentPurchase)

                    @php

                        $purchase = $shipmentPurchase->purchase;

                    @endphp

                    <tr>

                        <td>

                            {{ $index + 1 }}

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

                        <td colspan="6" class="text-center">

                            Belum ada Purchase pada Shipment ini.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
</div>

@endsection