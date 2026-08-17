@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>Backup Data / 数据备份</h2>

            <small class="text-muted">
                Backup manual data ERP.
            </small>

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            <strong>{{ session('success') }}</strong>

            @if(session('backup'))

                <div class="mt-3">

                    <div>Folder:</div>

                    <div class="fw-bold">
                        {{ session('backup.folder_name') }}
                    </div>

                    <div class="mt-3">Contains:</div>

                    <div>✓ {{ session('backup.excel_name') }}</div>

                    <div>✓ {{ session('backup.sql_name') }}</div>

                </div>

            @endif

        </div>

    @endif

    @if($errors->any())

        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>

    @endif

    <div class="card">

        <div class="card-body">

            <form method="POST"
                  action="{{ route('backups.store') }}">

                @csrf

                <button type="submit"
                        class="btn btn-primary">
                    Backup ERP
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
