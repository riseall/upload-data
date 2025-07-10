@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <h1 class="h3 mb-0 text-gray-800">Selamat Datang, {{ Auth::user()->name }}</h1>
        </div>
    </div>
@endsection
