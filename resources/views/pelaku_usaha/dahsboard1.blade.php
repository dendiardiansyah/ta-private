@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Pelaku Usaha</h1>
    <p>Selamat datang, {{ Auth::guard('pelaku_usaha')->user()->nama }}!</p>
</div>
@endsection