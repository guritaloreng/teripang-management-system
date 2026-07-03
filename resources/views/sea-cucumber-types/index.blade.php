@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2>🐚 Jenis Teripang</h2>

        <small>Master Data Jenis Teripang</small>

    </div>

    <a href="{{ route('sea-cucumber-types.create') }}"
       class="btn btn-primary">

        + Tambah Jenis

    </a>

</div>

@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

<table class="table table-bordered">

    <thead class="table-dark">

        <tr>

            <th width="60">No</th>

            <th>Nama Jenis</th>

            <th width="150">Aksi</th>

        </tr>

    </thead>

    <tbody>

        @forelse($types as $type)

        <tr>

            <td>{{ $loop->iteration }}</td>

            <td>{{ $type->name }}</td>

            <td>

                <a href="{{ route('sea-cucumber-types.edit',$type) }}"
                   class="btn btn-warning btn-sm">

                    Edit

                </a>

                <form
                    action="{{ route('sea-cucumber-types.destroy',$type) }}"
                    method="POST"
                    style="display:inline">

                    @csrf
                    @method('DELETE')

                    <button
                        onclick="return confirm('Hapus?')"
                        class="btn btn-danger btn-sm">

                        Hapus

                    </button>

                </form>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="3" class="text-center">

                Belum ada data

            </td>

        </tr>

        @endforelse

    </tbody>

</table>

@endsection