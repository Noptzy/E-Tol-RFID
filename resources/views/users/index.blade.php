<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(120deg, #657bf6, #fd85fd);
            min-height: 100vh;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            background: linear-gradient(135deg, #ffffff, #c4dafa);
            color: #333;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            object-fit: cover;
            height: 150px;
            width: 150px;
            border-radius: 50%;
            margin: 15px auto;
            border: 4px solid #fff;
        }
        .card-actions a, .card-actions form {
            margin: 5px;
        }
        h1 {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            /* background: linear-gradient(90deg, #ffffff, #fad0c4); */
            padding: 10px 20px;
            border-radius: 30px;
            display: inline-block;
            font-size: 2.5rem;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
        }
        .btn-primary {
            background-color: #115dcf;
            border: none;
        }
        .btn-primary:hover {
            background-color: #082f99;
        }
        
    </style>
</head>
<body>
    <div class="container mt-5 text-center">
        <h1>User List</h1>
        <div class="text-center mb-4">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Create New User
            </a>
        </div>
        <div class="row">
            @foreach ($users as $user)
                <div class="col-md-4 mb-4">
                    <div class="card text-center">
                        <img src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://via.placeholder.com/150' }}" alt="User Photo">
                        <div class="card-body">
                            <h5 class="card-title"><strong>{{ $user->nama }}</strong></h5>
                            <p><i>UID: {{ $user->uid }}</i></p>
                            <p><i>Saldo: Rp {{ number_format($user->saldo, 0, ',', '.') }}</i></p>
                            <div class="card-actions d-flex justify-content-center">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
