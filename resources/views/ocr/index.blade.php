@extends('layouts.app')

@section('content')

<h1>📷 Upload Nota</h1>

<form action="{{ route('ocr.upload') }}"
method="POST"
enctype="multipart/form-data">

@csrf

<input type="file" name="photo">

<br><br>

<button type="submit">

Upload

</button>

</form>

@if(session('photo'))

<hr>

<h3>Preview</h3>

<img src="{{ asset('storage/'.session('photo')) }}"
width="500">

@endif

@endsection