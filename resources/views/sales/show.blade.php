@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>📤 Detail Nota Penjualan</h2>

            <small class="text-muted">

                {{ $sale->invoice_number }}

            </small>

        </div>

        <div>

            <a href="{{ route('sales.index') }}"
               class="btn btn-secondary">

                ← Kembali

            </a>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="card shadow-sm">

                <div class="card-header bg-success text-white">

                    Informasi Invoice

                </div>

                <div class="card-body">

                    <table class="table table-sm">

                        <tr>

                            <th width="130">

                                Invoice

                            </th>

                            <td>

                                {{ $sale->invoice_number }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Tanggal

                            </th>

                            <td>

                                {{ $sale->sale_date->format('d-m-Y') }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Buyer

                            </th>

                            <td>

                                {{ $sale->buyer }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Shipment

                            </th>

                            <td>

                                {{ $sale->shipment->shipment_number }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Catatan

                            </th>

                            <td>

                                {{ $sale->note ?: '-' }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">

                    Ringkasan

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <h5>

                                Grand Total

                            </h5>

                            <h3 class="text-success">

                                Rp {{ number_format($grandTotal,0,',','.') }}

                            </h3>

                        </div>

                        <div class="col-md-6">

                            <h5>

                                Status Shipment

                            </h5>

                            <span class="badge bg-primary fs-6">

                                {{ $sale->shipment->status }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <div class="card mt-4 shadow-sm">

    <div class="card-header bg-success text-white">

        Detail Penjualan

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-hover mb-0">

            <thead class="table-dark">

                <tr>

                    <th width="60">No</th>

                    <th>Jenis Teripang</th>

                    <th class="text-end">Berat (Kg)</th>

                    <th class="text-end">Harga / Kg</th>

                    <th class="text-end">Subtotal</th>

                    <th class="text-center">Status</th>

                </tr>

            </thead>

            <tbody>

                @forelse($sale->items as $index => $item)

                    <tr>

                        <td>

                            {{ $index + 1 }}

                        </td>

                        <td>

                            {{ $item->type->name }}

                        </td>

                        <td class="text-end">

                            {{ number_format($item->weight,2) }}

                        </td>

                        <td class="text-end">

                            Rp {{ number_format($item->price,0,',','.') }}

                        </td>

                        <td class="text-end">

                            Rp {{ number_format($item->subtotal,0,',','.') }}

                        </td>

                        <td class="text-center">

                            @if($item->status == 'Selesai')

                                <span class="badge bg-success">

                                    Selesai

                                </span>

                            @else

                                <span class="badge bg-warning text-dark">

                                    Terjual Sebagian

                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">

                            Belum ada item penjualan.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
<div class="d-flex justify-content-between mt-4">

    <a href="{{ route('sales.index') }}"
       class="btn btn-secondary">

        ← Daftar Penjualan

    </a>

    <div>

        <a href="{{ route('shipments.show',$sale->shipment) }}"
           class="btn btn-primary">

            🚚 Lihat Shipment

        </a>

        <form
            action="{{ route('sales.destroy',$sale) }}"
            method="POST"
            class="d-inline"
            onsubmit="return confirm('Yakin ingin menghapus invoice ini?');">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="btn btn-danger">

                🗑 Hapus Invoice

            </button>

        </form>

    </div>

</div>

</div>

@endsection