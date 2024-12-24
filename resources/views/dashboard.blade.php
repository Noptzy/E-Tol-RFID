<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard E-TOL</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
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
</body>

</html>
