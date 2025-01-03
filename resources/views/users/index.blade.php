<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    @include('layouts.style')
</head>
<body>
    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>Daftar User</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-2"></i>Tambah User
                    </a>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($users as $user)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    @if($user->foto)
                                        <img src="{{ asset('storage/fotos/' . $user->foto) }}" alt="{{ $user->nama }}" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                                            <span class="text-white">No Foto</span>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="card-title text-center">{{ $user->nama }}</h5>
                                <p class="card-text text-center mb-2"><strong>UID:</strong> {{ $user->uid }}</p>
                                <p class="card-text text-center"><strong>Saldo:</strong> Rp {{ number_format($user->saldo, 0, ',', '.') }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>