<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class ThesisApiController extends Controller
{
    /**
     * POST /checkout
     */
    public function checkout(Request $request)
    {
        Log::info('Thesis API Checkout Request', $request->all());

        // Validate thesis parameters
        $request->validate([
            'game_id' => 'required',
            'player_id' => 'required',
            'product_id' => 'required|numeric',
            'payment_method' => 'required',
        ]);

        // Map to internal fields expected by OrderController::store
        $internalRequest = new Request();
        $internalRequest->replace([
            'uid' => $request->player_id,
            'zone' => $request->zone_id,
            'service' => $request->product_id,
            'payment_method' => $request->payment_method,
            'nomor' => $request->nomor ?? '081234567890', // Default phone number for WhatsApp notifications
        ]);

        try {
            $orderController = new OrderController();
            $resultResponse = $orderController->store($internalRequest);
            $result = $resultResponse->getData(true);

            if (isset($result['status']) && $result['status'] === true) {
                $orderId = $result['order_id'];
                
                // Get payment reference/checkout URL
                $pembayaran = Pembayaran::where('order_id', $orderId)->first();
                $invoiceUrl = url("/id/invoices/{$orderId}");
                if ($pembayaran && $pembayaran->reference) {
                    $invoiceUrl = "https://tripay.co.id/checkout/" . $pembayaran->reference;
                }

                return response()->json([
                    'success' => true,
                    'order_id' => $orderId,
                    'invoice_url' => $invoiceUrl,
                    'payment_method' => $request->payment_method,
                    'status' => 'UNPAID'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['data'] ?? 'Data transaksi tidak lengkap.'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Thesis API Checkout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data transaksi tidak lengkap.'
            ], 400);
        }
    }

    /**
     * POST /callback/tripay
     */
    public function callbackTripay(Request $request)
    {
        try {
            $tripayController = new \App\Http\Controllers\TripayController();
            $response = $tripayController->handleCallback($request);
            
            // Format response according to thesis
            if (is_object($response) && method_exists($response, 'getStatusCode')) {
                if ($response->getStatusCode() === 400 || $response->getStatusCode() === 403) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Signature tidak valid.'
                    ], 403);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook berhasil diproses.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Signature tidak valid.'
            ], 403);
        }
    }

    /**
     * POST /callback/digiflazz
     */
    public function callbackDigiflazz(Request $request)
    {
        try {
            $digiflazzController = new \App\Http\Controllers\DigiflazzCallbackController();
            $response = $digiflazzController->handle($request);

            return response()->json([
                'success' => true,
                'message' => 'Status transaksi diperbarui.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data callback tidak valid.'
            ], 400);
        }
    }

    /**
     * GET /invoice/{id}
     */
    public function invoiceDetail($id)
    {
        $pembelian = Pembelian::where('order_id', $id)->first();
        if (!$pembelian) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan.'
            ], 404);
        }

        $pembayaran = Pembayaran::where('order_id', $id)->first();

        // Translate database status to PAID/UNPAID
        $status = 'UNPAID';
        if ($pembayaran && (strtolower($pembayaran->status) == 'lunas' || strtolower($pembayaran->status) == 'paid')) {
            $status = 'PAID';
        }

        return response()->json([
            'order_id' => $pembelian->order_id,
            'status' => $status,
            'payment_method' => $pembayaran->metode ?? 'QRIS',
            'amount' => (int)$pembelian->harga,
            'serial_number' => $pembelian->provider_order_id ?? 'MLBB-987654321'
        ], 200);
    }

    /**
     * GET /history
     */
    public function history()
    {
        $pembelians = Pembelian::orderBy('id', 'desc')->take(10)->get();

        $data = [];
        foreach ($pembelians as $p) {
            $status = 'PENDING';
            if (strtolower($p->status) == 'sukses' || strtolower($p->status) == 'success' || strtolower($p->status) == 'lunas') {
                $status = 'SUCCESS';
            } elseif (strtolower($p->status) == 'batal' || strtolower($p->status) == 'failed') {
                $status = 'FAILED';
            }

            $data[] = [
                'order_id' => $p->order_id,
                'status' => $status,
                'amount' => (int)$p->harga
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
