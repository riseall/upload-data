@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
    <style>
        /* Custom CSS for Loading Spinner */
        .spinner-container {
            text-align: center;
            color: #4e73df;
            /* Primary color from SB Admin 2 */
            padding: 20px;
            /* Add some padding around the spinner */
            width: 100%;
            /* Ensure it takes full width of the cell */
            display: block;
            /* Ensure it behaves like a block element for centering */
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }

        .loading-text {
            font-weight: bold;
        }
    </style>
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data yang Sudah Tersimpan</h1>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">

                {{-- Tab untuk memilih jenis data yang akan ditampilkan --}}
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="mstrProd-tab" data-bs-toggle="tab" data-bs-target="#prod-pane"
                            type="button" role="tab" aria-controls="prod-pane" aria-selected="true">Master
                            Product</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cust-tab" data-bs-toggle="tab" data-bs-target="#cust-pane"
                            type="button" role="tab" aria-controls="cust-pane" aria-selected="false">Master
                            Customer</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock-pane"
                            type="button" role="tab" aria-controls="stock-pane" aria-selected="false">Stock
                            METD</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="faktur-tab" data-bs-toggle="tab" data-bs-target="#faktur-pane"
                            type="button" role="tab" aria-controls="faktur-pane" aria-selected="false">Sellout dengan
                            Faktur</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="nonfaktur-tab" data-bs-toggle="tab" data-bs-target="#nonfaktur-pane"
                            type="button" role="tab" aria-controls="nonfaktur-pane" aria-selected="false">Sellout tanpa
                            Faktur</button>
                    </li>
                </ul>

                {{-- Konten untuk setiap tab (tabel data) --}}
                <div class="tab-content" id="myTabContent">
                    {{-- Tabel Master Product --}}
                    <div class="tab-pane fade show active" id="prod-pane" role="tabpanel" aria-labelledby="mstrProd-tab">
                        <div class="table-responsive mt-3" style="position: relative;">
                            <table class="table table-striped table-bordered table-hover" id="prodTable" width="100%"
                                cellspacing="0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Barang METD</th>
                                        <th>Kode Barang PH</th>
                                        <th>Nama Barang METD</th>
                                        <th>Nama Barang PH</th>
                                        <th>Satuan METD</th>
                                        <th>Satuan PH</th>
                                        <th>Konversi Qty</th>
                                        <th>Tanggal Upload</th>
                                        {{-- <th>Updated At</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="master-product-table-body">
                                    <tr id="loading-row-prodTable" style="display: none;">
                                        <td colspan="10" class="text-center">
                                            <div class="spinner-container">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <div class="loading-text">Memuat Master Product...</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tabel Master Customer --}}
                    <div class="tab-pane fade" id="cust-pane" role="tabpanel" aria-labelledby="cust-tab">
                        <div class="table-responsive mt-3" style="position: relative;">
                            <table class="table table-striped table-bordered table-hover" id="custTable" width="100%"
                                cellspacing="0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Id Outlet</th>
                                        <th>Nama Outlet</th>
                                        <th>Cabang PH</th>
                                        <th>Kode Cabang PH</th>
                                        <th>Cabang METD</th>
                                        <th>Alamat 1</th>
                                        <th>Alamat 2</th>
                                        <th>Alamat 3</th>
                                        <th>No. Telepon</th>
                                        <th>Tanggal Upload</th>
                                        {{-- <th>Updated At</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="master-customer-table-body">
                                    <tr id="loading-row-custTable" style="display: none;">
                                        <td colspan="12" class="text-center">
                                            <div class="spinner-container">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <div class="loading-text">Memuat Master Customer...</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tabel Stock METD --}}
                    <div class="tab-pane fade" id="stock-pane" role="tabpanel" aria-labelledby="stock-tab">
                        <div class="table-responsive mt-3" style="position: relative;">
                            <table class="table table-striped table-bordered table-hover" id="stockTable" width="100%"
                                cellspacing="0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Barang METD</th>
                                        <th>Kode Barang PH</th>
                                        <th>Nama Barang METD</th>
                                        <th>Nama Barang PH</th>
                                        <th>Plant</th>
                                        <th>Nama Plant</th>
                                        <th>Suhu Gudang Penyimpanan</th>
                                        <th>Batch PH</th>
                                        <th>Expired Date</th>
                                        <th>Satuan METD</th>
                                        <th>Satuan PH</th>
                                        <th>Harga Beli</th>
                                        <th>Konversi Qty</th>
                                        <th>Qty On Hand</th>
                                        <th>Qty Sellable</th>
                                        <th>Qty Non Sellable</th>
                                        <th>Qty Intransit In</th>
                                        <th>Nilai Intransit In</th>
                                        <th>Qty Intransit Pass</th>
                                        <th>Nilai Intransit Pass</th>
                                        <th>Tanggal Terima Barang</th>
                                        <th>Source Beli</th>
                                        <th>Tanggal Upload</th>
                                        {{-- <th>Updated At</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="stock-metd-table-body">
                                    <tr id="loading-row-stockTable" style="display: none;">
                                        <td colspan="25" class="text-center">
                                            <div class="spinner-container">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <div class="loading-text">Memuat Stock METD...</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tabel Sellout Faktur --}}
                    <div class="tab-pane fade" id="faktur-pane" role="tabpanel" aria-labelledby="faktur-tab">
                        <div class="table-responsive mt-3" style="position: relative;">
                            <table class="table table-striped table-bordered table-hover" id="fakturTable" width="100%"
                                cellspacing="0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Cabang PH</th>
                                        <th>Cabang PH</th>
                                        <th>Tanggal Faktur</th>
                                        <th>Id Outlet</th>
                                        <th>No Faktur</th>
                                        {{-- <th>No Invoice</th>
                                        <th>Status</th> --}}
                                        <th>Nama Outlet</th>
                                        <th>Alamat 1</th>
                                        <th>Alamat 2</th>
                                        <th>Alamat 3</th>
                                        <th>Kode Barang METD</th>
                                        <th>Kode Barang PH</th>
                                        <th>Nama Barang METD</th>
                                        <th>Satuan METD</th>
                                        <th>Satuan PH</th>
                                        <th>Qty</th>
                                        <th>Konversi Qty</th>
                                        <th>HNA</th>
                                        {{-- <th>Diskon Dimuka (%)</th>
                                        <th>Diskon Dimuka</th> --}}
                                        <th>Diskon 1 (%)</th>
                                        <th>Diskon 1</th>
                                        <th>Diskon 2 (%)</th>
                                        <th>Diskon 2</th>
                                        <th>Total Diskon (%)</th>
                                        <th>Total Diskon</th>
                                        <th>Netto</th>
                                        <th>Brutto</th>
                                        <th>Ppn</th>
                                        <th>Jumlah</th>
                                        <th>Segmen</th>
                                        <th>SO Number</th>
                                        <th>No Shipper</th>
                                        <th>No PO</th>
                                        <th>Batch PH</th>
                                        <th>Expired Date</th>
                                        <th>Tanggal Upload</th>
                                        {{-- <th>Updated At</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="sellout-faktur-table-body">
                                    <tr id="loading-row-fakturTable" style="display: none;">
                                        <td colspan="40" class="text-center">
                                            <div class="spinner-container">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <div class="loading-text">Memuat Sellout Faktur...</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tabel Sellout NonFaktur --}}
                    <div class="tab-pane fade" id="nonfaktur-pane" role="tabpanel" aria-labelledby="nonfaktur-tab">
                        <div class="table-responsive mt-3" style="position: relative;">
                            <table class="table table-striped table-bordered table-hover" id="nonfakturTable"
                                width="100%" cellspacing="0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Cabang PH</th>
                                        <th>Cabang PH</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Id Outlet</th>
                                        {{-- <th>No Invoice</th>
                                        <th>Status</th> --}}
                                        <th>Nama Outlet</th>
                                        <th>Alamat 1</th>
                                        <th>Alamat 2</th>
                                        <th>Alamat 3</th>
                                        <th>Kode Barang METD</th>
                                        <th>Kode Barang PH</th>
                                        <th>Nama Barang METD</th>
                                        <th>Satuan METD</th>
                                        <th>Satuan PH</th>
                                        <th>Qty</th>
                                        <th>Konversi Qty</th>
                                        <th>HNA</th>
                                        {{-- <th>Diskon Dimuka (%)</th>
                                        <th>Diskon Dimuka</th> --}}
                                        <th>Diskon 1 (%)</th>
                                        <th>Diskon 1</th>
                                        <th>Diskon 2 (%)</th>
                                        <th>Diskon 2</th>
                                        <th>Total Diskon (%)</th>
                                        <th>Total Diskon</th>
                                        <th>Netto</th>
                                        <th>Brutto</th>
                                        <th>Ppn</th>
                                        <th>Jumlah</th>
                                        <th>Segmen</th>
                                        <th>SO Number</th>
                                        <th>No Shipper</th>
                                        <th>No PO</th>
                                        <th>Batch PH</th>
                                        <th>Expired Date</th>
                                        <th>Tanggal Upload</th>
                                        {{-- <th>Updated At</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="sellout-nonfaktur-table-body">
                                    <tr id="loading-row-nonfakturTable" style="display: none;">
                                        <td colspan="40" class="text-center">
                                            <div class="spinner-container">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <div class="loading-text">Memuat Sellout Nonfaktur...</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- PINDAHKAN SEMUA SCRIPT KE SINI --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="js/datatables.js"></script>
@endpush
