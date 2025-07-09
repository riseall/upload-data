@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800">Form Upload File CSV</h1>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('upload.data') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="data_type" class="form-label">Pilih Jenis Data</label>
                        <select class="form-select" id="data_type" name="data_type" required>
                            <option value="">-- Pilih Jenis Data --</option>
                            <option value="otif">OTIF (On Time In Full)</option>
                            <option value="top">TOP (Target Operasional)</option>
                            <option value="selling_out">Selling Out</option>
                            <option value="inventory">Inventory</option>
                        </select>
                        @error('data_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Pilih File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv, .txt">
                        <div class="form-text">File harus berekstensi .csv</div>
                        @error('csv_file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
            </div>
        </div>
    </div>
@endsection
