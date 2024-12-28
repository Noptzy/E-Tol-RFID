<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #6a11cb, #2575fc);
            min-height: 100vh;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            background: #fff;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-top: 50px;
        }
        h1 {
            background: linear-gradient(90deg, #657bf6, #fd85fd);
            padding: 10px 20px;
            border-radius: 30px;
            display: inline-block;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            font-size: 2rem;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #115dcf;
            border: none;
        }
        .btn-primary:hover {
            background-color: #082f99;
        }
        .form-label {
            font-weight: bold;
        }
        #preview {
            display: block;
            margin: 10px 0;
            max-width: 150px;
            border-radius: 10px;
        }
    </style>
    <script>
        function previewImage() {
            const input = document.getElementById('foto');
            const preview = document.getElementById('preview');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }

        function formatInputRupiah(input) {
            let angka = input.value.replace(/[^\d]/g, '');
    
            input.value = angka ? 'Rp ' + new Intl.NumberFormat('id-ID').format(angka) : '';
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>EDIT USER</h1>
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $user->nama }}" required>
                </div>
                <div class="mb-3">
                    <label for="saldo" class="form-label">Saldo</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="saldo" 
                        name="saldo" 
                        value="Rp {{ number_format($user->saldo, 0, ',', '.') }}" 
                        required
                        oninput="formatInputRupiah(this)">
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Upload Foto</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImage()">
                    <div class="mt-2">
                        <img id="preview" src="{{ $user->foto ? asset('storage/' . $user->foto) : '' }}" alt="User Photo" style="display: {{ $user->foto ? 'block' : 'none' }}">
                </div>
                </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>    
</body>
</html>
