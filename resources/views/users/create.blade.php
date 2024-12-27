<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Tambah User</h1>
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="uid" class="form-label">UID</label>
                <input type="text" class="form-control" id="uid" name="uid" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="saldo" class="form-label">Saldo</label>
                <input type="number" class="form-control" id="saldo" name="saldo" required>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto (opsional)</label>
                <input type="file" class="form-control" id="foto" name="foto">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/users" class="btn btn-danger">Halaman User</a>
        </form>

    </div>
</body>

</html>
