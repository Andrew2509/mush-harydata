<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class SettingWebController extends Controller
{
    public function settingWeb()
    {
        
        $api = DB::table('setting_webs')->where('id',1)->first();
        return view('admin.settingweb',[
            'web' => DB::table('setting_webs')->where('id',1)->first(),
            ]);
        
        try {
        $response = Http::withOptions([
            'verify' => true,
        ])->get("https://solo.wablas.com/api/device/info", [
            'token' => $api->wa_key,
        ]);

        $data = $response->json()['data'];
        $status = $data['status'];
        $account = $data['name'];
        $expired = $data['expired_date'];
        $sender = $data['sender'];
        $wa_key = $api->wa_key;

        return view('admin.settingweb', [
            'status' => $status,
            'account' => $account,
            'expired' => $expired,
            'sender' => $sender,
            'wa_key' => $wa_key,
        ]);
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        $errorMessage = "Terjadi kesalahan koneksi ke API. Silakan coba lagi nanti.";
        return view('admin.settingweb', [
            'error' => $errorMessage,
        ]);
    } catch (\Illuminate\Http\Client\RequestException $e) {
        $errorMessage = "Terjadi kesalahan saat mengambil data dari API. Silakan coba lagi nanti.";
        return view('admin.settingweb', [
            'error' => $errorMessage,
        ]);
    }
    }
    
    public function saveSettingWeb(Request $request)
    {
        $request->validate([
            'judul_web' => 'required',
            'deskripsi_web' => 'required',
            'logo_header' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_footer' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_favicon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'url_wa' => 'required',
            'url_ig' => 'required',
            'url_tiktok' => 'required',
            'url_youtube' => 'required',
            'url_fb' => 'required',
            'topupindo_api' => 'nullable',
        ]);
        
        if($request->hasFile('logo_header')){
            $file = $request->file('logo_header');
            $filename = 'header-' . time() . '.' . $file->getClientOriginalExtension();
            $folder = 'assets/logo';
            $file->move(public_path($folder), $filename);      
            DB::table('setting_webs')->where('id', 1)->update([
                'logo_header' => "/".$folder."/".$filename
            ]);
        }
        
        if($request->hasFile('logo_footer')){
            $file2 = $request->file('logo_footer');
            $filename2 = 'footer-' . time() . '.' . $file2->getClientOriginalExtension();
            $folder2 = 'assets/logo';
            $file2->move(public_path($folder2), $filename2);      
            DB::table('setting_webs')->where('id', 1)->update([
                'logo_footer' => "/".$folder2."/".$filename2
            ]);
        }
        
        if($request->hasFile('logo_favicon')){
            $file3 = $request->file('logo_favicon');
            $filename3 = 'favicon-' . time() . '.' . $file3->getClientOriginalExtension();
            $folder3 = 'assets/logo';
            $file3->move(public_path($folder3), $filename3);      
            DB::table('setting_webs')->where('id', 1)->update([
                'logo_favicon' => "/".$folder3."/".$filename3
            ]);
        }
        
        DB::table('setting_webs')->where('id',1)->update([
           
           'judul_web' => $request->judul_web,
           'deskripsi_web' => $request->deskripsi_web,
           'url_wa' => $request->url_wa,
           'url_ig' => $request->url_ig,
           'url_tiktok' => $request->url_tiktok,
           'url_youtube' => $request->url_youtube,
           'url_fb' => $request->url_fb,
           'topupindo_api' => $request->topupindo_api,
           'created_at' => now(),
           'updated_at' => now()
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi website!');
        
        
    }
    
    public function saveSettingWarna(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'warna1' => $request->warna1,
          'warna2' => $request->warna2,
          'warna3' => $request->warna3,
          'warna4' => $request->warna4
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Warna!');
        
    }
    
    
    public function saveSettingTripay(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'tripay_api' => $request->tripay_api,
          'tripay_merchant_code' => $request->tripay_merchant_code,
          'tripay_private_key' => $request->tripay_private_key
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Tripay!');
        
    }
    public function saveSettingPaydisini(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'paydisini' => $request->paydisini
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Tripay!');
        
    }
    
    public function saveSettingTokopay(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'tokopay_merchant_id' => $request->tokopay_merchant_id,
          'tokopay_secret_key' => $request->tokopay_secret_key
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Tripay!');
        
    }
    
    public function saveSettingDigiflazz(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'username_digi' => $request->username_digi,
          'api_key_digi' => $request->api_key_digi,
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Digiflazz!');
        
    }
    
    public function saveSettingBangjeff(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'apikey_bangjeff' => $request->apikey_bangjeff
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Api Key!');
        
    }
    public function saveSettingAoshi(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'apikey_aoshi' => $request->apikey_aoshi
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Api Key!');
        
    }
    public function saveSettingMobilegamestore(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'api_mobilegamestore' => $request->api_mobilegamestore
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Api Key Mobilegamestore!');
        
    }
    
    public function saveSettingApigames(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'apigames_secret' => $request->apigames_secret,
          'apigames_merchant' => $request->apigames_merchant,
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi Apigames!');
        
    }
    
    public function saveSettingVip(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'vip_apiid' => $request->vip_apiid,
          'vip_apikey' => $request->vip_apikey,
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi VIP Reseller!!');
        
    }
    
    public function saveSettingWagateway(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'nomor_admin' => $request->nomor_admin,
          'wa_key' => $request->wa_key,
          'wa_number' => $request->wa_number
            
        ]);
        
         $response = $this->changeNumber($request->wa_number);
        
        return back()->with('success','Berhasil konfigurasi WA gateway!');
        
    }
    
    public function changeNumber($number){
        
        $api = DB::table('setting_webs')->where('id',1)->first();
        
        $curl = curl_init();
        $data = [
            'phone' => "$number",
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $api->wa_key",
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL,  "https://solo.wablas.com/api/device/change-number");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
    
     public function saveSettingMutasi(Request $request)
    {
        
        DB::table('setting_webs')->where('id',1)->update([
           
          'ovo_admin' => $request->ovo_admin,
          'ovo1_admin' => $request->ovo1_admin,
          'gopay_admin' => $request->gopay_admin,
          'gopay1_admin' => $request->gopay1_admin,
          'dana_admin' => $request->dana_admin,
          'shopeepay_admin' => $request->shopeepay_admin,
          'bca_admin' => $request->bca_admin
            
        ]);
        
        
        return back()->with('success','Berhasil konfigurasi mutasi e-wallet / bank!');
        
    }
    
}