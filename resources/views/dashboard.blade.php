<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Saldo Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #657bf6, #fd85fd);
            font-family: 'Poppins', sans-serif;
        }

        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: auto;
        }

        h1 {
            color: #000000;
            font-size: 2.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
        }

        #user-info {
            background-color: #f9f9f9;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .photo-box {
            width: 150px;
            height: 150px;
            background-color: #ffffff;
            border: 1px solid #0d47a1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .photo-box:hover {
            transform: scale(1.05);
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            border-radius: 8px;
        }

        p {
            color: #333;
            font-size: 1.1rem;
        }

        p strong {
            color: #000000;
        }

        .form-control {
            font-size: 1.2em;
            border-radius: 10px;
            box-shadow: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #0d47a1;
            box-shadow: 0 0 0 0.25rem rgba(13, 71, 161, 0.25);
        }

        button {
            font-size: 1.1em;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            background-color: #0d47a1;
            color: #fff;
            border: none;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #104a70;
        }

        #alert {
            font-size: 1em;
            margin-bottom: 1rem;
        }

        a {
            color: #0d47a1;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            display: inline-block;
            margin-top: 1.5rem;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 767px) {
            .container {
                padding: 1rem;
            }

            h1 {
                font-size: 2rem;
            }

            .photo-box {
                width: 120px;
                height: 120px;
            }
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
