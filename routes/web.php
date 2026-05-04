<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDepositController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\Berita;
use App\Http\Controllers\CariController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TokoPayCallbackController;
use App\Http\Controllers\PaydisiniCallbackController;
use App\Http\Controllers\digiFlazzController;
use App\Http\Controllers\DigiflazzCallbackController;
use App\Http\Controllers\RiwayatPembelian;
use App\Http\Controllers\Admin\UserDepositController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Admin\WhatsappController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\DeviceInfoController;
use App\Http\Controllers\PricelistController;
use App\Http\Controllers\SusController;
use App\Http\Controllers\Admin\SusAdminController;
use App\Http\Controllers\DsController;
use App\Http\Controllers\Admin\MethodController as AdminMethodController;
use App\Http\Controllers\MethodController;
use App\Http\Controllers\Admin\SettingWebController;
use App\Http\Controllers\Admin\DataJokiController;
use App\Http\Controllers\GiftskinController;
use App\Http\Controllers\Admin\DataGiftSkinController;
use App\Http\Controllers\HitungpointmwController;
use App\Http\Controllers\HitungpointzodiacController;
use App\Http\Controllers\HitungwrController;
use App\Http\Controllers\ratingCustomerController;
use App\Http\Controllers\ratingAdminController;
use App\Http\Controllers\IPAddressController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\policyandtermss\TermsController;
use App\Http\Controllers\leaderboard\LeaderboardController;
use App\Http\Controllers\PaketLayananController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\TabmenuController;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
use App\Http\Controllers\provider\bangjeff\BangJeffController;
use App\Http\Controllers\provider\topupedia\TopupediaController;
use App\Http\Controllers\Admin\BangjeffdashboardController;
use App\Http\Controllers\Admin\DigiflazzdashboardController;
use App\Http\Controllers\Admin\TopupediadashboardController;
use App\Http\Controllers\ApiCheckController;
use App\Http\Controllers\Admin\WhitelistedIPController;
use App\Models\PaketLayanan;
use App\Http\Controllers\TokoPayController;
use App\Models\Withdrawal;
use App\Http\Controllers\OtpController;

Route::get('/cek-ip', function () {
    $ipAddress = request()->ip(); 
    return response()->json(['ip' => $ipAddress]);
});

Route::get('/test-debug', function () {
    return "Route is working. APP_ENV: " . config('app.env') . " | APP_DEBUG: " . (config('app.debug') ? 'true' : 'false');
});

Route::get('/', function () {
    return redirect('/id');
});

Route::prefix('id')->middleware(['xss', 'sanitize'])->group(function () {
    Route::get('/', [\App\Http\Controllers\BerandaController::class, 'create'])->name('home');

Route::middleware(['auth', 'xss', 'sanitize'])->group(function () {
    Route::get('/dashboard',                                                     [DsController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings',                                                      [DsController::class, 'editProfile'])->name('editProfile');
    Route::post('/settings',                                                     [DsController::class, 'saveEditProfile'])->name('saveEditProfile');
    Route::post('/id/logout',                                                    [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard/reload',                                              [DepositController::class, 'reloadd'])->name('reload');
    Route::get('/dashboard/reload/topup',                                        [DepositController::class, 'create'])->name('deposit');
    Route::post('/dashboard/reload/topup',                                       [DepositController::class, 'store'])->name('deposit.store');
    Route::get('/deposit/{order}',                                               [InvoiceDepositController::class, 'create'])->name('deposit.invoice');
    Route::get('/dashboard/history',                                             [RiwayatPembelian::class, 'create'])->name('riwayat');
    
    // Profile OTP
    Route::post('/settings/send-otp',                                            [OtpController::class, 'sendProfileOtp'])->name('profile.send_otp');
    Route::post('/settings/verify-otp',                                          [OtpController::class, 'verifyProfileOtp'])->name('profile.verify_otp');
});

    // Rute publik
    Route::post('/cari/index',                                                   [\App\Http\Controllers\BerandaController::class, 'cariIndex']);
    Route::get('/invoices',                                                      [CariController::class, 'create'])->name('cari');
    Route::post('/cari',                                                         [CariController::class, 'store'])->name('cari.post');
    Route::get('/price-list',                                                    [PricelistController::class, 'create'])->name('price');
    Route::get('/calculator/magic-wheel',                                        [HitungpointmwController::class, 'create'])->name('hitungpointmw');
    Route::get('/calculator/zodiac',                                             [HitungpointzodiacController::class, 'create'])->name('hitungpointzodiac');
    Route::get('/calculator/winrate',                                            [HitungwrController::class, 'create'])->name('hitungwr');
    Route::get('/leaderboard',                                                   [LeaderboardController::class, 'leaderboard'])->name('leaderboardd');
    Route::get('/terms-and-condition',                                           [TermsController::class, 'terms'])->name('terms');
    Route::get('/privacy-policy',                                                [TermsController::class, 'policy'])->name('policy');
    Route::get('/sign-in',                                                       [LoginController::class, 'create'])->name('login');
    Route::post('/sign-in',                                                      [LoginController::class, 'store'])->name('post.login')->middleware('throttle:10,1');
    Route::get('/sign-up',                                                       [RegisterController::class, 'create'])->name('register');
    Route::post('/sign-up',                                                      [RegisterController::class, 'store'])->name('post.register');
    Route::post('/firebase-google-login',                                        [\App\Http\Controllers\FirebaseAuthController::class, 'handleGoogleLogin'])->name('firebase.google.login');
    Route::get('/reviews',                                                       [RatingCustomerController::class, 'create'])->name('reviews');

    // OTP Routes
    Route::post('/send-otp',                                                     [OtpController::class, 'sendOtp'])->name('send.otp');
    Route::post('/verify-otp',                                                   [OtpController::class, 'verifyOtp'])->name('verify.otp');

    // Forgot Password
    Route::get('/forgot-password',                                               [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password/send-otp',                                     [OtpController::class, 'sendOtpForgot'])->name('password.send_otp');
    Route::post('/forgot-password/verify-otp',                                   [OtpController::class, 'verifyOtpForgot'])->name('password.verify_otp');
    Route::get('/forgot-password/reset',                                         [ForgotPasswordController::class, 'showResetFormAfterOtp'])->name('password.reset.otp');
    Route::post('/forgot-password/reset',                                        [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});
    Route::get('/whitelist',                                                        [WhitelistedIPController::class, 'index'])->name('whitelisted-ips.index');
    Route::delete('/whitelist/{whitelistedIP}',                                     [WhitelistedIPController::class, 'destroy'])->name('whitelisted-ips.destroy');
    Route::get('/whitelist/create',                                                 [WhitelistedIPController::class, 'create'])->name('whitelisted-ips.create');
    Route::post('/whitelist',                                                       [WhitelistedIPController::class, 'store'])->name('whitelisted-ips.store');

Route::middleware(['xss', 'sanitize',])->group(function () {
    Route::get('/id/{kategori:kode}',                                            [OrderController::class, 'create']);
    Route::post('/id/harga',                                                     [OrderController::class, 'price'])->name('ajax.price');
    Route::post('/id/konfirmasi-data',                                           [OrderController::class, 'confirm'])->name('ajax.confirmation');
    Route::post('/id',                                                           [OrderController::class, 'store'])->name('ordered');
    Route::get('/id/invoices/{order}',                                           [InvoiceController::class, 'create'])->name('pembelian');
    Route::get('/id/invoices/{order}/status',                                    [InvoiceController::class, 'checkStatus'])->name('pembelian.status');
    Route::post('/id/invoices/{order}',                                          [InvoiceController::class, 'ratingCustomer'])->name('rating.pembelian');
    Route::post('/check-voucher',                                                [VoucherController::class, 'confirm'])->name('check.voucher');
    
});

// Rute callback
Route::group([], function () {
    Route::post('/digi/callback/haryserver',                                                   [DigiflazzCallbackController::class, 'handle'])->name('digicallback');
    Route::post('/tokopaycallback',                                              [TokoPayCallbackController::class, 'handle']);
    Route::post('/paydisini/callback', [PaydisiniCallbackController::class, 'callbackTransaction']);
    Route::post('/tripay/callback', [\App\Http\Controllers\TripayController::class, 'handleCallback']);
    Route::post('/callback/tripay', [\App\Http\Controllers\TripayController::class, 'handleCallback']);
    Route::post('/callback/digiflazz', [\App\Http\Controllers\DigiflazzCallbackController::class, 'handle']);
});


Route::prefix('dash/')->middleware(['xss',  'sanitize',])->group(function () {
    Route::get('/login',                                                   [AdminLoginController::class, 'create'])->name('adminlogin');
    Route::post('/haryit',                                                  [AdminLoginController::class, 'store'])->name('post.adminlogin')->middleware('throttle:10,1');
});

Route::middleware(['auth', 'check.role'])->group(function () {
    Route::get('/dashboard',                                                     [DashboardController::class, 'create'])->name('admin.dashboard');
    Route::get('/pesanan',                                                       [AdminOrder::class, 'create'])->name('pesanan');
    Route::get('/latency',                                                       [AdminOrder::class, 'latency'])->name('latency');
    Route::get('/order-status/{order_id}/{status}',                              [AdminOrder::class, 'update']);
    
    
    Route::prefix('bangjeff')->middleware(['auth', 'check.role'])->group(function () {
    Route::get('/balance',                                                       [BangjeffdashboardController::class, 'balance'])->name('bangjeff.balance');
    Route::get('/product',                                                       [BangjeffdashboardController::class, 'getProduct'])->name('bangjeff.product');
});
    Route::prefix('topupedia')->middleware(['auth', 'check.role'])->group(function () {
    Route::get('/balance',                                                       [TopupediadashboardController::class, 'balance'])->name('topupedia.balance');
    Route::get('/product',                                                       [TopupediadashboardController::class, 'getProduct'])->name('topupedia.product');
});
    
    Route::prefix('digiflazz')->middleware(['auth', 'check.role'])->group(function () {
    Route::get('/produk',                                                        [DigiflazzdashboardController::class, 'harga'])->name('digiflazz.prices');
});

    Route::get('/berita',                                                        [Berita::class, 'create'])->name('berita');
    Route::post('/berita',                                                       [Berita::class, 'post'])->name('berita.post');
    Route::get('/berita/hapus/{id}',                                             [Berita::class, 'delete'])->name('berita.delete');
   
      
    Route::get('/tarik-saldo',                                                   [TokoPayController::class, 'tarikSaldo']);
    Route::post('/tarik-saldo',                                                  [TokoPayController::class, 'tarikSaldo'])->name('tarik-saldo');
    Route::get('/informasi-akun',                                                [TokoPayController::class, 'akun'])->name('informasi-akun');
    Route::get('/status-order',                                                  [TokoPayController::class, 'cekStatusOrder'])->name('status-order');
    Route::get('/withdrawals', function () {
        $withdrawals = Withdrawal::all();
        return view('withdrawals', ['withdrawals' => $withdrawals]);
    });
    Route::get('/kategori',                                                      [KategoriController::class, 'create'])->name('kategori');
    Route::post('/kategori',                                                     [KategoriController::class, 'store'])->name('kategori.post');
    Route::get('/kategori/hapus/{id}',                                           [KategoriController::class, 'delete'])->name('kategori.delete');
    Route::get('/kategori-status/{id}/{status}',                                 [KategoriController::class, 'update'])->name('kategori.update');
    Route::post('/kategori/update',                                              [KategoriController::class, 'patch'])->name('kategori.update.patch');
    Route::get('/kategori/{id}/detail',                                          [KategoriController::class, 'detail'])->name('kategori.detail');
    Route::post('/kategori/{id}/detail',                                         [KategoriController::class, 'patch'])->name('kategori.detail.update');
    Route::get('/produk/get/{provider?}',                                        [ProdukController::class, 'get'])->name('produk.get');
    Route::post('/produk/get/{provider?}',                                       [ProdukController::class, 'store'])->name('produk.get.post');
    Route::post('/produk/sync/',                                                 [ProdukController::class, 'sync'])->name('sync.produk.get.post');
    Route::post('/produk/synctopupedia/',                                        [ProdukController::class, 'synctopupedia'])->name('synctopupedia.produk.get.post');
    Route::get('/produk/get/{id}/detail',                                        [ProdukController::class, 'detail'])->name('detail.produk.get');
    Route::post('/produk/get/{id}/detail',                                       [ProdukController::class, 'patch'])->name('detail.produk.get.update');
    Route::get('/rating-customer',                                               [ratingAdminController::class, 'create'])->name('rating-customer');
    Route::delete('/rating-customer/{id}',                                       [ratingAdminController::class, 'destroy'])->name('rating-customer.destroy');
    Route::get('/layanan',                                                       [LayananController::class, 'create'])->name('layanan');
    Route::post('/layanan',                                                      [LayananController::class, 'store'])->name('layanan.post');
    Route::get('/layanan/hapus/{id}',                                            [LayananController::class, 'delete'])->name('layanan.delete');
    Route::get('/layanan-status/{id}/{status}',                                  [LayananController::class, 'update'])->name('layanan.update');
    Route::get('/layanan/{id}/detail',                                           [LayananController::class, 'detail'])->name('layanan.detail');
    Route::post('/layanan/{id}/detail',                                          [LayananController::class, 'patch'])->name('layanan.detail.update');
    
    Route::resources([ 'paket' => PaketController::class, 'paket-layanan' => PaketLayananController::class]);
    Route::post('paket-layanan-get-layanan',                                     [PaketLayananController::class, 'get_layanan'])->name('paket-layanan.get-layanan');
    Route::delete('/paket-layanan/destroy',                                      [PaketLayananController::class, 'destroy'])->name('paket-layanan.bulk-destroy');
    Route::get('/method',                                                        [MethodController::class, 'create'])->name('method');
    Route::post('/method',                                                       [MethodController::class, 'store'])->name('method.post');
    Route::get('/method/hapus/{id}',                                             [MethodController::class, 'delete'])->name('method.delete');
    Route::post('/method/update',                                                [MethodController::class, 'patch'])->name('method.update.patch');
    Route::get('/method/{id}/detail',                                            [MethodController::class, 'detail'])->name('method.detail');
    Route::post('/method/{id}/detail',                                           [MethodController::class, 'patch'])->name('method.detail.update');
    Route::get('/member',                                                        [MemberController::class, 'create'])->name('member');
    Route::get('/member/{id}/delete',                                            [MemberController::class, 'delete'])->name('member.delete');
    Route::post('/member',                                                       [MemberController::class, 'store'])->name('member.post');
    Route::post('/send-balance',                                                 [MemberController::class, 'send'])->name('saldo.post');
    Route::get('/member/{id}/detail',                                            [MemberController::class, 'show'])->name('member.detail');
    Route::post('/member/update',                                                [MemberController::class, 'patch'])->name('member.detail.update');
    Route::get('/user-deposit',                                                  [UserDepositController::class, 'create'])->name('userdeposit');
    Route::get('/user-deposit/{id}/{status}',                                    [UserDepositController::class, 'patch'])->name('confirm.deposit');
    Route::get('/whatsapp',                                                       [WhatsappController::class, 'create'])->name('whatsapp');
    Route::get('/voucher',                                                       [VoucherController::class, 'create'])->name('voucher');
    Route::post('/voucher',                                                      [VoucherController::class, 'store'])->name('voucher.post');
    Route::get('/voucher/{id}/delete',                                           [VoucherController::class, 'destroy'])->name('voucher.delete');
    Route::get('/voucher/{id}/detail',                                           [VoucherController::class, 'show'])->name('voucher.detail');
    Route::post('/voucher/{id}/update',                                          [VoucherController::class, 'patch'])->name('voucher.detail.update');
    Route::get('/data/giftskin',                                                 [DataGiftSkinController::class,'dataGiftSkin']);
    Route::get('/giftskin-status/{order_id}/{status}',                           [DataGiftSkinController::class,'statusGiftSkin']);
    Route::get('/giftskin/hapus/{id}',                                           [DataGiftSkinController::class,'hapusGiftSkin']);
    Route::get('/gift-skin', [GiftskinController::class, 'index'])->name('gift-skin');
    Route::get('/setting/web',                                                   [SettingWebController::class,'settingWeb']);
    Route::post('/setting/web',                                                  [SettingWebController::class,'saveSettingWeb']);
    Route::post('/setting/warnaweb',                                             [SettingWebController::class,'saveSettingWarna']);
    Route::post('/setting/tripay',                                               [SettingWebController::class,'saveSettingTripay']);
    Route::post('/setting/tokopay',                                              [SettingWebController::class,'saveSettingTokopay']);
    Route::post('/setting/digiflazz',                                            [SettingWebController::class,'saveSettingDigiflazz']);
    Route::post('/setting/wagateway',                                            [SettingWebController::class,'saveSettingWagateway']);
    Route::post('/setting/bangjeff',                                             [SettingWebController::class,'saveSettingBangjeff']);
    Route::post('/setting/paydisini',                                             [SettingWebController::class,'saveSettingPaydisini']);
    Route::get('/data/joki',                                                     [DataJokiController::class,'dataJoki']);
    Route::get('/joki-status/{order_id}/{status}',                               [DataJokiController::class,'statusJoki']);
    Route::get('/joki/hapus/{id}',                                               [DataJokiController::class,'hapusJoki']);
    
    // SUS Analysis Admin
    Route::get('/admin/sus', [SusAdminController::class, 'index'])->name('admin.sus.index');
    Route::get('/admin/sus/manage', [SusAdminController::class, 'manage'])->name('admin.sus.manage');
    Route::post('/admin/sus/question', [SusAdminController::class, 'storeQuestion'])->name('admin.sus.question.store');
    Route::post('/admin/sus/question/{id}', [SusAdminController::class, 'updateQuestion'])->name('admin.sus.question.update');
    Route::get('/admin/sus/question/delete/{id}', [SusAdminController::class, 'destroyQuestion'])->name('admin.sus.question.delete');
    Route::get('/admin/sus/export', [SusAdminController::class, 'exportExcel'])->name('admin.sus.export');
});

// User SUS routes
Route::get('/sus', [SusController::class, 'index'])->name('sus.index');
Route::post('/sus', [SusController::class, 'store'])->name('sus.store');