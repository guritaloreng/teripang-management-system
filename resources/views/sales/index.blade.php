@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>📤 Nota Penjualan</h2>

            <small class="text-muted">

                Daftar seluruh invoice penjualan

            </small>

        </div>

    </div>

    <div class="card">

        <div class="card-header bg-success text-white">

            Daftar Invoice

        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th width="60">No</th>

                        <th>Invoice</th>

                        <th>Tanggal</th>

                        <th>Buyer</th>

                        <th>Shipment</th>

                        <th class="text-end">

                            Total

                        </th>

                        <th width="180">

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody>
                                    @forelse($sales as $index => $sale)

                    <tr>

                        <td>

                            {{ $sales->firstItem() + $index }}

                        </td>

                        <td>

                            <strong>

                                {{ $sale->invoice_number }}

                            </strong>

                        </td>

                        <td>

                            {{ $sale->sale_date->format('d-m-Y') }}

                        </td>

                        <td>

                            {{ $sale->buyer }}

                        </td>

                        <td>

                            {{ $sale->shipment->shipment_number }}

                        </td>

                        <td class="text-end">

                            Rp {{ number_format($sale->items->sum('subtotal'),0,',','.') }}

                        </td>

                        <td>

                            <a href="{{ route('sales.show',$sale) }}"
                               class="btn btn-info btn-sm">

                                Detail

                            </a>

                            <form
                                action="{{ route('sales.destroy',$sale) }}"
                                method="POST"
                                class="d-inline">

                                @csrf

                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus invoice ini?')">

                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="text-center">

                            Belum ada invoice penjualan.

                        </td>

                    </tr>

                @endforelse
                                </tbody>

            </table>

        </div>

        @if($sales->hasPages())

            <div class="card-footer">

                {{ $sales->links() }}

            </div>

        @endif

    </div>

</div>

@endsection