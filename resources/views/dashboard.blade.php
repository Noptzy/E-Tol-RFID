<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Saldo Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>RFID Saldo Management</h1>
        <div id="alert" class="alert d-none" role="alert"></div>

        <div id="user-info" class="mt-3">
            <p><strong>UID:</strong> <span id="uid">-</span></p>
            <p><strong>Nama:</strong> <span id="nama">-</span></p>
            <p><strong>Saldo:</strong> <span id="saldo">-</span></p>
            <p><strong>Saldo Sebelumnya:</strong> <span id="saldo-sebelumnya">-</span></p>
            <p><strong>Saldo Sesudah:</strong> <span id="saldo-sesudah">-</span></p>
        </div>

        <form id="kurangi-saldo-form" class="mt-3">
            <div class="mb-3">
                <label for="tarif" class="form-label">Kurangi Saldo (Rp)</label>
                <input type="text" class="form-control" id="tarif" placeholder="Masukkan jumlah pengurangan">
            </div>
            <button type="submit" class="btn btn-primary">Kurangi Saldo</button>
        </form>
    </div>
    <script>
        const apiUrl = "http://192.168.100.26:8000/api";

        // Fungsi untuk menampilkan notifikasi
        function showAlert(type, message) {
            const alert = document.getElementById("alert");
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alert.classList.remove("d-none");
            setTimeout(() => alert.classList.add("d-none"), 3000);
        }

        // Fungsi untuk format angka dengan titik pemisah ribuan
        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Polling UID terbaru dari server
        setInterval(async () => {
            try {
                const response = await axios.get(`${apiUrl}/get-latest-uid`);
                const uid = response.data.uid;

                // Perbarui UID hanya jika berbeda dari yang sebelumnya
                const currentUid = document.getElementById("uid").textContent;
                if (uid && uid !== currentUid) {
                    document.getElementById("uid").textContent = uid;

                    // Reset data user (nama dan saldo) untuk mendapatkan data baru
                    document.getElementById("nama").textContent = "-";
                    document.getElementById("saldo").textContent = "-";
                    document.getElementById("saldo-sebelumnya").textContent = "-";
                    document.getElementById("saldo-sesudah").textContent = "-";

                    // Dapatkan informasi user berdasarkan UID
                    const userResponse = await axios.post(`${apiUrl}/get-user-by-uid`, { uid });
                    if (userResponse.data.status === "berhasil") {
                        const user = userResponse.data.user;
                        document.getElementById("nama").textContent = user.nama;
                        document.getElementById("saldo").textContent = formatNumber(user.saldo);
                    } else {
                        showAlert("danger", "User tidak ditemukan!");
                    }
                }
            } catch (error) {
                console.error("Error mendapatkan UID: ", error);
            }
        }, 5000); // Polling setiap 5 detik

        // Format input tarif secara real-time
        document.getElementById("tarif").addEventListener("input", (e) => {
            const value = e.target.value.replace(/\./g, ""); // Hilangkan titik sebelumnya
            if (!isNaN(value) && value !== "") {
                e.target.value = formatNumber(value); // Format ulang dengan titik
            } else {
                e.target.value = ""; // Kosongkan jika input tidak valid
            }
        });

        // Form untuk mengurangi saldo
        document.getElementById("kurangi-saldo-form").addEventListener("submit", async (e) => {
            e.preventDefault();
            const uid = document.getElementById("uid").textContent;
            const tarif = document.getElementById("tarif").value.replace(/\./g, ""); // Hilangkan titik sebelum dikirim

            try {
                const userResponse = await axios.post(`${apiUrl}/get-user-by-uid`, { uid });
                if (userResponse.data.status === "berhasil") {
                    const user = userResponse.data.user;
                    const saldoSebelumnya = user.saldo;

                    // Update saldo sebelumnya
                    document.getElementById("saldo-sebelumnya").textContent = formatNumber(saldoSebelumnya);

                    // Kurangi saldo
                    const response = await axios.post(`${apiUrl}/kurangi-saldo`, { uid, tarif });
                    if (response.data.status === "berhasil") {
                        const saldoSesudah = saldoSebelumnya - parseInt(tarif);

                        // Update saldo sesudah
                        document.getElementById("saldo-sesudah").textContent = formatNumber(saldoSesudah);

                        // Tampilkan pesan "Gerbang Dibuka"
                        showAlert("success", "Saldo berhasil di kurangi, Gerbang Dibuka");

                        // Tunggu 3 detik sebelum gerbang ditutup dan halaman di-refresh
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    }
                }
            } catch (error) {
                showAlert("danger", error.response?.data?.message || "Terjadi kesalahan");
            }
        });
    </script>

</body>

</html>
