@extends('layouts.app')
@section('content')
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
            <div class="form-text">File harus berekstensi .csv atau .txt dan maksimal 2MB.</div>
            @error('csv_file')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-success">Upload</button>
    </form>

    <hr class="my-5">

    <h2 class="mb-4 text-center">Data yang Sudah Masuk</h2>

    {{-- Tab untuk memilih jenis data yang akan ditampilkan --}}
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="otif-tab" data-bs-toggle="tab" data-bs-target="#otif" type="button"
                role="tab" aria-controls="otif" aria-selected="true">OTIF</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="top-tab" data-bs-toggle="tab" data-bs-target="#top" type="button" role="tab"
                aria-controls="top" aria-selected="false">TOP</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="selling-out-tab" data-bs-toggle="tab" data-bs-target="#selling-out" type="button"
                role="tab" aria-controls="selling-out" aria-selected="false">Selling Out</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button"
                role="tab" aria-controls="inventory" aria-selected="false">Inventory</button>
        </li>
    </ul>

    {{-- Konten untuk setiap tab (tabel data) --}}
    <div class="tab-content" id="myTabContent">
        {{-- Tabel OTIF --}}
        <div class="tab-pane fade show active" id="otif" role="tabpanel" aria-labelledby="otif-tab">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Produk</th>
                            <th>Jumlah Pesanan</th>
                            <th>Jumlah Terkirim</th>
                            <th>Tanggal Pesanan</th>
                            <th>Tanggal Kirim</th>
                            <th>Dibuat Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($otifData as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->produk }}</td>
                                <td>{{ $data->jumlah_pesanan }}</td>
                                <td>{{ $data->jumlah_terkirim }}</td>
                                <td>{{ $data->tanggal_pesanan }}</td>
                                <td>{{ $data->tanggal_kirim }}</td>
                                <td>{{ $data->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data OTIF.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel TOP --}}
        <div class="tab-pane fade" id="top" role="tabpanel" aria-labelledby="top-tab">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Target</th>
                            <th>Target Value</th>
                            <th>Tanggal Target</th>
                            <th>Dibuat Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topData as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->nama_target }}</td>
                                <td>{{ $data->target_value }}</td>
                                <td>{{ $data->tanggal_target }}</td>
                                <td>{{ $data->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data TOP.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Selling Out --}}
        <div class="tab-pane fade" id="selling-out" role="tabpanel" aria-labelledby="selling-out-tab">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Jumlah Terjual</th>
                            <th>Tanggal Jual</th>
                            <th>Dibuat Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sellingOutData as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->nama_produk }}</td>
                                <td>{{ $data->jumlah_terjual }}</td>
                                <td>{{ $data->tanggal_jual }}</td>
                                <td>{{ $data->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data Selling Out.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Inventory --}}
        <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Lokasi</th>
                            <th>Dibuat Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventoryData as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->nama_barang }}</td>
                                <td>{{ $data->stok }}</td>
                                <td>{{ $data->lokasi }}</td>
                                <td>{{ $data->created_at->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data Inventory.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
