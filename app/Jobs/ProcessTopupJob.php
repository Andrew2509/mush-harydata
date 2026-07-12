<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\DigiFlazzController;
use App\Models\Pembelian;
use Illuminate\Support\Facades\Log;

class ProcessTopupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    /**
     * Create a new job instance.
     *
     * @param string $orderId
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info("Queue Started: Memulai pemrosesan antrean untuk Order ID: " . $this->orderId);

        $pembelian = Pembelian::where('order_id', $this->orderId)->first();
        if (!$pembelian || $pembelian->status !== 'Paid') {
            Log::warning("Order ditolak dari antrean. Status bukan 'Paid' atau data tidak ada: " . $this->orderId);
            return;
        }

        // Update status to 'Proses' and set provider_order_id to orderId so callback can map it
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

        // Validasi response Digiflazz
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
}
