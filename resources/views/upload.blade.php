<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
</head>
<body>
    <h1>File Import</h1>

    <form action="" method="post" enctype="multipart/form-data">
        @csrf

        <label for="csv_file">Choose File</label> <br>
        <input type="file" name="csv_file" id="csv_file" accept=".csv"> <br> <br>

        <button type="submit">Upload</button>
    </form>
</body>
</html>