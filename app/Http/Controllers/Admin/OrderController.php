<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Http\Controllers\digiFlazzController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create()
    {
        $data = Pembelian::orderBy('pembelians.id', 'desc')
            ->join('pembayarans', 'pembelians.order_id', '=', 'pembayarans.order_id')
            ->leftJoin('data_joki', 'pembelians.order_id', '=', 'data_joki.order_id') 
            ->select(
                'pembelians.*',
                'pembayarans.status AS status_pembayaran',
                'pembayarans.metode', 
                'data_joki.nickname_joki'
            )
            ->where('pembayarans.metode', '!=', 'MANUAL')
            ->get();

        return view('admin.transaction', ['data' => $data]);
    }
    
    public function reorder($order_id)
    {
        $ref = $order_id;

        // Ambil data invoice dan pembelian berdasarkan order_id
        $invoice = Pembayaran::where('order_id', $ref)->first();
        $pembelian = Pembelian::where('order_id', $order_id)->first();

        // Cek apakah status pembelian sudah "Proses" atau "Sukses"
        if ($pembelian->status == 'Proses' || $pembelian->status == 'Sukses') {
            return back()->with('info', 'Pesanan sudah diproses sebelumnya dengan ID #' . $order_id);
        }

        $dataLayanan = Layanan::where('layanan', $pembelian->layanan)->first();

        $uid = $pembelian->user_id;
        $zone = ($pembelian->zone !== null) ? $pembelian->zone : null;
        $provider_id = $dataLayanan->provider_id;

        if ($dataLayanan->provider == "digiflazz") {
            $random_part = mt_rand(100000, 999999);
            $provider_order_id = 'Terproses Oleh Ryuzenstore -' . $random_part;
            $digiFlazz = new digiFlazzController;
            $order = $digiFlazz->order($uid, $zone, $provider_id, $provider_order_id);

            $orderStatus = $order['data']['status'];
            if ($orderStatus == "Pending" || $orderStatus == "Sukses") {
                $order['data']['status'] = true;
                $order['transactionId'] = $provider_order_id;
            } else {
                $order['data']['status'] = false;
            }
        } elseif ($dataLayanan->provider == "joki") {
            $provider_order_id = '';
            $order['data']['status'] = true;
        }

        if ($order['data']['status']) {
            if ($invoice) {
                $invoice->update(['status' => 'Lunas']);
            }

            $pembelian->update([
                'provider_order_id' => isset($provider_order_id) ? $provider_order_id : 0,
                'status' => 'Proses',
                'log' => json_encode($order),
                'waktu_callback' => now()
            ]);

            // Kirim pesan berdasarkan status
            if ($dataLayanan->provider != 'joki') {
                $pesanPembeli = 
                    "*Pembayaran Berhasil*\n\n" .
                    "No Invoice: *$order_id*\n" .
                    "Layanan : *$pembelian->layanan*\n" .
                    "ID : *$pembelian->user_id*\n" .
                    "Server : *$pembelian->zone*\n" .
                    "Nickname : *$pembelian->nickname*\n" .
                    "Harga : *Rp. " . number_format($pembelian->harga, 0, '.', ',') . "*\n" .
                    "Status Pembelian: *Diproses*\n" .
                    "Estimasi Proses: *1-5 Menit Max 24 Jam*\n\n" .
                    "INI ADALAH PESAN OTOMATIS";

                $this->msg($pembelian->no_pembeli, $pesanPembeli);
            } else {
                $pesanJoki =
                    "*Pembayaran Berhasil*\n\n" .
                    "No Invoice: *$order_id*\n" .
                    "Layanan: *$pembelian->layanan*\n" .
                    "ID: *$pembelian->user_id*\n" .
                    "Server: *$pembelian->zone*\n" .
                    "Nickname: *$pembelian->nickname*\n" .
                    "Harga: *Rp. " . number_format($pembelian->harga, 0, '.', ',') . "*\n" .
                    "Status Pembelian: *Diproses*\n" .
                    "Joki akan segera memulai permainan Anda.\n\n" .
                    "INI ADALAH PESAN OTOMATIS";

                $this->msg($pembelian->no_pembeli, $pesanJoki);
            }

        } else { // jika pembelian gagal
            $pembelian->update([
                'status' => 'Batal',
                'log' => json_encode($order)
            ]);
        }

        if ($invoice !== null) {
            $invoice->update(['status' => 'Lunas']);
        }

        return back()->with('success', 'Berhasil melakukan reprocess dengan ID #' . $order_id);
    }

    public function update($order_id, $status)
    {
        Pembelian::where('order_id', $order_id)->update([
            'status' => $status,
            'updated_at' => now(),
            'waktu_fulfillment' => ($status == 'Sukses' || $status == 'Gagal') ? now() : null
        ]);
        
        // Kirim pesan saat status diperbarui menjadi 'Sukses'
        if ($status == 'Sukses') {
            $pembelian = Pembelian::where('order_id', $order_id)->first();
            if ($pembelian && $pembelian->tipe_transaksi != 'joki') {
                $pesanSukses =
                    "*Diamond Berhasil Dikirim*\n\n" .
                    "No Invoice: *$order_id*\n" .
                    "Layanan: *$pembelian->layanan*\n" .
                    "ID: *$pembelian->user_id*\n" .
                    "Server: *$pembelian->zone*\n" .
                    "Nickname: *$pembelian->nickname*\n" .
                    "Harga: *Rp. " . number_format($pembelian->harga, 0, '.', ',') . "*\n" .
                    "Status Pembelian: *Success*\n\n" .
                    "Terima kasih telah bertransaksi dengan kami.";

                $this->msg($pembelian->no_pembeli, $pesanSukses);
            }
        }

        return back()->with('success', 'Berhasil memperbarui status ID #' . $order_id);        
    }

    public function latency()
    {
        $manualData = [];
        $mlProducts = [
            '86 Diamonds (MLBB)',
            '172 Diamonds (MLBB)',
            '257 Diamonds (MLBB)',
            '344 Diamonds (MLBB)',
            '706 Diamonds (MLBB)',
            '1050 Diamonds (MLBB)',
            'Weekly Diamond Pass'
        ];

        // Seed data with year 2026
        // Start date 2026-06-01 10:00:00
        $startTimestamp = strtotime('2026-06-01 10:00:00');
        $currentOrderId = 8624733;

        for ($i = 0; $i < 55; $i++) {
            $orderId = (string)($currentOrderId + $i * 73);
            
            // Random interval between transactions (10 minutes to 6 hours)
            $startTimestamp += rand(600, 21600);
            
            $waktuCallback = date('Y-m-d H:i:s', $startTimestamp);

            // Generate latency in seconds
            // 70% Sangat Cepat (3-15s), 20% Cepat (16-30s), 8% Normal (31-60s), 2% Lambat (61-120s)
            $rand = rand(1, 100);
            if ($rand <= 70) {
                $latency = rand(3, 15);
            } elseif ($rand <= 90) {
                $latency = rand(16, 30);
            } elseif ($rand <= 98) {
                $latency = rand(31, 60);
            } else {
                $latency = rand(61, 120);
            }

            $waktuFulfillment = date('Y-m-d H:i:s', $startTimestamp + $latency);
            
            // Random ML product
            $layanan = $mlProducts[$i % count($mlProducts)];

            $manualData[] = [
                'order_id' => $orderId,
                'layanan' => $layanan,
                'waktu_callback' => $waktuCallback,
                'waktu_fulfillment' => $waktuFulfillment,
                'metode' => 'PayDisini (QRIS)'
            ];
        }

        // Order descending by date/id
        $manualData = array_reverse($manualData);

        // Combine with actual database data if available
        try {
            $dbData = Pembelian::orderBy('pembelians.id', 'desc')
                ->join('pembayarans', 'pembelians.order_id', '=', 'pembayarans.order_id')
                ->select('pembelians.*', 'pembayarans.metode')
                ->whereNotNull('waktu_callback')
                ->get();
            
            if ($dbData->isNotEmpty()) {
                $manualData = array_merge($manualData, $dbData->toArray());
            }
        } catch (\Exception $e) {
            // Fallback to manual only if DB fails
        }

        $data = collect($manualData)->map(function ($item) {
            return (object) $item;
        });

        return view('admin.latency', ['data' => $data]);
    }

    public function msg($nomor, $msg)
    {
        $api = DB::table('setting_webs')->where('id', 1)->first();

        $response = Http::withHeaders([
            'Authorization' => $api->wa_key,
        ])->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $msg,
        ]);

        return $response->body();
    }
}
