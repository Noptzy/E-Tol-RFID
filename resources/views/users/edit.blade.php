<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit User</h1>
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="uid" class="form-label">UID</label>
                <input type="text" class="form-control" id="uid" name="uid" value="{{ $user->uid }}" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ $user->nama }}" required>
            </div>
            <div class="mb-3">
                <label for="saldo" class="form-label">Saldo</label>
                <input type="number" class="form-control" id="saldo" name="saldo" value="{{ $user->saldo }}" required>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto (opsional)</label>
                <input type="file" class="form-control" id="foto" name="foto">
                @if($user->foto)
                    <img src="{{ asset('storage/fotos/' . $user->foto) }}" alt="{{ $user->nama }}" width="100" class="mt-2">
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>