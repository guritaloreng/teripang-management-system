@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-md-5">

            <div class="card">

                <div class="card-header">
                    📷 Foto Nota
                </div>

                <div class="card-body text-center">

                    <img
                        src="{{ asset('storage/'.$photo) }}"
                        class="img-fluid border rounded">

                </div>

            </div>

        </div>

        <div class="col-md-7">

            <div class="card">

                <div class="card-header">
                    📄 Hasil OCR Mentah
                </div>

                <div class="card-body">

                    @if(isset($result['raw_text']))

<pre style="white-space: pre-wrap;font-size:14px;">{{ $result['raw_text'] }}</pre>

                    @else

<div class="alert alert-danger">

raw_text tidak ditemukan.

</div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection