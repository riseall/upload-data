@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="row">
                <h3 class="mb-2 text-gray-800">
                    <strong>Selamat Datang,</strong>
                    <span id="home-user-name">Guest</span>
                </h3>

                <div class="col-lg-12">
                    <h5>Ini adalah dashboard aplikasi Anda.</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
