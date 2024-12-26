<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>RFID Saldo Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
=======
    <title>Dashboard E-TOL</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
>>>>>>> 92dede23f7465d5cdf94469304be9379de1ab580
</head>

<body>
    <div class="container mt-5">
<<<<<<< HEAD
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

=======
        <h1 class="mb-4">Dashboard E-TOL</h1>
        <div id="alert-container"></div>
        <div id="user-info" class="card mb-4 d-none">
            <div class="card-body">
                <h5 class="card-title">Informasi Pengguna</h5>
                <p><strong>UID:</strong> <span id="user-uid"></span></p>
                <p><strong>Nama:</strong> <span id="user-name"></span></p>
                <p><strong>Saldo:</strong> Rp <span id="user-saldo"></span></p>
            </div>
        </div>
        <form id="deduct-saldo-form" class="d-none">
            <input type="hidden" id="deduct-uid" name="uid">
            <div class="mb-3">
                <label for="amount" class="form-label">Kurangi Saldo (Rp)</label>
                <input type="number" class="form-control" id="amount" name="amount"
                    placeholder="Masukkan jumlah pengurangan">
            </div>
            <button type="submit" class="btn btn-danger">Kurangi Saldo</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            function formatCurrency(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            function fetchUserData(uid) {
                $.post("{{ route('api.findUserAjax') }}", {
                        uid: uid,
                        _token: "{{ csrf_token() }}"
                    })
                    .done(function(response) {
                        $('#user-info').removeClass('d-none');
                        $('#deduct-saldo-form').removeClass('d-none');
                        $('#alert-container').html('');
                        $('#user-uid').text(response.data.uid);
                        $('#user-name').text(response.data.nama);
                        $('#user-saldo').text(formatCurrency(response.data.saldo));
                        $('#deduct-uid').val(response.data.uid);
                    })
                    .fail(function(xhr) {
                        const error = xhr.responseJSON.message || 'Terjadi kesalahan.';
                        $('#alert-container').html('<div class="alert alert-danger">' + error + '</div>');
                        $('#user-info').addClass('d-none');
                        $('#deduct-saldo-form').addClass('d-none');
                    });
            }

            $('#deduct-saldo-form').on('submit', function(e) {
                e.preventDefault();
                const uid = $('#deduct-uid').val();
                const amount = $('#amount').val();

                $.post("{{ route('api.deductSaldo') }}", {
                        uid: uid,
                        amount: amount,
                        _token: "{{ csrf_token() }}"
                    })
                    .done(function(response) {
                        $('#alert-container').html('<div class="alert alert-success">' + response.message + '</div>');
                        $('#user-saldo').text(formatCurrency(response.data.saldo)); 
                    })
                    .fail(function(xhr) {
                        const error = xhr.responseJSON.message || 'Terjadi kesalahan.';
                        $('#alert-container').html('<div class="alert alert-danger">' + error + '</div>');
                    });
            });

            setInterval(function() {
                const uid = "44F62C3";
                fetchUserData(uid);
            }, 3000); 
        });
    </script>
>>>>>>> 92dede23f7465d5cdf94469304be9379de1ab580
</body>

</html>
