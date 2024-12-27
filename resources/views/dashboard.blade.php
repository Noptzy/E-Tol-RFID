<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Saldo Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e3f2fd; /* Biru muda */
        }

        .container {
            background-color: #ffffff; /* Putih */
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0d47a1; /* Biru gelap */
        }

        #user-info p {
            font-size: 1.1em;
            color: #0d47a1; /* Biru */
        }

        .form-control {
            font-size: 1.2em;
        }

        button {
            font-size: 1.1em;
        }

        #alert {
            font-size: 1em;
        }

        .photo-box {
            width: 150px;
            height: 150px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .photo-box img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1><i class="fas fa-id-card"></i> RFID E - TOL</h1>
        <div id="alert" class="alert d-none" role="alert"></div>

        <div id="user-info" class="mt-4 p-3 shadow-sm rounded">
            <div class="photo-box">
                <img id="foto" src="" alt="Foto User" onerror="this.src='https://via.placeholder.com/150';">
            </div>
                        
            <p><strong><i class="fas fa-fingerprint"></i> UID:</strong> <span id="uid">-</span></p>
            <p><strong><i class="fas fa-user"></i> Nama:</strong> <span id="nama">-</span></p>
            <p><strong><i class="fas fa-wallet"></i> Saldo:</strong> <span id="saldo">-</span></p>
            <p><strong><i class="fas fa-money-bill-wave"></i> Saldo Sebelumnya:</strong> <span id="saldo-sebelumnya">-</span></p>
            <p><strong><i class="fas fa-coins"></i> Saldo Sesudah:</strong> <span id="saldo-sesudah">-</span></p>
        </div>

        <form id="kurangi-saldo-form" class="mt-4 p-3 shadow-sm rounded">
            <div class="mb-3">
                <label for="tarif" class="form-label"><i class="fas fa-minus-circle"></i> Kurangi Saldo (Rp)</label>
                <input type="text" class="form-control" id="tarif" placeholder="Masukkan jumlah pengurangan">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> Kurangi Saldo</button>
        </form>
        <a href="/users">user</a>
    </div>

    <!-- Include Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const apiUrl = "http://192.168.100.29:8000/api";

        function showAlert(type, message) {
            const alert = document.getElementById("alert");
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alert.classList.remove("d-none");
            setTimeout(() => alert.classList.add("d-none"), 3000);
        }

        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        setInterval(async () => {
    try {
        const response = await axios.get(`${apiUrl}/get-latest-uid`);
        const uid = response.data.uid;
        const currentUid = document.getElementById("uid").textContent;
        
        // Jika UID baru terdeteksi
        if (uid && uid !== currentUid) {
            document.getElementById("uid").textContent = uid;
            document.getElementById("nama").textContent = "-";
            document.getElementById("saldo").textContent = "-";
            document.getElementById("saldo-sebelumnya").textContent = "-";
            document.getElementById("saldo-sesudah").textContent = "-";
            document.getElementById("foto").src = "https://via.placeholder.com/150";  // Reset foto

            // Mengambil data user berdasarkan UID
            const userResponse = await axios.post(`${apiUrl}/get-user-by-uid`, { uid });
            if (userResponse.data.status === "berhasil") {
                const user = userResponse.data.user;
                document.getElementById("nama").textContent = user.nama;
                document.getElementById("saldo").textContent = formatNumber(user.saldo);
                
                // Menampilkan foto jika ada
                if (user.foto) {
                    const fotoUrl = `${window.location.origin}/storage/users/${user.foto}`;
                    console.log('URL Foto:', fotoUrl);  // Debugging: Periksa URL foto
                    document.getElementById("foto").src = fotoUrl;
                } else {
                    // Gunakan placeholder jika foto tidak ada
                    document.getElementById("foto").src = "https://via.placeholder.com/150";
                }
            } else {
                showAlert("danger", "User tidak ditemukan!");
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
                    if (response.data.status === "berhasil") {
                        const saldoSesudah = saldoSebelumnya - parseInt(tarif);
                        document.getElementById("saldo-sesudah").textContent = formatNumber(saldoSesudah);
                        showAlert("success", "Saldo berhasil dikurangi, Gerbang Dibuka");

                        setTimeout(() => location.reload(), 3000);
                    }
                }
            } catch (error) {
                showAlert("danger", error.response?.data?.message || "Terjadi kesalahan");
            }
        });
    </script>

</body>

</html>
