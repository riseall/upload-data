@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800">Form Upload File</h1>
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

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('upload.data') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Input tersembunyi untuk token API -->
                    <input type="hidden" name="api_token" id="api_token_input">

                    <div class="mb-3">
                        <label for="data_type" class="form-label">Pilih Jenis Data</label>
                        <select class="form-select" id="data_type" name="data_type" required>
                            <option value="">-- Pilih Jenis Data --</option>
                            <option value="Master Product" {{ old('data_type') == 'Master Product' ? 'selected' : '' }}>
                                Master Product</option>
                            <option value="Master Customer" {{ old('data_type') == 'Master Customer' ? 'selected' : '' }}>
                                Master Customer</option>
                            <option value="Stock METD" {{ old('data_type') == 'Stock METD' ? 'selected' : '' }}>Stock METD
                            </option>
                            <option value="Sellout Faktur" {{ old('data_type') == 'Sellout Faktur' ? 'selected' : '' }}>
                                Sellout Faktur</option>
                            <option value="Sellout Nonfaktur"
                                {{ old('data_type') == 'Sellout Nonfaktur' ? 'selected' : '' }}>Sellout Non-Faktur</option>
                        </select>
                        @error('data_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Pilih File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv, .txt">
                        <div class="form-text">File harus berekstensi .csv, maksimal ukuran file 2mb.</div>
                        @error('csv_file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Upload</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('access_token');
            const apiTokenInput = document.getElementById('api_token_input');

            // Set nilai input tersembunyi dengan token dari localStorage
            if (apiTokenInput && token) {
                apiTokenInput.value = token;
            }

            // Logika redirect jika tidak terotentikasi (seperti yang sudah dibahas sebelumnya)
            const currentPath = window.location.pathname;
            const protectedRoutes = ['/home', '/upload', '/data'];

            if (!token && protectedRoutes.includes(currentPath)) {
                window.location.href = '/login';
                return;
            }
        });
    </script>
@endsection
