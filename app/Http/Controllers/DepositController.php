<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Berita;
use App\Models\Pembayaran;
use App\Http\Controllers\TokoPayController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function reloadd()
    {
        return view('user.reload', ['data' => Deposit::where('username', Auth::user()->username)->orderBy('created_at', 'desc')->get(),
        'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
          'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
          'pay_method' => \App\Models\Method::all()
        ]);
    }
    public function create()
    {
        // return view('components.deposit', ['data' => Deposit::where('username', Auth::user()->username)->orderBy('created_at', 'desc')->paginate(10),
        // 'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
        //   'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        // ]);
        
        return view('user.deposit', ['data' => Deposit::where('username', Auth::user()->username)->orderBy('created_at', 'desc')->get(),
        'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
          'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
          'pay_method' => \App\Models\Method::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'metode' => 'required',
            'no_telfon' => 'required'
        ], [
            'jumlah.numeric' => "Jumlah tidak valid",
            "jumlah.min" => "Jumlah tidak valid",
            'jumlah.required' => "Harap mengisi jumlah",
            'no_telfon.required' => "Harap Mengisi No WhatsApp",
            'metode.required' => "Harap mengisi metode"
        ]);
        
        $api = DB::table('setting_webs')->where('id',1)->first();
        
        $unik = date('Hs');
        
        $characters = '0123456789'; // Tambahan huruf kapital A-Z
        $code = '';
        
        for ($i = 0; $i < 8; $i++) { // Panjang kode 12 karakter
            $randomIndex = rand(0, strlen($characters) - 1);
            $code .= $characters[$randomIndex];
        }
        
        $kode_unik = $code;
        $order_id = '4BILLD'.$unik.$kode_unik;
        $tripay = new TripayController();
        
        $customer_name = Auth::user()->name ?? Auth::user()->username;
        $customer_email = Auth::user()->email ?? Auth::user()->username . '@gmail.com';
        
        $tripayres = $tripay->createTransaction(
            $request->jumlah, 
            $order_id, 
            $request->metode, 
            $customer_name, 
            $request->no_telfon, 
            $customer_email
        );
        
        if ($tripayres['status'] != 'Success') {
            return back()->withErrors(['metode' => 'TriPay Error: ' . $tripayres['data']])->withInput();
        }
        
        $data = $tripayres['data'];
        $no_pembayaran = $data['pay_code'] ?? $data['qr_url'] ?? $data['checkout_url'] ?? '-';
        $reference = $data['reference'] ?? '-';
        $amount = $data['amount'] ?? $request->jumlah;

        $deposit = new Deposit();
        $deposit->order_id = $order_id;
        $deposit->username = Auth::user()->username;
        $deposit->metode = $request->metode;
        $deposit->no_pembayaran = $no_pembayaran;
        $deposit->jumlah = $request->jumlah;
        $deposit->status = "Pending";
        $deposit->save();
        
        $pembayaran = new Pembayaran();
        $pembayaran->order_id = $order_id;
        $pembayaran->harga = $amount;
        $pembayaran->no_pembayaran = $no_pembayaran;
        $pembayaran->no_pembeli = $request->no_telfon;
        $pembayaran->status = 'Belum Lunas';
        $pembayaran->metode = $request->metode;
        $pembayaran->reference = $reference;
        $pembayaran->save();

        return redirect('/id/deposit/' . $order_id)->with('success', 'Berhasil deposit saldo, silahkan lakukan pembayaran');
    }
}
