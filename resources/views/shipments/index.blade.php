@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>🚚 Shipment / 发货</h2>

            <small class="text-muted">

                Daftar Pengiriman Teripang

            </small>

        </div>

        <a href="{{ route('shipments.create') }}"
           class="btn btn-primary">

            ➕ Buat Shipment

        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th width="160">

                                No Shipment

                            </th>

                            <th width="120">

                                Tanggal

                            </th>

                            <th>

                                Tujuan

                            </th>

                            <th width="120">

                                Jumlah Nota

                            </th>

                            <th width="120">

                                Status

                            </th>

                            <th width="170">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($shipments as $shipment)

                        <tr>

                            <td>

                                {{ $shipment->shipment_number }}

                            </td>

                            <td>

                                {{ $shipment->shipment_date->format('d-m-Y') }}

                            </td>

                            <td>

                                {{ $shipment->destination }}

                            </td>

                            <td class="text-center">

                                {{ $shipment->purchases->count() }}

                            </td>

                            <td class="text-center">

                                <span class="badge bg-primary">

                                    {{ $shipment->status }}

                                </span>

                            </td>

                            <td>

                                <a href="{{ route('shipments.show',$shipment) }}"
                                   class="btn btn-info btn-sm">

                                    👁 Detail

                                </a>

                                <a href="{{ route('shipments.edit',$shipment) }}"
                                   class="btn btn-warning btn-sm">

                                    ✏ Edit

                                </a>

                                <form
                                    action="{{ route('shipments.destroy',$shipment) }}"
                                    method="POST"
                                    style="display:inline;">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus shipment ini?')">

                                        🗑

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-4">

                                Belum ada Shipment.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    @if($shipments->hasPages())

        <div class="mt-3">

            {{ $shipments->links() }}

        </div>

    @endif

</div>

@endsection