<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\digiFlazzController;
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
        $pembelian = Pembelian::where('order_id', $this->orderId)->first();
        if (!$pembelian || $pembelian->status !== 'Paid') {
            return;
        }

        // Update status to 'Proses' and set provider_order_id to orderId so callback can map it
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
}
