<?php

namespace App\Http\Controllers\policyandtermss;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Berita;

class TermsController extends Controller 
{
    public function terms()
    {
        return view('pages.terms-and-condition', [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }
    
    public function policy()
    {
        return view('pages.privacy-policy', [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }
    
}
