<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>User Detail</h1>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <p>{{ $user->nama }}</p>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            @if ($user->foto)
                <img src="{{ asset('storage/' . $user->foto) }}" alt="User Photo" width="150">
            @else
                <p>No Photo</p>
            @endif
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to User List</a>
    </div>
</body>
</html>
