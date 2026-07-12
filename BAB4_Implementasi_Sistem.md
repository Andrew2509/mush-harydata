# 4.1 Implementasi Sistem

Implementasi sistem merupakan tahap pengaplikasian hasil perancangan sistem yang telah disusun pada Bab III ke dalam bentuk baris kode program (*source code*). Subbab ini menjabarkan secara teknis mengenai implementasi *framework* Laravel, basis data MySQL, REST API, integrasi dengan API Tripay dan Digiflazz, mekanisme penerimaan *webhook*, serta penerapan sistem antrean *First-Come, First-Served* (FCFS).

---

## 4.1.1 Implementasi Framework Laravel

Platform *top-up game* ini dibangun menggunakan **Framework Laravel 12** sebagai landasan utama pengembangan backend. Laravel 12 menyediakan fitur-fitur modern untuk menangani logika bisnis, ORM (Eloquent), perutean (*routing*), validasi keamanan, serta pemrosesan antrean asinkron secara tangguh.

### 1. Struktur Model-View-Controller (MVC)
Laravel mengadopsi pola MVC untuk memisahkan logika data, antarmuka pengguna, dan alur kontrol bisnis:
* **Model**: Berfungsi merepresentasikan tabel basis data dan relasi antarentitas. Tersimpan dalam direktori `app/Models/` (contoh: [Pembelian.php](file:///e:/muslihinnnn%20(1)/harydata/app/Models/Pembelian.php), [Pembayaran.php](file:///e:/muslihinnnn%20(1)/harydata/app/Models/Pembayaran.php), [Layanan.php](file:///e:/muslihinnnn%20(1)/harydata/app/Models/Layanan.php)).
* **View**: Berfungsi menyajikan tampilan antarmuka pengguna. Diimplementasikan menggunakan Blade templating engine (`.blade.php`) yang terletak di direktori `resources/views/`.
* **Controller**: Berfungsi sebagai pengolah input dari pengguna, melakukan validasi, memproses logika transaksi, dan mengembalikan respon. Tersimpan dalam direktori `app/Http/Controllers/` (contoh: [OrderController.php](file:///e:/muslihinnnn%20(1)/harydata/app/Http/Controllers/OrderController.php), [TripayController.php](file:///e:/muslihinnnn%20(1)/harydata/app/Http/Controllers/TripayController.php)).

### 2. Routing
Sistem perutean bertindak sebagai pengatur lalu lintas HTTP request. Berkas [web.php](file:///e:/muslihinnnn%20(1)/harydata/routes/web.php) digunakan untuk mendefinisikan rute halaman utama website, dasbor admin, checkout, pencarian, dan *webhook callback* pihak ketiga.

Contoh pendaftaran rute penting pada berkas `routes/web.php`:
```php
// Rute untuk transaksi checkout
Route::post('/order', [OrderController::class, 'store'])->name('order');

// Rute untuk callback asinkron
Route::post('/tripay/callback', [TripayController::class, 'handleCallback']);
Route::post('/digi/callback/haryserver', [DigiflazzCallbackController::class, 'handle'])->name('digicallback');

// Rute untuk detail invoice pembeli
Route::get('/id/invoices/{order_id}', [InvoiceController::class, 'show']);
```

### 3. Controller
Controller bertanggung jawab mengkoordinasikan pertukaran data. Implementasi utama controller pada sistem meliputi:
* `BerandaController`: Mengambil katalog kategori game dan daftar produk aktif untuk disajikan di halaman beranda.
* `OrderController`: Memproses validasi input checkout, memeriksa voucher promo, menginisiasi transaksi pembayaran ke Tripay, dan menyimpan transaksi awal dengan status `Pending`.
* `TripayController`: Mengelola permintaan pembuatan *invoice* pembayaran closed payment ke Tripay, menerima webhook callback, melakukan pengecekan signature, dan memicu antrean top-up.
* `DigiflazzCallbackController`: Menangani status pengiriman produk digital dari Digiflazz, melakukan pembaruan status akhir transaksi (`Sukses`/`Gagal`), mengirim notifikasi WhatsApp, serta mengelola proses pengembalian dana (*refund*).

### 4. Model
Eloquent Model digunakan untuk interaksi basis data tanpa menulis query SQL mentah secara manual. 
Contoh model [Pembelian.php](file:///e:/muslihinnnn%20(1)/harydata/app/Models/Pembelian.php):
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $guarded = []; // Mengizinkan pengisian seluruh kolom (mass assignment)
}
```

### 5. Middleware
Middleware diimplementasikan untuk menyaring HTTP request sebelum masuk ke Controller. 
* `VerifyCsrfToken`: Laravel secara bawaan memproteksi rute POST menggunakan token CSRF. Pada rute webhook callback `/tripay/callback` dan `/digi/callback/haryserver`, token CSRF dikecualikan karena request dikirim secara langsung dari server luar (Tripay dan Digiflazz).
* `Signature Validation Middleware`: Validasi signature diimplementasikan pada tingkat pengendali callback untuk memeriksa keabsahan payload data.

### 6. Service
Pola Service (*Service Pattern*) diterapkan untuk mengisolasi logika pemanggilan API pihak ketiga. Hal ini diwujudkan dengan memisahkan pemanggilan HTTP REST API Digiflazz ke dalam fungsi `connect()` di dalam [DigiFlazzController.php](file:///e:/muslihinnnn%20(1)/harydata/app/Http/Controllers/digiFlazzController.php) menggunakan Laravel HTTP Client.

## 4.1.2 Struktur Folder Project Laravel
Gambar 4.1 menunjukkan struktur direktori proyek Laravel yang digunakan dalam pengembangan sistem. Direktori app berisi komponen utama aplikasi seperti Controller, Model, dan Job. Direktori routes digunakan untuk mendefinisikan rute aplikasi, sedangkan direktori resources digunakan untuk menyimpan tampilan antarmuka berbasis Blade Template. Selain itu, direktori database digunakan untuk menyimpan berkas migration dan seeders, sedangkan direktori public berfungsi sebagai direktori yang dapat diakses oleh pengguna melalui web browser.

---

**Gambar 4.1 Struktur Folder Project Laravel**

*(Diagram Interaktif: [Struktur Folder Laravel](file:///E:/muslihinnnn%20(1)/harydata/assets/diagram_folder_structure.html))*

*Penjelasan Gambar 4.1:*
Gambar 4.1 menunjukkan struktur direktori project Laravel yang terorganisir, di mana direktori `app/Http/Controllers` menyimpan berkas-berkas pengendali logika, `app/Jobs` menyimpan tugas antrean FCFS (`ProcessTopupJob.php`), `app/Models` menyimpan struktur relasi entitas, dan berkas rute didefinisikan pada direktori `routes/`.

---

## 4.1.3 Implementasi Database

Sistem basis data menggunakan **MySQL** dengan dukungan *storage engine* InnoDB untuk menjamin integritas relasi tabel data. Struktur database dibangun menggunakan berkas-berkas **Laravel Migration** untuk mempermudah eksekusi migrasi skema tabel di server produksi.

Perintah berikut digunakan untuk mengeksekusi berkas migrasi ke server basis data:
```bash
php artisan migrate
```

Struktur tabel utama yang diimplementasikan dijabarkan sebagai berikut:

### 1. Tabel `users`
Menyimpan data identitas administrator dan pengguna terdaftar untuk autentikasi sistem.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | Bigint (PK, Increment) | ID unik pengguna |
| `name` | Varchar(255) | Nama lengkap pengguna |
| `email` | Varchar(255) (Unique) | Alamat email terdaftar |
| `password` | Varchar(255) | Hash password terenkripsi |
| `balance` | Int | Saldo akun deposit (default: 0) |
| `created_at` | Timestamp | Waktu akun dibuat |

### 2. Tabel `layanans`
Menyimpan katalog nominal produk *top-up game* digital yang disinkronisasikan dari SKU Digiflazz.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | Bigint (PK, Increment) | ID unik produk |
| `kategori_id` | Int | ID relasi ke kategori game |
| `layanan` | Varchar(255) | Nama varian nominal produk |
| `provider_id` | Varchar(255) | Kode SKU produk dari Digiflazz |
| `harga` | Int | Harga jual produk kepada pembeli |
| `status` | Varchar(50) | Status keaktifan produk (active/inactive) |

### 3. Tabel `pembelians`
Mencatat detail setiap order transaksi top-up game beserta parameter pencatatan waktu pemrosesan antrean.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | Bigint (PK, Increment) | ID unik transaksi |
| `order_id` | Varchar(255) (Index) | Kode unik transaksi (Contoh: MSTP123) |
| `user_id` | Varchar(255) | User ID/Player ID akun game pembeli |
| `zone` | Varchar(255) | Zone ID/Server akun game pembeli |
| `nickname` | Varchar(255) | Nickname game pembeli setelah validasi |
| `layanan` | Varchar(255) | Nama produk yang dibeli |
| `harga` | Int | Total harga pembayaran |
| `provider_order_id` | Varchar(255) | ID referensi pesanan di Digiflazz / status antrean |
| `status` | Varchar(50) (Index) | Status pesanan (Pending, Paid, Proses, Sukses, Gagal) |
| `waktu_callback` | Timestamp | Waktu webhook callback Tripay diterima (Lunas) |
| `waktu_fulfillment` | Timestamp | Waktu webhook callback Digiflazz diterima (Sukses/Gagal) |

### 4. Tabel `webhook_logs`
Digunakan untuk mencatat log payload data webhook callback mentah yang masuk dari Tripay guna tujuan audit keamanan dan rekonsiliasi data jika terjadi selisih transaksi.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | Bigint (PK, Increment) | ID log webhook |
| `order_id` | Varchar(255) | ID pesanan yang berelasi |
| `payload` | Longtext | Payload JSON mentah dari provider |
| `status` | Varchar(50) | Status pemrosesan webhook (Success/Failed) |
| `created_at` | Timestamp | Waktu pencatatan log |

### 5. Tabel `jobs`
Digunakan sebagai media penyimpanan pesan antrean asinkron (database queue connection) untuk memproses FCFS.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | Bigint (PK, Increment) | ID unik baris pekerjaan (Queue ID) |
| `queue` | Varchar(255) (Index) | Nama channel antrean (Contoh: `topup`) |
| `payload` | Longtext | Serialized class job `ProcessTopupJob` |
| `attempts` | Tinyint | Jumlah percobaan eksekusi jika terjadi kegagalan |
| `created_at` | Int | Waktu antrean didaftarkan (UNIX timestamp) |

### 6. Tabel `failed_jobs`
Menyimpan riwayat antrean job yang gagal dieksekusi setelah melebihi batas maksimal percobaan (*attempts*).
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | Bigint (PK, Increment) | ID unik kesalahan antrean |
| `uuid` | Varchar(255) | UUID pesan kesalahan |
| `connection` | Text | Koneksi antrean (database) |
| `queue` | Text | Nama antrean |
| `payload` | Longtext | Data payload job yang gagal |
| `exception` | Longtext | Log trace pesan error PHP |
| `failed_at` | Timestamp | Waktu pencatatan kegagalan |

---

**Gambar 4.2 Tampilan Struktur Tabel Database pada phpMyAdmin**

*(Aset Gambar: [database_phpmyadmin.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/database_phpmyadmin.png))*

*Penjelasan Gambar 4.2:*
Gambar 4.2 menyajikan screenshot struktur basis data sistem pada antarmuka phpMyAdmin. Terlihat tabel `users`, `layanans`, `pembelians`, `webhook_logs`, `jobs`, dan `failed_jobs` terstruktur secara relasional lengkap dengan indeks utama pada kolom pencarian transaksi.

---

## 4.1.4 Implementasi REST API

REST API digunakan sebagai media integrasi data terstandarisasi. Implementasi REST API internal diatur melalui routing web Laravel dengan merespon data menggunakan format payload JSON, status HTTP yang tepat, serta parameter request/response yang spesifik.

### 1. Endpoint: `POST /checkout`
* **Deskripsi**: Dipanggil saat pengguna mengonfirmasi order untuk membuat tagihan transaksi pembayaran baru.
* **Request (JSON)**:
  ```json
  {
    "payment_method": "BRIVA",
    "nomor": "081234567890",
    "service_id": 14,
    "user_id": "12345678",
    "zone_id": "2001",
    "nickname": "MiyaLegends"
  }
  ```
* **Response (JSON - HTTP 200 OK)**:
  ```json
  {
    "status": true,
    "order_id": "MSTP123456",
    "amount": 52000,
    "payment_code": "80777081234567890",
    "checkout_url": "https://tripay.co.id/checkout/MSTP123456"
  }
  ```

### 2. Endpoint: `POST /callback/tripay`
* **Deskripsi**: Endpoint webhook asinkron yang menerima notifikasi pelunasan pembayaran dari server Tripay.
* **Request (Raw JSON - HTTP Headers: `X-Callback-Signature`)**:
  ```json
  {
    "reference": "T422650000001",
    "merchant_ref": "MSTP123456",
    "status": "PAID",
    "amount": 52000
  }
  ```
* **Response (JSON - HTTP 200 OK)**:
  ```json
  {
    "success": true,
    "message": "Payment verified and queued"
  }
  ```

### 3. Endpoint: `POST /callback/digiflazz`
* **Deskripsi**: Endpoint webhook asinkron yang menerima kabar pengiriman produk top-up dari Digiflazz.
* **Request (Raw JSON - HTTP Headers: `X-Hub-Signature`)**:
  ```json
  {
    "data": {
      "ref_id": "MSTP123456",
      "status": "Sukses",
      "sn": "9827-1829-1928-SN",
      "price": 49000
    }
  }
  ```
* **Response (JSON - HTTP 200 OK)**:
  ```json
  {
    "success": true,
    "message": "Invoice updated"
  }
  ```

### 4. Endpoint: `GET /invoice/{id}`
* **Deskripsi**: Mengambil status terkini transaksi pembayaran beserta detail kode bayar/barcode QRIS.
* **Response (JSON - HTTP 200 OK)**:
  ```json
  {
    "status": "Success",
    "invoice": {
      "order_id": "MSTP123456",
      "payment_status": "PAID",
      "delivery_status": "Proses",
      "payment_method": "QRIS",
      "payment_url": "https://tripay.co.id/qr/MSTP123456.png"
    }
  }
  ```

### 5. Endpoint: `GET /history`
* **Deskripsi**: Mengambil 10 data riwayat transaksi terakhir untuk dilacak oleh pengguna di halaman pelacakan pesanan.
* **Response (JSON - HTTP 200 OK)**:
  ```json
  {
    "status": true,
    "transactions": [
      {
        "created_at": "2026-07-10 18:00:00",
        "order_id": "MSTP123456",
        "layanan": "Mobile Legends 86 Diamonds",
        "harga": 21000,
        "status": "Sukses"
      }
    ]
  }
  ```

---

## 4.1.5 Implementasi FCFS Queue

Implementasi metode **First Come First Served (FCFS)** pada aplikasi Mustopup dilakukan menggunakan **Laravel Queue** dengan **database driver**. Setiap transaksi yang telah berhasil dibayar melalui *payment gateway* akan dimasukkan ke dalam antrean (*queue*) untuk diproses secara berurutan sesuai waktu kedatangannya. Pendekatan ini bertujuan untuk menghindari pemrosesan transaksi secara bersamaan serta menjaga konsistensi data.

### 4.1.5.1 Laravel Queue

Laravel Queue digunakan untuk menjalankan proses transaksi secara *asynchronous* sehingga proses *top-up* tidak dilakukan secara langsung pada saat pengguna melakukan pembayaran di thread utama HTTP request. Hal ini menjaga kinerja aplikasi tetap responsif.

**Gambar 4.3 Arsitektur Laravel Queue FCFS**

*(Aset Gambar: [laravel_queue_flow.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/laravel_queue_flow.png))*

*Penjelasan Gambar 4.3:*
Gambar 4.3 menampilkan diagram alur pemrosesan antrean *asynchronous* pada Laravel Queue, di mana permintaan dari klien didelegasikan ke *queue handler* untuk dieksekusi di latar belakang tanpa menahan waktu muat halaman pengguna.

---

### 4.1.5.2 Database Driver

Sistem menggunakan **database** sebagai *queue driver*. Seluruh antrean transaksi disimpan pada tabel `jobs` sehingga dapat diproses oleh *queue worker* secara berurutan sesuai prinsip FCFS. Konfigurasi ini diatur pada file konfigurasi `.env` dan `config/queue.php`.

```ini
QUEUE_CONNECTION=database
```

**Gambar 4.4 Konfigurasi Database Queue pada File .env**

*(Aset Gambar: [konfigurasi_database_queue.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/konfigurasi_database_queue.png))*

*Penjelasan Gambar 4.4:*
Gambar 4.4 menunjukkan baris kode pada file `.env` di mana parameter `QUEUE_CONNECTION` diatur ke nilai `database` agar Laravel menggunakan driver basis data sebagai media penyimpanan sementara antrean pekerjaan (*job*).

---

### 4.1.5.3 Supervisor

Supervisor digunakan untuk menjaga proses **queue worker** (`php artisan queue:work`) tetap aktif di latar belakang server Hostinger. Supervisor akan mendeteksi apabila *process worker* mengalami crash atau terhenti, lalu melakukan *restart* secara otomatis. 

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/u543440860/domains/mustopup.com/public_html/artisan queue:work database --queue=topup --sleep=3 --tries=3
autostart=true
autorestart=true
user=u543440860
numprocs=1
redirect_stderr=true
stdout_logfile=/home/u543440860/domains/mustopup.com/public_html/storage/logs/worker.log
```

**Gambar 4.5 Konfigurasi Supervisor Service**

*(Aset Gambar: [konfigurasi_supervisor.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/konfigurasi_supervisor.png))*

*Penjelasan Gambar 4.5:*
Gambar 4.5 menyajikan potongan file konfigurasi Supervisor daemon `/etc/supervisor/conf.d/laravel-worker.conf` yang menginstruksikan sistem operasi untuk terus menjalankan *queue worker* secara otomatis pada direktori `/home/u543440860/domains/mustopup.com/public_html/`.

Sebagai alternatif pada layanan *shared hosting* Hostinger yang tidak memiliki akses root untuk menginstal Supervisor, pemrosesan antrean dijaga secara berkala menggunakan fitur **Cron Job** pada hPanel dengan konfigurasi waktu eksekusi setiap menit sebagai berikut:

```bash
* * * * * php /home/u543440860/domains/mustopup.com/public_html/artisan queue:work --queue=topup --stop-when-empty
```

---

### 4.1.5.4 ProcessTopupJob

Kelas **ProcessTopupJob** (tersimpan di `app/Jobs/ProcessTopupJob.php`) berfungsi untuk memproses transaksi yang telah masuk ke antrean. Job ini bertanggung jawab mengirim permintaan *top-up* ke API Digiflazz serta memperbarui status transaksi menjadi `Proses`.

```php
public function handle()
{
    $pembelian = Pembelian::where('order_id', $this->orderId)->first();
    if (!$pembelian || $pembelian->status !== 'Paid') {
        return;
    }

    $pembelian->update([
        'status' => 'Proses',
        'provider_order_id' => $this->orderId,
    ]);

    $layanan = \App\Models\Layanan::where('layanan', $pembelian->layanan)->first();
    $skuCode = $layanan ? $layanan->provider_id : $pembelian->layanan;

    $digiFlazz = new digiFlazzController();
    $response = $digiFlazz->order(
        $pembelian->user_id,
        $pembelian->zone,
        $skuCode,
        $pembelian->order_id
    );

    $pembelian->update([
        'log' => json_encode($response)
    ]);

    Log::info("FCFS Queue Executed Order: " . $this->orderId);
}
```

**Gambar 4.6 Implementasi Metode handle() pada ProcessTopupJob**

*(Aset Gambar: [implementasi_process_topup_job.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/implementasi_process_topup_job.png))*

*Penjelasan Gambar 4.6:*
Gambar 4.6 menyajikan struktur kode logika internal pada fungsi `handle()` di berkas `ProcessTopupJob.php`. Kode ini memicu perubahan status ke `Proses` dan memanggil kelas `digiFlazzController` secara berurutan.

---

### 4.1.5.5 Dispatch Job

Setelah pembayaran berhasil diverifikasi melalui *webhook* Tripay pada `TripayController`, sistem menjalankan proses **dispatch job** untuk memasukkan transaksi ke dalam antrean Laravel Queue dengan spesifikasi channel `topup`.

```php
// FCFS Queue Dispatching
\App\Jobs\ProcessTopupJob::dispatch($order_id)->onQueue('topup');
```

**Gambar 4.7 Dispatching ProcessTopupJob pada TripayController**

*(Aset Gambar: [dispatch_job_code.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/dispatch_job_code.png))*

*Penjelasan Gambar 4.7:*
Gambar 4.7 menampilkan baris program pada `TripayController.php` tempat dipicunya fungsi `dispatch()` untuk mengirim kode unik transaksi (`order_id`) ke antrean basis data segera setelah invoice berstatus lunas.

---

### 4.1.5.6 Worker Queue

*Queue worker* bertugas mengambil transaksi dari tabel **jobs** dan memprosesnya satu per satu sesuai urutan antrean. Apabila terjadi kegagalan selama pemrosesan transaksi setelah 3 kali percobaan (*tries*), data transaksi akan dipindahkan ke tabel **failed_jobs** untuk proses analisis lanjut.

**Gambar 4.8 Eksekusi Queue Worker pada Terminal**

*(Aset Gambar: [terminal_queue_worker.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/terminal_queue_worker.png))*

*Penjelasan Gambar 4.8:*
Gambar 4.8 menunjukkan output terminal dari perintah `php artisan queue:work` yang memproses transaksi-transaksi top-up yang mengantre satu per satu secara serial sesuai urutan pendaftarannya.

**Gambar 4.9 Data Pekerjaan pada Tabel jobs**

*(Aset Gambar: [tabel_jobs.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/tabel_jobs.png))*

*Penjelasan Gambar 4.9:*
Gambar 4.9 memperlihatkan data antrean aktif pada tabel `jobs` di database MySQL. Baris-baris ini direpresentasikan sebagai antrean yang menunggu giliran untuk dieksekusi berdasarkan nilai kolom `id` auto-increment.

**Gambar 4.10 Data Kesalahan pada Tabel failed_jobs**

*(Aset Gambar: [tabel_failed_jobs.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/tabel_failed_jobs.png))*

*Penjelasan Gambar 4.10:*
Gambar 4.10 menunjukkan baris data kesalahan pada tabel `failed_jobs` basis data MySQL. Tabel ini mencatat log pengecualian (*exception error*) serta payload data asli transaksi yang gagal dipenuhi.

**Gambar 4.11 Aliran Log Transaksi Diproses Secara FIFO**

*(Aset Gambar: [log_fifo_transactions.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/log_fifo_transactions.png))*

*Penjelasan Gambar 4.11:*
Gambar 4.11 memperlihatkan log pencatatan transaksi di mana urutan eksekusi order (berdasarkan timestamp callback `waktu_callback`) bersesuaian dengan urutan keberangkatan request pengisian top-up ke Digiflazz (FIFO) tanpa ada transaksi yang saling mendahului.
---

### 4.1.5.7 Simulasi Algoritma FCFS (Disk Scheduling)

Untuk membuktikan keabsahan perhitungan algoritma **FCFS (First-Come, First-Served)** secara matematis dan prosedural kepada penguji, sistem dilengkapi dengan halaman **Simulasi FCFS Disk Scheduling** pada dasbor administrator. Halaman ini berfungsi mensimulasikan pergerakan *read/write head* piringan disk berdasarkan antrean nomor silinder (*track requests*) yang masuk.

Logika perhitungan diimplementasikan menggunakan bahasa pemrograman PHP pada berkas [FcfsSimulationController.php](file:///e:/muslihinnnn%20(1)/harydata/app/Http/Controllers/Admin/FcfsSimulationController.php):

```php
$totalHeadMovement = 0;
$currentHead = $head;
$steps = [];
$seekSequence = [$head];

foreach ($sequence as $index => $req) {
    $diff = abs($req - $currentHead);
    $steps[] = [
        'step' => $index + 1,
        'from' => $currentHead,
        'to' => $req,
        'diff' => $diff
    ];
    $totalHeadMovement += $diff;
    $currentHead = $req;
    $seekSequence[] = $req;
}
```

Halaman simulasi ini menerima masukan berupa posisi awal head dan deretan antrean silinder (misalnya `98, 183, 37, 122, 14, 124, 65, 67` dengan posisi awal head `53`). Sistem kemudian memproses selisih absolut secara berurutan dan menghasilkan total pergerakan head sebesar `640` silinder beserta visualisasi grafiknya menggunakan *ApexCharts*.

**Gambar 4.12 Halaman Input Parameter Simulasi FCFS**

*(Aset Gambar: [fcfs_simulation_input.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/fcfs_simulation_input.png))*

*Penjelasan Gambar 4.12:*
Gambar 4.12 menampilkan antarmuka halaman simulasi FCFS pada dasbor admin. Halaman ini menyediakan form input posisi head awal dan deretan angka antrean silinder yang dipisahkan tanda koma.

**Gambar 4.13 Grafik Seek Trajectory Hasil Perhitungan FCFS**

*(Aset Gambar: [fcfs_simulation_chart.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/fcfs_simulation_chart.png))*

*Penjelasan Gambar 4.13:*
Gambar 4.13 menampilkan grafik garis pergerakan head (*seek trajectory*) yang dihasilkan secara dinamis menggunakan ApexCharts berdasarkan hasil kalkulasi urutan pemrosesan antrean FCFS.

---

## 4.1.6 Implementasi Integrasi Tripay

Integrasi Tripay diimplementasikan pada [TripayController.php](file:///e:/muslihinnnn%20(1)/harydata/app/Http/Controllers/TripayController.php) untuk mengotomatisasi pembuatan tagihan belanja pembayaran secara *real-time*.

### 1. Proses Pembuatan Invoice
Saat pembeli mengirim request order via `OrderController`, sistem akan mengambil data detail produk dan total nominal harga. Data pembeli kemudian disusun dan dikirim ke server Tripay menggunakan request HTTP POST.

### 2. Pengiriman Request API
Request dikirim ke URL Sandbox atau Production Tripay. Autentikasi menggunakan header *Bearer Token API Key*. Tanda tangan digital (*Signature*) dibuat menggunakan algoritma HMAC-SHA256 untuk menjamin integritas nominal transaksi.

Formulasi *signature* pembuatan transaksi:
```php
$signature = hash_hmac('sha256', $merchantCode . $ref_id . $amount, $privateKey);
```

### 3. Response API (QRIS dan Virtual Account)
Response sukses dari API Tripay memuat parameter penting seperti `qr_url` (untuk metode pembayaran QRIS) atau `pay_code` / `nomor_va` (untuk metode Virtual Account transfer bank). Nilai ini disimpan ke dalam tabel `pembayarans` untuk ditampilkan kepada pembeli.

---

**Gambar 4.14 Screenshot Transaksi Pembuatan Invoice di Tripay**

*(Aset Gambar: [transaksi_tripay.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/transaksi_tripay.png))*

*Penjelasan Gambar 4.14:*
Gambar 4.14 merupakan screenshot dashboard merchant Tripay yang menampilkan data invoice transaksi pembayaran. Halaman ini menunjukkan status transaksi lunas (*PAID*), kode referensi unik Tripay, metode transfer bank VA, dan detail jumlah tagihan pembayaran.

---

## 4.1.7 Implementasi Integrasi Digiflazz

Digiflazz diimplementasikan pada berkas [digiFlazzController.php](file:///e:/muslihinnnn%20(1)/harydata/app/Http/Controllers/digiFlazzController.php) untuk mengirimkan item *virtual game* digital secara otomatis ketika order dinyatakan lunas.

### 1. Request Topup
Request dikirim dengan format payload JSON menggunakan HTTP client Laravel POST menuju alamat URL `https://api.digiflazz.com/v1/transaction`.

### 2. Parameter Utama Digiflazz:
* `buyer_sku_code` (SKU): Kode produk game yang akan dipesan (Contoh: `ML86`).
* `customer_no` (Customer No): Nomor target pengiriman produk game, di mana ID Pemain digabung langsung dengan ID Server/Zone game (Contoh: `123456782001`).
* `ref_id` (Reference ID): Kode unik transaksi lokal (`order_id`) untuk mencegah duplikasi order ganda (*idempotency*).
* `sign` (Signature): Enkripsi pengenal transaksi menggunakan MD5 dengan struktur:
  ```php
  $sign = md5($username_digi . $api_key_digi . $ref_id);
  ```

### 3. Response Digiflazz
API Digiflazz akan mengembalikan response JSON berisi parameter `status` awal transaksi (biasanya bernilai `Pending` karena transaksi sedang dikirimkan ke server game) serta sisa saldo akun merchant Digiflazz.

---

**Gambar 4.15 Screenshot Response Transaksi H2H Digiflazz**

*(Aset Gambar: [response_digiflazz.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/response_digiflazz.png))*

*Penjelasan Gambar 4.15:*
Gambar 4.15 menyajikan screenshot data response mentah (JSON) dari server Digiflazz. Data memuat parameter status pesanan `Success` lengkap dengan pencantuman *Serial Number* (SN) pengiriman token item game ke akun pembeli.

---

## 4.1.8 Implementasi Webhook

Webhook diimplementasikan untuk menangani notifikasi asinkron dari Tripay dan Digiflazz saat terjadi perubahan status transaksi secara instan.

### 1. Callback Tripay
Saat pembeli menyelesaikan pembayaran di aplikasi dompet digital atau mobile banking, server Tripay akan mengirim request callback POST ke URL `/tripay/callback`.
* Webhook memvalidasi header `HTTP_X_CALLBACK_SIGNATURE` secara lokal menggunakan raw payload JSON and *private key* Tripay melalui HMAC-SHA256.
* Jika valid and bernilai `PAID`, status pembayaran diubah menjadi lunas, sistem mengirim pesan notifikasi WhatsApp, dan mendaftarkan pekerjaan pengisian saldo game ke antrean FCFS Laravel Queue.

### 2. Callback Digiflazz
Saat transaksi top-up selesai dikirimkan ke server game oleh Digiflazz, server Digiflazz akan mengirim callback POST ke `/digi/callback/haryserver` dengan membawa header parameter `X-Hub-Signature` (HMAC-SHA1).
* Jika status update bernilai `Sukses`, status pembelian diubah menjadi `Sukses` pada database, dan notifikasi nomor voucher/Serial Number (SN) dikirimkan via WhatsApp.
* Jika status update bernilai `Gagal`, sistem memicu metode `handleRefund()` untuk melakukan pengembalian saldo balance kepada akun pengguna terdaftar, atau mencatat log pada tabel `pending_refunds` untuk guest user agar dana dapat direfund manual oleh admin.

---

**Gambar 4.16 Screenshot Payload Webhook Callback**

*(Aset Gambar: [payload_webhook.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/payload_webhook.png))*

*Penjelasan Gambar 4.16:*
Gambar 4.16 menyajikan screenshot payload data callback webhook yang diterima secara asinkron dari Tripay dan Digiflazz. Payload ini membawa data status final pembayaran dan Serial Number pengiriman voucher game yang sukses diproses.
