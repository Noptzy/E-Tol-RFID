## Langkah-langkah Setup Proyek E-Tol-RFID

### 1. **Clone Repository**
   Jalankan perintah berikut untuk meng-clone repository dari GitHub:
   ```bash
   git clone https://github.com/Noptzy/E-Tol-RFID.git
   ```

### 2. **Install Composer Dependencies**
   Pastikan Composer sudah terinstall di sistem Anda. Kemudian jalankan perintah berikut untuk menginstall dependensi yang diperlukan:
   ```bash
   composer install
   ```

### 3. **Copy File `.env`**
   Salin file `.env.example` menjadi `.env` untuk konfigurasi environment:
   ```bash
   cp .env.example .env
   ```

### 4. **Ubah Nama Database di `.env`**
   Buka file `.env` dan ubah konfigurasi database sesuai dengan pengaturan database yang Anda gunakan:
   ```env
   DB_DATABASE=nama_database
   DB_USERNAME=nama_pengguna
   DB_PASSWORD=password
   ```

### 5. **Cek IP Address**
   Gunakan perintah `ipconfig` di Command Prompt (CMD) untuk mengetahui alamat IPv4:
   ```bash
   ipconfig
   ```
   Temukan bagian **IPv4 Address** dan salin alamat IP yang muncul.

### 6. **Jalankan Server Laravel**
   Jalankan server Laravel menggunakan perintah berikut, ganti `{ipmu}` dengan alamat IP yang Anda salin pada langkah sebelumnya:
   ```bash
   php artisan serve --host={ipmu} --port=8080
   ```

### 7. **Update IP dan Port di `dashboard.php`**
   Buka file `dashboard.php` dan cari variabel `apiUrl`. Ubah nilai `apiUrl` dengan alamat IP dan port yang sesuai (misalnya, `http://{ipmu}:8080`).

### 8. **Update IP di Code Arduino**
   Pada kode Arduino, cari variabel `serverName` dan ubah dengan IP yang Anda gunakan di langkah sebelumnya.

### 9. **Ubah SSID dan Password di Code Arduino**
   Sesuaikan SSID dan password Wi-Fi di kode Arduino agar dapat terhubung ke jaringan yang diinginkan.

### 10. **Tap RFID dan Salin UID**
   Ketika kartu RFID berhasil di-tap, UID kartu akan muncul. Salin UID yang ditampilkan.

### 11. **Tambah User di Laravel**
   Masuk ke halaman admin Laravel, buka halaman **users**, dan tambahkan user baru menggunakan UID yang telah disalin sebelumnya. Anda juga dapat mengubah informasi pengguna sesuai kebutuhan.

### 12. **Verifikasi**
   Hasilnya dapat dilihat di halaman awal aplikasi, pastikan semua berjalan sesuai harapan.

## Pengkabelan RFID

Berikut adalah pinout yang digunakan untuk menghubungkan modul RFID dengan NodeMCU:

| **Pin RFID** | **Pin NodeMCU** |
|--------------|-----------------|
| 3.3V PIN     | 3.3V PIN        |
| RST PIN      | D2              |
| GND PIN      | GND PIN         |
| MISO PIN     | D6              |
| MOSI PIN     | D7              |
| SCK PIN      | D5              |
| SDA PIN      | D4              |

