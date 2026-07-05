@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>💰 Pengeluaran</h2>

            <small class="text-muted">

                Daftar seluruh pengeluaran perusahaan

            </small>

        </div>

        <a href="{{ route('expenses.create') }}"
           class="btn btn-primary">

            + Tambah Pengeluaran

        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card">

        <div class="card-header bg-primary text-white">

            Daftar Pengeluaran

        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                <tr>

                    <th width="120">Tanggal</th>

                    <th>Nama</th>

                    <th>Shipment</th>

                    <th class="text-end">Nominal</th>

                    <th>Keterangan</th>

                    <th width="150">Aksi</th>

                </tr>

                </thead>

                <tbody>
                                    @forelse($expenses as $expense)

                    <tr>

                        <td>

                            {{ $expense->expense_date->format('d-m-Y') }}

                        </td>

                        <td>

                            {{ $expense->expense_name }}

                        </td>

                        <td>

                            @if($expense->shipment)

                                <span class="badge bg-success">

                                    {{ $expense->shipment->shipment_number }}

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    Umum

                                </span>

                            @endif

                        </td>

                        <td class="text-end">

                            Rp {{ number_format($expense->amount,0,',','.') }}

                        </td>

                        <td>

                            {{ $expense->description }}

                        </td>

                        <td>

                            <a href="{{ route('expenses.edit',$expense) }}"
                               class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <form
                                action="{{ route('expenses.destroy',$expense) }}"
                                method="POST"
                                class="d-inline">

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus pengeluaran ini?')">

                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center">

                            Belum ada data pengeluaran.

                        </td>

                    </tr>

                @endforelse
                                </tbody>

            </table>

        </div>

        <div class="card-footer">

            {{ $expenses->links() }}

        </div>

    </div>

</div>

@endsection