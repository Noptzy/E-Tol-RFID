<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Daftar User</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Tambah User</a>
        <a href="/" class="btn btn-danger mb-3">E - TOL</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>UID</th>
                    <th>Nama</th>
                    <th>Saldo</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->uid }}</td>
                    <td>{{ $user->nama }}</td>
                    <td>Rp {{ number_format($user->saldo, 0, ',', '.') }}</td>
                    <td>
                        @if($user->foto)
                            <img src="{{ asset('storage/fotos/' . $user->foto) }}" alt="{{ $user->nama }}" width="100">
                        @else
                            <span>Tidak ada foto</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
