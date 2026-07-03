@extends('layouts.app')

@section('content')

<div class="container">

<h2 class="mb-4">

📷 OCR Nota Pembelian / OCR采购单

</h2>

<div class="card">

<div class="card-body">

<form
method="POST"
action="{{ route('ocr.upload') }}"
enctype="multipart/form-data">

@csrf

<div class="mb-3">

<label>Upload Foto Nota</label>

<input
type="file"
name="photo"
class="form-control"
required>

</div>

<button class="btn btn-success">

📷 Upload & Scan

</button>

</form>

</div>

</div>

</div>

@endsection