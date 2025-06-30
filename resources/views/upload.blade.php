<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1100px;
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4 fw-bold">Upload Data</h1>
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
                    <div class="form-text">File harus berekstensi .csv atau .txt dan maksimal 2MB.</div>
                    @error('csv_file')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">Upload</button>
            </form>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>