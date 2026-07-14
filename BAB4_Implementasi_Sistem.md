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

Implementasi metode **First Come First Served (FCFS)** pada aplikasi Mustopup dilakukan menggunakan **Laravel Queue** dengan **database driver**. Setiap transaksi yang telah berhasil dibayar melalui *payment gateway* Tripay akan dimasukkan ke dalam antrean (*queue*) untuk diproses secara berurutan berdasarkan waktu transaksi diterima. Mekanisme ini memastikan setiap transaksi diproses sesuai prinsip **First Come First Served (FCFS)** sehingga tidak terjadi pemrosesan secara bersamaan yang dapat menyebabkan inkonsistensi data atau kegagalan API *rate-limit* pada provider Digiflazz.

---

### 4.1.5.1 Implementasi Laravel Queue

Laravel Queue digunakan untuk menjalankan proses *top-up* secara **asynchronous**. Setelah pembayaran berhasil diverifikasi oleh *webhook callback* Tripay, sistem tidak langsung mengirim permintaan pengisian produk game ke Digiflazz di dalam siklus HTTP request utama, tetapi mendaftarkannya terlebih dahulu ke antrean. Pendekatan ini memisahkan proses berat pengisian saldo game ke latar belakang sehingga pengguna memperoleh respons transaksi sukses dengan cepat tanpa perlu menunggu proses integrasi API provider yang memakan waktu.

**Gambar 4.3 Struktur Berkas Implementasi Queue**

*(Aset Gambar: [diagram_queue_implementation.html](file:///E:/muslihinnnn%20(1)/harydata/assets/diagram_queue_implementation.html))*

*Penjelasan Gambar 4.3:*
Gambar 4.3 menunjukkan struktur direktori dan letak file job `ProcessTopupJob.php` di dalam folder `app/Jobs/` yang bertindak sebagai pemroses pekerjaan di latar belakang aplikasi secara asinkron.

---

### 4.1.5.2 Konfigurasi Database Driver

Untuk mengaktifkan antrean berbasis basis data, driver Laravel Queue dialihkan dari mode `sync` (default sinkron) menjadi `database`. Konfigurasi ini diatur dengan mengubah nilai parameter `QUEUE_CONNECTION` pada berkas `.env` aplikasi.

```ini
QUEUE_CONNECTION=database
```

**Gambar 4.4 Konfigurasi Driver Antrean pada Berkas .env**

*(Aset Gambar: [konfigurasi_database_queue.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/konfigurasi_database_queue.png))*

*Penjelasan Gambar 4.4:*
Gambar 4.4 menyajikan screenshot isi berkas konfigurasi `.env` aplikasi Mustopup yang mengaktifkan koneksi driver antrean menggunakan database MySQL.

Konfigurasi ini memastikan bahwa setiap transaksi lunas yang didelegasikan ke queue akan disimpan terlebih dahulu ke dalam tabel basis data bernama `jobs` sebelum dieksekusi secara serial oleh queue worker.

---

### 4.1.5.3 Implementasi ProcessTopupJob

Logika utama FCFS dideklarasikan di dalam kelas **ProcessTopupJob** pada berkas `app/Jobs/ProcessTopupJob.php`. Kelas ini memuat metode `handle()` yang bertugas memvalidasi kelayakan transaksi, mengunci status menjadi `Proses`, mengirim request API top-up ke `DigiFlazzController`, dan menangkap serta melempar pengecualian (*exception error*) jika response API menyatakan kegagalan.

```php
public function handle()
{
    Log::info("Queue Started: Memulai pemrosesan antrean untuk Order ID: " . $this->orderId);

    $pembelian = Pembelian::where('order_id', $this->orderId)->first();
    if (!$pembelian || $pembelian->status !== 'Paid') {
        Log::warning("Order ditolak dari antrean. Status bukan 'Paid' atau data tidak ada: " . $this->orderId);
        return;
    }

    $pembelian->update([
        'status' => 'Proses',
        'provider_order_id' => $this->orderId,
    ]);
    Log::info("Processing Order: Status transaksi " . $this->orderId . " diubah ke 'Proses'.");

    $layanan = \App\Models\Layanan::where('layanan', $pembelian->layanan)->first();
    $skuCode = $layanan ? $layanan->provider_id : $pembelian->layanan;

    $digiFlazz = new DigiFlazzController();
    $response = $digiFlazz->order(
        $pembelian->user_id,
        $pembelian->zone,
        $skuCode,
        $pembelian->order_id
    );

    $pembelian->update([
        'log' => json_encode($response)
    ]);

    if (empty($response) || !isset($response['data'])) {
        Log::error("Order Failed: Koneksi/Response API Digiflazz tidak valid untuk Order ID: " . $this->orderId);
        throw new \Exception("Response dari Digiflazz tidak valid atau kosong: " . json_encode($response));
    }

    $statusResponse = $response['data']['status'] ?? 'Gagal';
    if ($statusResponse === 'Gagal') {
        Log::error("Order Failed: Pengiriman ke Digiflazz GAGAL untuk Order ID: " . $this->orderId);
        throw new \Exception("Transaksi Digiflazz Gagal: " . ($response['data']['message'] ?? 'Unknown Error'));
    }

    Log::info("Order Success: Request order berhasil terkirim ke Digiflazz untuk Order ID: " . $this->orderId);
}
```

**Gambar 4.5 Logika Pemrosesan Transaksi pada ProcessTopupJob**

*(Aset Gambar: [implementasi_process_topup_job.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/implementasi_process_topup_job.png))*

*Penjelasan Gambar 4.5:*
Gambar 4.5 memperlihatkan potongan kode program metode `handle()` pada berkas `ProcessTopupJob.php` yang menguji keabsahan transaksi, memicu API Digiflazz, dan menangani pelemparan error jika transaksi bermasalah.

---

### 4.1.5.4 Dispatch Job

Ketika sistem menerima notifikasi asinkron callback pembayaran lunas dari Tripay dengan parameter status bernilai `PAID`, sistem akan langsung memanggil perintah `dispatch` untuk mendaftarkan transaksi tersebut ke dalam saluran antrean khusus bernama `topup`.

```php
// Mendaftarkan transaksi ke antrean FCFS (FIFO channel 'topup')
\App\Jobs\ProcessTopupJob::dispatch($order_id)->onQueue('topup');
```

**Gambar 4.6 Pemanggilan Dispatch Job pada TripayController**

*(Aset Gambar: [dispatch_job_code.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/dispatch_job_code.png))*

*Penjelasan Gambar 4.6:*
Gambar 4.6 menampilkan baris program `TripayController.php` tempat dipicunya fungsi `dispatch()` untuk meluncurkan transaksi dengan ID tertentu ke antrean segera setelah status pembayaran berstatus `Paid`.

---

### 4.1.5.5 Queue Worker

*Queue Worker* dijalankan melalui CLI dengan perintah `php artisan queue:work`. Perintah ini memicu loop tak berujung (*daemon process*) yang memantau tabel `jobs` secara real-time. Worker mengambil job satu per satu berurutan menaik berdasarkan ID terendah (`ORDER BY id ASC`), yang merepresentasikan transaksi terlama yang masuk terlebih dahulu, memenuhinya, dan menghapusnya jika berhasil atau memindahkannya ke tabel `failed_jobs` jika gagal.

**Gambar 4.7 Aktivitas Queue Worker pada Terminal CLI**

*(Aset Gambar: [terminal_queue_worker.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/terminal_queue_worker.png))*

*Penjelasan Gambar 4.7:*
Gambar 4.7 menyajikan screenshot terminal yang menampilkan output pengerjaan transaksi oleh `php artisan queue:work --queue=topup` yang diproses secara berurutan satu per satu.

---

### 4.1.5.6 Hasil Implementasi FCFS Queue

Hasil implementasi FCFS Queue terekam secara sistematis pada basis data relasional. Tabel `jobs` menyimpan antrean yang aktif mengantre, sedangkan tabel `failed_jobs` menyimpan data antrean yang gagal beserta dengan pesan kesalahannya (*exception log*).

**Gambar 4.8 Data Antrean Aktif pada Tabel jobs**

*(Aset Gambar: [tabel_jobs.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/tabel_jobs.png))*

*Penjelasan Gambar 4.8:*
Gambar 4.8 menunjukkan tabel `jobs` di phpMyAdmin yang berisi data payload serial transaksi lunas yang mengantre menunggu eksekusi dari worker.

**Gambar 4.9 Data Log Pekerjaan Gagal pada Tabel failed_jobs**

*(Aset Gambar: [tabel_failed_jobs.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/tabel_failed_jobs.png))*

*Penjelasan Gambar 4.9:*
Gambar 4.9 menyajikan screenshot tabel `failed_jobs` yang mengisolasi transaksi gagal beserta log rincian penyebab error agar tidak menyumbat antrean FCFS transaksi sehat lainnya.

**Gambar 4.10 Aliran Log Transaksi Diproses Secara FIFO**

*(Aset Gambar: [log_fifo_transactions.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/log_fifo_transactions.png))*

*Penjelasan Gambar 4.10:*
Gambar 4.10 menyajikan log aplikasi pada `laravel.log` yang membuktikan bahwa pemrosesan antrean (`Queue Started`, `Processing Order`, `Order Success`) dilakukan secara berurutan sesuai urutan kedatangan transaksi (*First Come First Served*).

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

**Gambar 4.11 Halaman Input Parameter Simulasi FCFS**

*(Aset Gambar: [fcfs_simulation_input.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/fcfs_simulation_input.png))*

*Penjelasan Gambar 4.11:*
Gambar 4.11 menampilkan antarmuka halaman simulasi FCFS pada dasbor admin. Halaman ini menyediakan form input posisi head awal dan deretan angka antrean silinder yang dipisahkan tanda koma.

**Gambar 4.12 Grafik Seek Trajectory Hasil Perhitungan FCFS**

*(Aset Gambar: [fcfs_simulation_chart.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/fcfs_simulation_chart.png))*

*Penjelasan Gambar 4.12:*
Gambar 4.12 menampilkan grafik garis pergerakan head (*seek trajectory*) yang dihasilkan secara dinamis menggunakan ApexCharts berdasarkan hasil kalkulasi urutan pemrosesan antrean FCFS.

---

## 4.1.6 Implementasi Keamanan API

Implementasi keamanan API bertujuan untuk melindungi komunikasi data antara aplikasi Mustopup dengan layanan pihak ketiga. Mekanisme keamanan diterapkan untuk memastikan bahwa setiap *request* dan *webhook callback* berasal dari sumber yang valid serta mencegah manipulasi data transaksi.

---

### 4.1.6.1 Signature Validation

Setiap *webhook callback* dari Tripay akan melalui proses validasi *signature*. Validasi dilakukan dengan membandingkan nilai *signature* yang diterima dari Tripay dengan hasil perhitungan *signature* yang dibuat oleh sistem. Apabila kedua nilai tersebut sama, maka *request* dinyatakan valid dan dapat diproses lebih lanjut.

**Gambar 4.13 Validasi Signature**

*(Aset Gambar: [validasi_signature_code.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/validasi_signature_code.png))*

*Penjelasan Gambar 4.13:*
Gambar 4.13 menampilkan kode program pembandingan signature asinkron yang dikirim oleh server Tripay dengan signature yang dikalkulasikan secara lokal pada server Mustopup.

---

### 4.1.6.2 HMAC-SHA256

Proses validasi *signature* menggunakan algoritma **HMAC-SHA256** dengan memanfaatkan *Private Key* yang diberikan oleh Tripay. Algoritma ini menghasilkan nilai *hash* berbasis kunci rahasia (*secret-key*) berdasarkan *raw JSON payload* sehingga integritas data dapat terjaga selama proses transmisi data.

Contoh implementasi:
```php
$localSignature = hash_hmac(
    'sha256',
    $rawJsonPayload,
    $privateKey
);
```

**Gambar 4.14 Implementasi HMAC-SHA256**

*(Aset Gambar: [implementasi_hmac_sha256.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/implementasi_hmac_sha256.png))*

*Penjelasan Gambar 4.14:*
Gambar 4.14 menyajikan penulisan fungsi enkripsi `hash_hmac` bermode SHA256 untuk memproses pencocokan integritas payload data callback.

---

### 4.1.6.3 IP Whitelist

Sistem Mustopup memanfaatkan fitur **IP Whitelist** untuk menyaring alamat IP pengirim callback. Setiap *request* yang masuk akan diperiksa berdasarkan alamat IP asalnya. Hanya alamat IP Tripay dan Digiflazz resmi yang telah terdaftar dalam basis data yang diperbolehkan mengakses endpoint callback, sedangkan alamat IP asing lainnya akan langsung ditolak oleh sistem.

**Gambar 4.15 Middleware Whitelist IP**

*(Aset Gambar: [middleware_whitelist_ip.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/middleware_whitelist_ip.png))*

*Penjelasan Gambar 4.15:*
Gambar 4.15 menampilkan potongan kelas middleware `WhitelistIp` yang menarik daftar IP resmi dari tabel `whitelisted_ips` di database MySQL.

---

### 4.1.6.4 Middleware

Middleware digunakan sebagai lapisan keamanan tambahan (*security wrapper*) sebelum *request* diteruskan ke *controller*. Middleware bertugas melakukan pemeriksaan kecocokan alamat IP pengirim secara asinkron sehingga hanya request dari IP terverifikasi yang bisa menyentuh logika controller utama.

**Gambar 4.16 Implementasi Middleware**

*(Aset Gambar: [registrasi_middleware_kernel.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/registrasi_middleware_kernel.png))*

*Penjelasan Gambar 4.16:*
Gambar 4.16 menunjukkan pendaftaran alias middleware keamanan `'whitelist.ip'` pada berkas `app/Http/Kernel.php` di Laravel.

---

### 4.1.6.5 Validasi Callback

Setelah proses validasi signature dan IP berhasil dilewati, sistem memeriksa data *callback* yang diterima, seperti **merchant_ref**, **reference**, dan **status pembayaran**. Apabila status pembayaran bernilai **PAID**, sistem akan memperbarui status transaksi pada basis data dan mengirim *job* ke Laravel Queue untuk diproses.

**Gambar 4.17 Validasi Callback**

*(Aset Gambar: [controller_callback_tripay.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/controller_callback_tripay.png))*

*Penjelasan Gambar 4.17:*
Gambar 4.17 menyajikan kode penanganan parameter webhook Tripay pada `TripayController` yang meluncurkan proses antrean jika status bernilai lunas.

---

### 4.1.6.6 Log Keamanan

Setiap *request* yang gagal melewati proses validasi signature atau terblokir oleh middleware akan dicatat ke dalam **laravel.log** sebagai log keamanan. Informasi yang dicatat meliputi waktu kejadian, alamat IP pengirim, dan pesan kesalahan. Log tersebut membantu administrator dalam melakukan pemantauan serta analisis apabila terjadi percobaan akses yang tidak sah.

**Gambar 4.18 Log Keamanan**

*(Aset Gambar: [log_keamanan_api.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/log_keamanan_api.png))*

*Penjelasan Gambar 4.18:*
Gambar 4.18 menampilkan log kesalahan `Tripay Callback: Invalid signature` lengkap dengan alamat IP pengirim di dalam file log sistem `storage/logs/laravel.log`.

---

### 4.1.6.7 Hasil Validasi

Hasil pengujian menunjukkan bahwa mekanisme keamanan API berhasil memvalidasi setiap *request* yang masuk. *Request* dengan *signature* yang valid diproses oleh sistem, sedangkan *request* yang tidak valid ditolak dengan respon **HTTP 403 Forbidden** atau **HTTP 400 Bad Request** sehingga dapat mencegah manipulasi data transaksi.

**Gambar 4.19 Hasil Validasi API**

*(Aset Gambar: [security_403_response.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/security_403_response.png))*

*Penjelasan Gambar 4.19:*
Gambar 4.19 menyajikan respon penolakan akses (HTTP 403 Forbidden) ketika endpoint callback diakses secara ilegal oleh IP asing di luar whitelist.

---

## 4.1.7 Implementasi Integrasi Tripay

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

**Gambar 4.20 Screenshot Transaksi Pembuatan Invoice di Tripay**

*(Aset Gambar: [transaksi_tripay.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/transaksi_tripay.png))*

*Penjelasan Gambar 4.20:*
Gambar 4.20 merupakan screenshot dashboard merchant Tripay yang menampilkan data invoice transaksi pembayaran. Halaman ini menunjukkan status transaksi lunas (*PAID*), kode referensi unik Tripay, metode transfer bank VA, dan detail jumlah tagihan pembayaran.

---

## 4.1.8 Implementasi Integrasi Digiflazz

Digiflazz diimplementasikan pada berkas [DigiFlazzController.php](file:///e:/muslihinnnn%20(1)/harydata/app/Http/Controllers/DigiFlazzController.php) untuk mengirimkan item *virtual game* digital secara otomatis ketika order dinyatakan lunas.

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

**Gambar 4.21 Screenshot Response Transaksi H2H Digiflazz**

*(Aset Gambar: [response_digiflazz.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/response_digiflazz.png))*

*Penjelasan Gambar 4.21:*
Gambar 4.21 menyajikan screenshot data response mentah (JSON) dari server Digiflazz. Data memuat parameter status pesanan `Success` lengkap dengan pencantuman *Serial Number* (SN) pengiriman token item game ke akun pembeli.

---

## 4.1.9 Implementasi Webhook

Webhook diimplementasikan untuk menangani notifikasi asinkron dari Tripay dan Digiflazz saat terjadi perubahan status transaksi secara instan.

### 1. Callback Tripay
Saat pembeli menyelesaikan pembayaran di aplikasi dompet digital atau mobile banking, server Tripay akan mengirim request callback POST ke URL `/tripay/callback`.
* Webhook memvalidasi header `HTTP_X_CALLBACK_SIGNATURE` secara lokal menggunakan raw payload JSON and *private key* Tripay melalui HMAC-SHA256.
* Jika valid and bernilai `PAID`, status pembayaran diubah menjadi lunas, sistem mengirim pesan notifikasi WhatsApp, dan mendaftarkan pekerjaan pengisian saldo game ke antrean FCFS Laravel Queue.

### 2. Callback Digiflazz
Saat transaksi top-up selesai dikirimkan ke server game oleh Digiflazz, server Digiflazz akan mengirim callback POST ke `/digi/callback/haryserver` dengan membawa header parameter `X-Hub-Signature` (HMAC-SHA1).
* If status update bernilai `Sukses`, status pembelian diubah menjadi `Sukses` pada database, dan notifikasi nomor voucher/Serial Number (SN) dikirimkan via WhatsApp.
* If status update bernilai `Gagal`, sistem memicu metode `handleRefund()` untuk melakukan pengembalian saldo balance kepada akun pengguna terdaftar, atau mencatat log pada tabel `pending_refunds` untuk guest user agar dana dapat direfund manual oleh admin.

---

**Gambar 4.22 Screenshot Payload Webhook Callback**

*(Aset Gambar: [payload_webhook.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/payload_webhook.png))*

*Penjelasan Gambar 4.22:*
Gambar 4.22 menyajikan screenshot payload data callback webhook yang diterima secara asinkron dari Tripay dan Digiflazz. Payload ini membawa data status final pembayaran dan Serial Number pengiriman voucher game yang sukses diproses.

---

# 4.2 Pengujian Sistem

Pengujian sistem dilakukan untuk memverifikasi fungsionalitas dan kinerja aplikasi Mustopup setelah melalui tahap implementasi. Tahap ini bertujuan mendeteksi kesalahan, menguji kepatuhan sistem terhadap skenario fungsional yang dirancang, serta menjamin bahwa seluruh fitur keamanan dan integrasi API berjalan dengan baik.

---

## 4.2.1 Black Box Testing

Pengujian *Black Box Testing* dilakukan untuk memastikan seluruh fungsi pada aplikasi Mustopup berjalan sesuai dengan kebutuhan fungsional. Pengujian dilakukan dengan memberikan masukan (*input*) pada setiap fitur tanpa memperhatikan struktur kode program. Hasil pengujian menunjukkan bahwa seluruh fitur utama dapat berjalan sesuai dengan yang diharapkan.

**Tabel 4.1 Hasil Pengujian Black Box**

| No | Fitur                   | Skenario Pengujian                 | Hasil yang Diharapkan         | Hasil Pengujian | Status   |
| -- | ----------------------- | ---------------------------------- | ----------------------------- | --------------- | -------- |
| 1  | Login Admin             | Login menggunakan akun valid       | Berhasil masuk ke dashboard   | Sesuai harapan  | Berhasil |
| 2  | Registrasi Member       | Mengisi seluruh data dengan benar  | Akun berhasil dibuat          | Sesuai harapan  | Berhasil |
| 3  | Login Member            | Login menggunakan akun terdaftar   | Berhasil masuk                | Sesuai harapan  | Berhasil |
| 4  | Pilih Produk            | Memilih produk game                | Detail produk tampil          | Sesuai harapan  | Berhasil |
| 5  | Checkout                | Mengisi data akun game             | Invoice berhasil dibuat       | Sesuai harapan  | Berhasil |
| 6  | Pembayaran Tripay       | Melakukan pembayaran QRIS          | Invoice berstatus UNPAID      | Sesuai harapan  | Berhasil |
| 7  | Callback Tripay         | Callback diterima sistem           | Status berubah menjadi PAID   | Sesuai harapan  | Berhasil |
| 8  | Order Digiflazz         | Sistem mengirim order ke Digiflazz | Order berhasil diproses       | Sesuai harapan  | Berhasil |
| 9  | Callback Digiflazz      | Serial number diterima             | Invoice selesai               | Sesuai harapan  | Berhasil |
| 10 | Riwayat Transaksi       | Membuka halaman riwayat            | Data transaksi tampil         | Sesuai harapan  | Berhasil |
| 11 | Dashboard Admin         | Membuka dashboard                  | Statistik tampil              | Sesuai harapan  | Berhasil |
| 12 | REST API Invoice        | Mengakses endpoint `/invoice/{id}` | Data invoice ditampilkan      | Sesuai harapan  | Berhasil |
| 13 | REST API History        | Mengakses endpoint `/history`      | Riwayat transaksi ditampilkan | Sesuai harapan  | Berhasil |
| 14 | Validasi Signature      | Signature tidak valid              | Request ditolak               | Sesuai harapan  | Berhasil |
| 15 | Middleware IP Whitelist | IP tidak terdaftar                 | Akses ditolak (403)           | Sesuai harapan  | Berhasil |

Berdasarkan hasil *Black Box Testing* pada Tabel 4.1, seluruh fitur utama aplikasi Mustopup dapat berfungsi sesuai dengan kebutuhan sistem. Tidak ditemukan kesalahan fungsional selama proses pengujian sehingga aplikasi dinyatakan telah memenuhi kebutuhan fungsional yang dirancang.

---

## 4.2.2 Boundary Value Analysis (BVA)

Pengujian *Boundary Value Analysis (BVA)* dilakukan untuk menguji kehandalan sistem dalam menangani input data pada batas nilai minimum dan maksimum yang telah didefinisikan. Pengujian ini berfokus pada nilai-nilai ekstrem (tepi) untuk meminimalkan risiko terjadinya *error* atau celah keamanan akibat input data yang tidak valid.

### 4.2.2.1 Tabel Pengujian BVA

Variabel input yang diuji menggunakan metode BVA meliputi panjang karakter User ID (`uid`), format nomor WhatsApp (`nomor`), nominal jumlah deposit (`jumlah`), dan panjang serta kombinasi karakter kata sandi (`password`) pada sistem registrasi dan profil.

**Tabel 4.2 Hasil Pengujian Boundary Value Analysis**

| No | Parameter Input | Nilai Batas (BVA) | Nilai Uji | Hasil yang Diharapkan | Hasil Pengujian | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | Panjang User ID (`uid`) | Maksimal 25 Karakter | 24 Karakter | Request diterima (Valid) | Sesuai harapan | Berhasil |
| 2 | Panjang User ID (`uid`) | Maksimal 25 Karakter | 25 Karakter | Request diterima (Valid) | Sesuai harapan | Berhasil |
| 3 | Panjang User ID (`uid`) | Maksimal 25 Karakter | 26 Karakter | Request ditolak (Error validasi) | Sesuai harapan | Berhasil |
| 4 | Jumlah Deposit (`jumlah`) | Minimal Rp 1 | Rp 0 | Request ditolak (Error: Jumlah tidak valid) | Sesuai harapan | Berhasil |
| 5 | Jumlah Deposit (`jumlah`) | Minimal Rp 1 | Rp 1 | Request diterima (Valid) | Sesuai harapan | Berhasil |
| 6 | Jumlah Deposit (`jumlah`) | Minimal Rp 1 | Rp 2 | Request diterima (Valid) | Sesuai harapan | Berhasil |
| 7 | No WhatsApp (`nomor`) | Minimal 10 Digit | 9 Digit | Request ditolak (Error: Minimal 10 digit) | Sesuai harapan | Berhasil |
| 8 | No WhatsApp (`nomor`) | Minimal 10 Digit | 10 Digit | Request diterima (Valid) | Sesuai harapan | Berhasil |
| 9 | No WhatsApp (`nomor`) | Minimal 10 Digit | 11 Digit | Request diterima (Valid) | Sesuai harapan | Berhasil |
| 10 | Panjang Password | Minimal 8 Karakter | "Pass123" (7 karakter) | Request ditolak (Error: Minimal 8) | Sesuai harapan | Berhasil |
| 11 | Panjang Password | Minimal 8 Karakter | "Pass1234" (8 karakter) | Request diterima (Valid) | Sesuai harapan | Berhasil |
| 12 | Panjang Password | Minimal 8 Karakter | "Pass12345" (9 karakter) | Request diterima (Valid) | Sesuai harapan | Berhasil |
| 13 | Kombinasi Password | Huruf besar, kecil, angka | "hanyahurufkecil" (10 karakter) | Request ditolak (Error: Kombinasi karakter) | Sesuai harapan | Berhasil |

---

### 4.2.2.2 Hasil Pengujian BVA

Hasil pengujian BVA menunjukkan bahwa sistem Laravel secara konsisten melakukan validasi input pada batas-batas nilai yang telah ditentukan:
1. **Validasi Karakter**: Input User ID (`uid`) dengan batas maksimal 25 karakter berhasil menyaring input berlebih (26 karakter) dengan mengembalikan pesan kesalahan bawaan validator Laravel.
2. **Validasi Nominal**: Jumlah input deposit di bawah batas minimum Rp 1 (yaitu Rp 0 atau minus) berhasil dihentikan oleh aturan `min:1` pada validator `DepositController` dengan mengembalikan pesan error *"Jumlah tidak valid"*.
3. **Validasi Tipe Data**: Input nomor WhatsApp di bawah batas minimal 10 digit berhasil ditolak dengan benar oleh sistem.
4. **Validasi Kompleksitas Password**: Input sandi di bawah 8 karakter atau yang tidak memiliki perpaduan huruf kapital, huruf kecil, dan angka berhasil ditolak dengan pesan kesalahan kustom *"Password harus terdiri dari minimal 8 karakter"* dan *"Password harus mengandung huruf besar, huruf kecil, dan angka"*.

---

### 4.2.2.3 Analisis Pengujian BVA

Berdasarkan analisis hasil pengujian BVA pada Tabel 4.2, sistem aplikasi Mustopup terbukti memiliki tingkat kehandalan input yang tinggi (*robustness*). Validasi batas nilai pada tingkat controller berfungsi secara preventif untuk:
* Mencegah terjadinya *database overflow* atau inkonsistensi skema data akibat input panjang karakter yang melebihi kapasitas kolom basis data.
* Mencegah manipulasi nominal negatif pada transaksi keuangan (deposit saldo) yang dapat merugikan penyedia platform.
* Menjamin keakuratan data pengiriman notifikasi WhatsApp dengan mewajibkan format numerik bersih tanpa spasi atau tanda hubung.
* Melindungi akun pengguna secara maksimal dari serangan *brute-force* atau tebakan kamus dengan mewajibkan penerapan frasa sandi (*passphrase*) yang panjang dan berkekuatan tinggi.

Secara keseluruhan, pengujian BVA membuktikan bahwa sistem aplikasi Mustopup aman terhadap serangan *input injection* atau kesalahan entri data pada batas-batas kritis nilai parameter.

---

## 4.2.4 Stress Testing

Pengujian *Stress Testing* dilakukan untuk mengetahui kemampuan sistem ketika menerima beban tinggi secara terus-menerus. Pengujian dilakukan menggunakan **Apache JMeter** dengan meningkatkan jumlah pengguna virtual (*Virtual User/VU*) hingga sistem mencapai batas kemampuan. Parameter yang diamati meliputi penggunaan CPU, penggunaan RAM, *response time*, dan keberhasilan pemrosesan permintaan.

### 4.2.4.1 Hasil Pengujian Stress Testing

**Tabel 4.3 Hasil Stress Testing**

| Parameter | Hasil |
| :--- | :--- |
| Beban Maksimum | 100 Virtual User |
| CPU Maksimum | 65.4 % |
| RAM Maksimum | 184 MB / 2.0 GB |
| Average Response Time | 342 ms |
| Error Rate | 0.0 % |
| Status | Berhasil |

---

**Gambar 4.23 Skenario Stress Testing pada Apache JMeter**

*(Aset Gambar: [jmeter_stress_test_scenario.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/jmeter_stress_test_scenario.png))*

*Penjelasan Gambar 4.23:*
Gambar 4.23 menampilkan konfigurasi Thread Group pada Apache JMeter yang disimulasikan untuk mengirimkan beban maksimum sebesar 100 Virtual User secara simultan ke server aplikasi Mustopup.

---

**Gambar 4.24 Grafik Penggunaan CPU Saat Pengujian Beban**

*(Aset Gambar: [stress_test_cpu_usage.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/stress_test_cpu_usage.png))*

*Penjelasan Gambar 4.24:*
Gambar 4.24 menyajikan visualisasi grafik monitoring beban kerja CPU server Hostinger yang menyentuh angka tertinggi 65.4% selama pengujian berlangsung.

---

**Gambar 4.25 Grafik Penggunaan RAM Saat Pengujian Beban**

*(Aset Gambar: [stress_test_ram_usage.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/stress_test_ram_usage.png))*

*Penjelasan Gambar 4.25:*
Gambar 4.25 menyajikan visualisasi grafik konsumsi memori utama (RAM) server Hostinger yang stabil pada angka 184 MB saat dialiri 100 Virtual User.

---

**Gambar 4.26 Hasil Summary Report Pengujian pada Apache JMeter**

*(Aset Gambar: [jmeter_summary_report.png](file:///E:/muslihinnnn%20(1)/harydata/assets/img/jmeter_summary_report.png))*

*Penjelasan Gambar 4.26:*
Gambar 4.26 memperlihatkan tabel Summary Report Apache JMeter yang mencatat statistik keberhasilan transaksi 100% tanpa adanya kegagalan jaringan (*Error Rate 0.0%*) dan rata-rata waktu respon sebesar 342 ms.

---

Berdasarkan hasil *Stress Testing* pada Tabel 4.3, aplikasi Mustopup mampu melayani hingga **100 Virtual User** secara bersamaan dengan penggunaan CPU sebesar **65.4%**, penggunaan RAM sebesar **184 MB**, serta *response time* rata-rata **342 ms**. Selama pengujian tidak ditemukan kegagalan yang signifikan sehingga sistem dinilai tetap stabil pada beban maksimum yang diberikan.
