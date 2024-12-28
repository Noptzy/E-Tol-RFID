<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            min-height: 100vh;
            color: #333; 
            font-family: 'Poppins', sans-serif;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
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

        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            color: #333; /* Changed label color to black */
        }

        .form-label i {
            margin-right: 5px;
            color: #8e44ad;
        }

        .form-container img {
            display: block;
            margin: 10px auto;
            border-radius: 10%;
        }

        .upload-preview {
            text-align: center;
        }
    </style>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const preview = document.getElementById('preview');
                preview.src = reader.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="form-container">
            <h1 class="text-center">CREATE USER</h1>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">
                        <i class="fas fa-user"></i> Nama
                    </label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="uid" class="form-label">
                        <i class="fas fa-id-card"></i> UID
                    </label>
                    <input type="text" class="form-control" id="uid" name="uid" required>
                </div>
                <div class="mb-3">
                    <label for="saldo" class="form-label">
                        <i class="fas fa-wallet"></i> Saldo
                    </label>
                    <input type="number" class="form-control" id="saldo" name="saldo" required>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">
                        <i class="fas fa-camera"></i> Upload Foto
                    </label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
                    <div class="upload-preview mt-3">
                        <img id="preview" src="#" alt="Image Preview" style="display:none; max-width: 150px; max-height: 150px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Create User</button>
            </form>
        </div>
    </div>
</body>
</html>
