<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Saldo Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #141e30 0%, #243b55 100%);
            color: #fff;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(45deg, #1e3c72 0%, #2a5298 100%) !important;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .card-header {
            padding: 1rem;
            font-weight: bold;
            border-radius: 15px 15px 0 0;
        }
        .card-header.bg-primary {
            background: linear-gradient(45deg, #1e3c72 0%, #2a5298 100%) !important;
        }
        .card-header.bg-warning {
            background: linear-gradient(45deg, #FF8008 0%, #FFC837 100%) !important;
        }
        .profile-image-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100%;
            padding: 20px;
        }
        #foto {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            display: none;
            margin: auto;
        }
        .user-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .user-info p {
            font-size: 1rem;
            padding: 0.8rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.15);
            margin-bottom: 0.8rem;
            border-left: 4px solid #2a5298;
        }
        .btn-primary {
            background: linear-gradient(45deg, #1e3c72 0%, #2a5298 100%);
            border: none;
        }
        .btn-danger {
            background: linear-gradient(45deg, #cb2d3e 0%, #ef473a 100%);
            border: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-credit-card-2-front me-2"></i>RFID E-TOL
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="/"><i class="bi bi-house-door"></i> Dashboard</a>
                <a class="nav-link" href="/users"><i class="bi bi-people"></i> Users</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Informasi Pengguna</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="profile-image-container">
                                    <img id="foto" src="" alt="Foto Pengguna">
                                </div>
                            </div>
                            <div class="col-md-8 user-info">
                                <p><strong>UID:</strong> <span id="uid">-</span></p>
                                <p><strong>Nama:</strong> <span id="nama">-</span></p>
                                <p><strong>Saldo:</strong> <span id="saldo">-</span></p>
                                <p><strong>Saldo Sebelumnya:</strong> <span id="saldo-sebelumnya">-</span></p>
                                <p><strong>Saldo Sesudah:</strong> <span id="saldo-sesudah">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Kurangi Saldo</h4>
                    </div>
                    <div class="card-body">
                        <form id="kurangi-saldo-form">
                            <div class="mb-4">
                                <label class="form-label">Jumlah Pengurangan (Rp)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="tarif" placeholder="Masukkan jumlah">
                                </div>
                            </div>
                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Kurangi Saldo
                                </button>
                                <a href="/users" class="btn btn-danger btn-lg">
                                    <i class="bi bi-people me-2"></i>Halaman User
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const apiUrl = "http://192.168.100.26:8000/api";

        function showAlert(type, message) {
            Swal.fire({
                icon: type === 'success' ? 'success' : 'error',
                title: type === 'success' ? 'Berhasil!' : 'Gagal!',
                text: message,
                timer: type === 'success' ? 5000 : 3000,
                timerProgressBar: true
            });
        }

        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        setInterval(async () => {
            try {
                const response = await axios.get(`${apiUrl}/get-latest-uid`);
                const uid = response.data.uid;

                const currentUid = document.getElementById("uid").textContent;
                if (uid && uid !== currentUid) {
                    document.getElementById("uid").textContent = uid;
                    document.getElementById("nama").textContent = "-";
                    document.getElementById("saldo").textContent = "-";
                    document.getElementById("saldo-sebelumnya").textContent = "-";
                    document.getElementById("saldo-sesudah").textContent = "-";
                    document.getElementById("foto").style.display = "none";
                    document.getElementById("foto").src = "";

                    const userResponse = await axios.post(`${apiUrl}/get-user-by-uid`, { uid });
                    if (userResponse.data.status === "berhasil") {
                        const user = userResponse.data.user;
                        document.getElementById("nama").textContent = user.nama;
                        document.getElementById("saldo").textContent = formatNumber(user.saldo);
                        if (user.foto) {
                            document.getElementById("foto").src = `/storage/fotos/${user.foto}`;
                            document.getElementById("foto").style.display = "block";
                        }
                    } else {
                        showAlert("error", "User tidak ditemukan!");
                    }
                }
            } catch (error) {
                console.error("Error mendapatkan UID: ", error);
            }
        }, 5000);

        document.getElementById("tarif").addEventListener("input", (e) => {
            const value = e.target.value.replace(/\./g, "");
            if (!isNaN(value) && value !== "") {
                e.target.value = formatNumber(value);
            } else {
                e.target.value = "";
            }
        });

        document.getElementById("kurangi-saldo-form").addEventListener("submit", async (e) => {
            e.preventDefault();
            const uid = document.getElementById("uid").textContent;
            const tarif = document.getElementById("tarif").value.replace(/\./g, "");

            try {
                const userResponse = await axios.post(`${apiUrl}/get-user-by-uid`, { uid });
                if (userResponse.data.status === "berhasil") {
                    const user = userResponse.data.user;
                    const saldoSebelumnya = user.saldo;
                    document.getElementById("saldo-sebelumnya").textContent = formatNumber(saldoSebelumnya);

                    const response = await axios.post(`${apiUrl}/kurangi-saldo`, { uid, tarif });
                    if (response.data.status === "success") {
                        document.getElementById("saldo-sesudah").textContent = formatNumber(response.data.saldo_akhir);
                        document.getElementById("saldo").textContent = formatNumber(response.data.saldo_akhir);

                        Swal.fire({
                            icon: 'success',
                            title: 'Transaksi Berhasil!',
                            text: `Saldo berhasil dikurangi Rp ${formatNumber(tarif)}, Membuka Gerbang!`,
                            timer: 5000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload();
                        });
                    }
                }
            } catch (error) {
                showAlert("error", error.response?.data?.message || "Terjadi kesalahan");
            }
        });
    </script>
</body>
</html>
