@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />

    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data yang Sudah Tersimpan</h1>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                {{-- Tab untuk memilih jenis data yang akan ditampilkan --}}
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="otif-tab" data-bs-toggle="tab" data-bs-target="#otif"
                            type="button" role="tab" aria-controls="otif" aria-selected="true">OTIF</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="top-tab" data-bs-toggle="tab" data-bs-target="#top" type="button"
                            role="tab" aria-controls="top" aria-selected="false">TOP</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="selling-out-tab" data-bs-toggle="tab" data-bs-target="#selling-out"
                            type="button" role="tab" aria-controls="selling-out" aria-selected="false">Selling
                            Out</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory"
                            type="button" role="tab" aria-controls="inventory" aria-selected="false">Inventory</button>
                    </li>
                </ul>

                {{-- Konten untuk setiap tab (tabel data) --}}
                <div class="tab-content" id="myTabContent">
                    {{-- Tabel OTIF --}}
                    <div class="tab-pane fade show active" id="otif" role="tabpanel" aria-labelledby="otif-tab">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-bordered table-hover" id="dataTable" width="100%"
                                cellspacing="0">
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
            </div>
        </div>
    </div>

    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
@endsection
