<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $config = DB::table('setting_webs')->where('id', 1)->first();
            
            // Safety check for null config
            if (!$config) {
                $config = (object)[
                    'id' => 1,
                    'warna1' => '#f5c754', // default yellow
                    'warna2' => '#0f172a', // default dark
                    'warna3' => '#1e293b', 
                    'judul_web' => config('app.name', 'Mustopup'),
                    'deskripsi_web' => 'Store Topup Game Terpercaya',
                    'logo_header' => null,
                    'logo_footer' => null,
                    'logo_favicon' => null,
                    'og_image' => null,
                ];
            }

            $kategoris = \App\Models\Kategori::where('status', 'active')->get();
            $logoheader = \App\Models\Berita::where('tipe', 'logoheader')->latest()->first();
            $logofooter = \App\Models\Berita::where('tipe', 'logofooter')->latest()->first();

            $view->with([
                'config' => $config,
                'kategoris' => $kategoris,
                'logoheader' => $logoheader,
                'logofooter' => $logofooter,
            ]);
        });
    }
}
