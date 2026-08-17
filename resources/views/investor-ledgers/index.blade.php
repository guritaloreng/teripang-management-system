@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Investor Ledger / 投资人流水</h2>

            <small class="text-muted">

                Riwayat modal investor / 投资资金记录

            </small>

        </div>

        <a href="{{ route('investor-ledgers.create') }}"
           class="btn btn-primary">

            Tambah Transaksi / 新增交易

        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card shadow-sm">

        <div class="table-responsive">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>

                        <th width="60">No</th>

                        <th>Tanggal / 日期</th>

                        <th>Investor / 投资人</th>

                        <th>Jenis / 类型</th>

                        <th class="text-end">Nominal / 金额</th>

                        <th>Catatan / 备注</th>

                        <th width="120">Aksi / 操作</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($ledgers as $ledger)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $ledger->transaction_date->format('d-m-Y') }}</td>

                            <td>{{ $ledger->investor->name }}</td>

                            <td>

                                {{ $ledger->transaction_type === 'deposit' ? 'Deposit / 存入' : 'Withdraw / 提取' }}

                            </td>

                            <td class="text-end">

                                Rp {{ number_format($ledger->amount, 0, ',', '.') }}

                            </td>

                            <td>{{ $ledger->note ?: '-' }}</td>

                            <td>

                                <form
                                    action="{{ route('investor-ledgers.destroy', $ledger) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus transaksi modal ini? / 删除此资金交易？')">

                                        Hapus / 删除

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-4">

                                Belum ada transaksi modal. / 暂无资金交易。

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
