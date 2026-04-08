@extends('layouts.user')

@section('custom_style')


<style>

            .rate {
            background-color: var(--warna_1);
            box-shadow: 0 0 6px 1px var(--warna_1);
            color: #fffbfb;
            padding: 0 .5em;
            font-weight: 800;
            text-align: center;
            border-radius: 1em;
                
            }
            /* Efek untuk elemen promo */
            .promo {
                position: absolute;
                right: 0;
                bottom: -4px;
                background-color: #E2EAF4;
                color: #1f1f1f;
                font-weight: 800;
                text-align: center;
                border-radius: 1em;
                padding: 0 .5em;
                box-shadow: 0 0 6px 1px #E2EAF4;
                overflow: hidden;
                background-size: 200% 200%;
                animation: lightSweep 2s ease-in-out infinite;
            }
            
            /* Keyframe animasi light sweep */
            @keyframes lightSweep {
                0% {
                    background-image: linear-gradient(135deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0) 40%, rgba(255, 255, 255, 0.6) 50%, rgba(255, 255, 255, 0) 60%, rgba(255, 255, 255, 0) 100%);
                    background-position: 200% 200%;
                }
                100% {
                    background-image: linear-gradient(135deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0) 40%, rgba(255, 255, 255, 0.6) 50%, rgba(255, 255, 255, 0) 60%, rgba(255, 255, 255, 0) 100%);
                    background-position: -200% -200%;
                }
            }

            .scroll-container {
                display: flex;
                overflow-x: auto;
                padding: 1rem 0;
                white-space: nowrap;
                scrollbar-width: thin;
            }
        
            .button-3d {
                background: linear-gradient(145deg, var(--warna_1), var(--warna_3));
                border-radius: 12px;
                color: #f2efef;
                font-weight: bold;
                margin: 0 5px;
                padding: 7px 20px;
                transition: transform 0.3s;
                display: inline-block;
                cursor: pointer;
            }
            
            .button-3d {
            transition: transform 0.1s ease;
            }

            .button-3d:active {
            transform: scale(0.9);
            }
            
            /* Animasi menghilang */
            .fade-out {
                animation: fadeOut 0.2s forwards;
            }
            
            /* Animasi muncul */
            .pop-in {
                animation: popIn 0.3s ease-out forwards;
            }
            
            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: scale(1);
                }
                to {
                    opacity: 0;
                    transform: scale(0.8);
                }
            }
            
            @keyframes popIn {
                from {
                    opacity: 0;
                    transform: scale(0.8);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .product-list,.rounded-xl{
            	border-radius:.75rem
            }
            .swal2-popup{
            	box-sizing:border-box;
            	grid-template-columns:minmax(0,100%);
            	font-family:inherit
            }
            .bg-murky-300,.bg-murky-600,.bg-orange-700,.bg-primary-5400{
            	--tw-bg-opacity:1
            }
            .product-list:active,.ring-offset-murky-800{
            	--tw-ring-offset-color:#34373b
            }
            .product-list:active,.ring-offset-2{
            	--tw-ring-offset-width:2px
            }
            .product-list:active,.ring-primary-500{
            	--tw-ring-opacity:1;
            	--tw-ring-color:var(--warna_3)
            }
            .decoration-destructive{
            	text-decoration-color:#dc2626
            }
            @media (max-width:767px){
            	.hide-mobile{
            		display:none!important
            }
            }
            @media (min-width:768px){
            	.show-desktop{
            		display:block!important
            }
            }
            .bg-orange-700{
            	background-color:rgb(132 130 129)
            }
            .bg-background,.bg-murky-600,.bg-secondary{
            	background-color:var(--warna_1)
            }
            .text-foreground{
            	color:#ebeaef
            }
            .bg-murky-300{
            	background-color:#ebeaef80
            }
            .product-list{
            	min-height:10px;
            	box-shadow:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06)
            }
            .product-list:active,.ring-2{
            	--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--warna_1);
            	--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            	box-shadow:var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow,0 0 #0000)
            }
            .product-list.active{
            	background:#fff!important;
            	box-shadow:0 0 10px rgba(255,255,255,.5),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow,0 0 #0000);
            	transition:box-shadow .2s
            }
            .product-list.active .btn-order{
            	box-shadow:0 0 20px rgba(220,220,220,.4)
            }
            .bg-product{
            	background:#c3c9cc
            }
            .product-list img{
            	display:flex;
            	float:right;
            	margin-top:0
            }
            .productlogo{
            	width:32px;
            	right:5%
            }
            .rounded-b-md{
            	border-bottom-left-radius:13px;
            	border-bottom-right-radius:13px
            }
            .border-murky-500{
            	--tw-border-opacity:1;
            	border-color:rgb(255 255 255)
            }
            .border-dashed{
            	border-style:dashed
            }
            .border-t{
            	border-top-width:1px
            }
            .divider{
            	border-top:3px solid #fff;
            	margin:10px 0;
            	width:5%
            }
            .swal2-popup{
            	background:#333333eb
            }
            .hover\:bg-orange-400:hover,.hover\:bg-primary-400:hover{
            	--tw-bg-opacity:1;
            	background-color:#ebeaef29
            }
            .load{
            	background:rgba(31,33,46,.5);
            	position:fixed;
            	top:0;
            	bottom:0;
            	left:0;
            	right:0;
            	z-index:10000;
            	background-repeat:no-repeat;
            	background-size:40px;
            	background-position:center;
            	display:none
            }
            .load.show{
            	display:block;
            	background-image:url(/assets/image/loading-order.gif)
            }
            .swal2-popup{
            	display:none;
            	position:relative;
            	width:25em;
            	max-width:100%;
            	padding:10px;
            	border:none;
            	border-radius:10px;
            	background:#1e2022eb;
            	color:#dfdede!important;
            	font-size:1rem
            }
            @keyframes slideIn{
            	0%{
            		transform:translateY(-100%);
            		opacity:0
            }
            	100%{
            		transform:translateY(0);
            		opacity:1
            }
            }
            #react-notif{
            	position:fixed;
            	z-index:9999;
            	top:16px;
            	right:16px;
            	pointer-events:none;
            	max-width:300px
            }
            .toast{
            	padding:16px;
            	color:#fff;
            	background-color:rgba(244,63,94,.8);
            	position:relative;
            	margin-bottom:16px;
            	border-radius:8px;
            	display:flex;
            	justify-content:space-between;
            	align-items:center;
            	animation:.3s forwards slideIn;
            	opacity:0
            }
            .toast:last-child{
            	transform:translateY(0)
            }
            .toast-icon{
            	padding:8px;
            	border-radius:50%;
            	background-color:#fff;
            	margin-right:8px
            }
            .bar,.progress{
            	border-radius:5px
            }
            .toast-message{
            	flex:1
            }
            .toast-close{
            	cursor:pointer
            }
            .toast.success{
            	background-color:rgba(34,197,94,.8)
            }
            .text-warning{
            	color:#eab308
            }
            .text-info{
            	color:#0ea5e9
            }
            .text-success{
            	color:#22c55e
            }
            .bg-primary-5400{
            	background-color:var(--warna_3)
            }
            .to-primary-600{
            	--tw-gradient-to:#022c56 var(--tw-gradient-to-position)
            }
            .from-primary-400{
            	--tw-gradient-from:#022c56 var(--tw-gradient-from-position);
            	--tw-gradient-to:rgba(251, 138, 60, 0) var(--tw-gradient-to-position);
            	--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)
            }
            .text-sm\/6{
            	font-size:.875rem;
            	line-height:1.5rem
            }
            .text-besar{
            	font-size:50px;
            	font-weight:700
            }
            .rating-list{
            	list-style:none;
            	padding:0;
            	margin:0
            }
            .rating,.rating-item{
            	display:flex;
            	align-items:center
            }
            .stars{
            	display:flex;
            	align-items:center;
            	gap:5px;
            	font-weight:700;
            	color:#333
            }
            .star{
            	color:#ffc107;
            	width:20px
            }
            .bar{
            	height:20px;
            	width:350px;
            	background-color:#ddd;
            	margin:1 1px
            }
            .progress{
            	height:100%;
            	background-color:#ffc107
            }
            .count{
            	min-width:50px;
            	text-align:right
            }
</style>

@endsection


@section('content')

@include('components.user.navbar')

      
    <div class="load"></div>
    <!--
    <div class="relative h-56 w-full bg-murky-800 lg:h-[340px]">
    <img src="{{ $kategori->thumbnail }}" class="object-cover object-center"
    style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;"/>
      <ul class="circles" style="background-color: var(--warna_1)">
     
     <section class="relative flex items-center overflow-hidden bg-secondary/50 px-4 py-m lg:min-h-[521.96px]">
   @php
    $positions = [
        ['left' => 1130, 'delay' => 0.686975, 'duration' => 8],
        ['left' => -350, 'delay' => 0.670151, 'duration' => 8],
        ['left' => 563, 'delay' => 0.632454, 'duration' => 9],
        ['left' => -969, 'delay' => 0.524996, 'duration' => 5],
        ['left' => -1153, 'delay' => 0.460272, 'duration' => 8],
        ['left' => -560, 'delay' => 0.223791, 'duration' => 6],
        ['left' => -1287, 'delay' => 0.406558, 'duration' => 4],
        ['left' => 211, 'delay' => 0.475533, 'duration' => 6],
        ['left' => -63, 'delay' => 0.394929, 'duration' => 5],
        ['left' => -112, 'delay' => 0.78249, 'duration' => 2],
        ['left' => 946, 'delay' => 0.353787, 'duration' => 5],
        ['left' => 275, 'delay' => 0.309607, 'duration' => 5],
        ['left' => 1216, 'delay' => 0.35162, 'duration' => 8],
        ['left' => -210, 'delay' => 0.413144, 'duration' => 7],
        ['left' => -842, 'delay' => 0.395388, 'duration' => 6],
        ['left' => -323, 'delay' => 0.582248, 'duration' => 4],
        ['left' => 278, 'delay' => 0.710367, 'duration' => 4],
        ['left' => -736, 'delay' => 0.564896, 'duration' => 6],
        ['left' => -800, 'delay' => 0.206357, 'duration' => 7],
        ['left' => -1118, 'delay' => 0.628613, 'duration' => 9],
        ['left' => 1361, 'delay' => 0.529785, 'duration' => 7],
        ['left' => -11, 'delay' => 0.64863, 'duration' => 6],
        ['left' => -678, 'delay' => 0.701722, 'duration' => 3],
        ['left' => -170, 'delay' => 0.366231, 'duration' => 5],
        ['left' => 946, 'delay' => 0.521904, 'duration' => 7],
        ['left' => 1364, 'delay' => 0.484818, 'duration' => 9],
        ['left' => 943, 'delay' => 0.502043, 'duration' => 3],
        ['left' => 1296, 'delay' => 0.577243, 'duration' => 7],
        ['left' => 1273, 'delay' => 0.273317, 'duration' => 5],
        ['left' => -1306, 'delay' => 0.556245, 'duration' => 7],
        ['left' => -360, 'delay' => 0.344508, 'duration' => 5],
        ['left' => 306, 'delay' => 0.332693, 'duration' => 6],
        ['left' => 312, 'delay' => 0.250245, 'duration' => 9],
        ['left' => 649, 'delay' => 0.607517, 'duration' => 2],
        ['left' => 13, 'delay' => 0.379304, 'duration' => 6],
        ['left' => 1269, 'delay' => 0.586079, 'duration' => 5],
        ['left' => -798, 'delay' => 0.675148, 'duration' => 4],
        ['left' => 1199, 'delay' => 0.515393, 'duration' => 6],
        ['left' => 304, 'delay' => 0.799655, 'duration' => 8],
            ];
        @endphp
        
        @for ($i = 0; $i < count($positions); $i++)
            @php
                $left = $positions[$i]['left'];
                $delay = $positions[$i]['delay'];
                $duration = $positions[$i]['duration'];
            @endphp
            <span class="absolute left-1/2 top-1/2 h-1 w-1 rotate-[215deg] animate-meteor-effect rounded-[9999px] bg-white shadow-[0_0_0_1px_#ffffff10] before:absolute before:top-1/2 before:h-[1px] before:w-[80px] before:-translate-y-[0%] before:transform before:bg-gradient-to-r before:from-white before:to-transparent before:content-['']"
                style="top: -20px; left: {{ $left }}px; animation-delay: {{ $delay }}s; animation-duration: {{ $duration }}s;"></span>
        @endfor

               </section>
 </ul>
     <div
        class="container relative top-10 z-20  flex h-full w-full flex-col justify-end gap-4 py-4 md:top-[5rem] lg:py-8">
        <article class="flex items-start gap-4">
          <div class="product-thumbnail-container">
            <img src="{{ $kategori->thumbnail }}" width="300" height="300"
              class="z-20 -mb-14 aspect-square w-32 rounded-2xl object-cover shadow-2xl md:-mb-20 md:w-60"
              style="color: transparent;" alt="" />
          </div>
        </article>
      </div>
</div> -->

<main class="relative">
    <div class="relative">
        <div class="relative h-56 w-full w-tih bg-card lg:h-[340px]">
            <img
                alt="{{ $kategori->thumbnail }}" fetchpriority="high" decoding="async" data-nimg="fill" class="object-cover object-center" sizes="80vw" src="{{ $kategori->banner }}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;"/>
            <div class="container relative top-10 z-20 flex h-full flex-col justify-end gap-4 py-4 md:top-[5rem] lg:py-8">
                <article class="flex items-start gap-4">
                    <div class="product-thumbnail-container">
                        <img alt="" loading="lazy" width="300" height="300" decoding="async" data-nimg="1" class="z-20 -mb-14 aspect-square w-32 rounded-2xl object-cover shadow-2xl md:-mb-20 md:w-60" sizes="100vw" src="{{ $kategori->thumbnail }}" style="color: transparent;"/>
                    </div>
                </article>
            </div>
        </div>
   
    <div class="bg-title-product min-h-[120px] shadow-2xl md:min-h-[140px]">
    <div class="container">
        <div class="ml-[8.5rem] pt-4 md:ml-[15.5rem] md:pt-5">
            <div>
                <h2 class="truncate text-base font-semibold text-white md:text-2xl">{{ $kategori->nama }}</h2>
                <p class="truncate text-xs text-white md:text-base">{{ $kategori->sub_nama }}</p>
            </div>
      
        <div class="py-4 sm:py-0">
    <div class="mt-4 flex flex-col gap-2 text-xs sm:flex-row sm:items-center sm:gap-8 sm:text-sm/6">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap h-5 w-5 text-warning">
                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
            </svg>
            <span>Proses Cepat</span>
        </div>
        <div class="flex items-center gap-2">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-headset h-5 w-5 text-info"
            >
                <path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z"></path>
                <path d="M21 16v2a4 4 0 0 1-4 4h-5"></path>
            </svg>
            <span>Layanan Chat 24/7</span>
        </div>
        <div class="flex items-center gap-2">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-earth h-5 w-5 text-success"
            >
                <path d="M21.54 15H17a2 2 0 0 0-2 2v4.54"></path>
                <path d="M7 3.34V5a3 3 0 0 0 3 3v0a2 2 0 0 1 2 2v0c0 1.1.9 2 2 2v0a2 2 0 0 0 2-2v0c0-1.1.9-2 2-2h3.17"></path>
                <path d="M11 21.95V18a2 2 0 0 0-2-2v0a2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05"></path>
                <circle cx="12" cy="12" r="10"></circle>
            </svg>
            <span>Instant</span>
        </div>
    </div>
</div>

        </div>
      </div>
    </div>
    <div class="container grid grid-cols-3 gap-8 pb-8 pt-8">
      <div class="col-span-3 md:col-span-1">
        <div class="sticky top-24 flex flex-col space-y-8">
          <div class="rounded-xl bg-murky-800 shadow-2xl">
            <div class="prose prose-sm px-4 py-2 pb-8 text-xs text-white sm:px-6">
              <div>

                        {!! htmlspecialchars_decode($kategori->deskripsi_game) !!}
              </div>
                    <div class="mt-2 flex flex-col border-t border-dashed  text-card-foreground">
                </div>
            </div>
         </div>
          
          
@include('components.user.reviews', ['is_mobile' => false])
            </div>

      </div>
     
        @if(in_array($kategori->tipe, ['joki', 'jokigendong', 'giftskin' , 'vilogml']))
        @if($kategori->tipe === 'joki')
            
      <ul class="col-span-3 flex flex-col space-y-8 md:col-span-2">
@include('components.user.account-data')
                    <!--end section input-->
     
            
                @if(in_array($kategori->kode, ['joki-ranked']))
<div class="popup-structureeee popup-slide flex min-h-full items-center justify-center p-4 text-center sm:p-0" id="popupdppp">
  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
      <div class="popup-content relative w-full transform overflow-hidden bg-murky-900 text-left shadow-xl transition-all sm:my-8 sm:max-w-3xl !rounded-2xl opacity-100 sm:scale-100" id="headlessui-dialog-panel-weekly-diamond-pass" data-headlessui-state="open">
        <div class="absolute right-0 top-0 block pr-4 pt-4"></div>
        <div class="w-full pb-4 flex flex-col items-center justify-center">
          <h2 class="max-w-xl pt-1 text-center text-sm font-semibold text-white mt-4">Informasi Sebelum Order Jasa Joki Rank</h2>
          <div class="prose prose-sm px-2 text-xs text-foreground">
            <div>
              <p><strong>
                  <em class="text-white">Mohon luangkan waktu untuk membaca catatan Informasi sebelum melakukan pemesanan.<br /><br /></em>
              </strong>Waktu Pengecekan Orderan :<br />Orderan yang sudah dibayarkan akan kami cek setiap hari mulai pukul 07.00 - 22.00 WIB.<br />Untuk orderan yang melewati batas waktu pengecekan, akan kami proses pada jam kerja di hari berikutnya.<br /><br />Berikut Syarat Dan Ketentuan Sebelum Order Jasa Joki :</p>
              <p class="selectable-text copyable-text iq0m558w g0rxnol2" dir="ltr">
                <span class="selectable-text copyable-text">1. Data Akun : Lengkapi data dengan benar, termasuk kapitalisasi huruf.<br /></span>2. Pilihan Hero : Minimal tiga pilihan hero, sebagai alternatif jika hero sedang di pick/ban.<br />
                <span class="selectable-text copyable-text">3. Verifikasi Akun : Nonaktifkan Untuk Mempermudah Login.<br /></span>
                <span class="selectable-text copyable-text">4. Tipe Akun : Utamakan Akun yang dijoki adalah akun utama, bukan akun beli atau bekas GB untuk menghindari BAN.<br /></span>
                <span class="selectable-text copyable-text">5. Login Tanpa izin : Berakibat pembatalan joki dan hangusnya pembayaran.<br /></span>
                <span class="selectable-text copyable-text">6. Kesabaran: Tunggu sesuai estimasi dan jangan spam chat admin.<br /></span>
                <span class="selectable-text copyable-text">7. Masalah Login : Admin/Bot akan menghubungi jika ada kendala.<br /></span>
                <span class="selectable-text copyable-text">8. Keterlambatan Proses : Hubungi kami jika belum diproses dalam 1-3 jam.<br /></span>
                <span class="selectable-text copyable-text">9. Setelah Joki Selesai : Tetapi belum menerima laporan dari Admin/BOT, jangan di login terlebih dahulu karena ada benefit bonus.<br /></span>
                <span class="selectable-text copyable-text">10. Tanggung Jawab Pasca-Joki : Tanggung jawab atas akun berakhir setelah joki selesai.<br /></span>
                <span class="selectable-text copyable-text">11. Konfirmasi Selesai : Akan dihubungi oleh Admin/BOT dan Customer Bisa Cek Malalui (Cek Transaksi)<br /><br /></span>
                Jika Butuh Bantuan Harap Hubungi Admin {{ ENV('APP_NAME') }}<br />Terimakasih
              </p>
            </div>
          </div>
          <div class="flex justify-center bg-secondary px-4 py-2 rounded-xl">
            <button class="items-center justify-center whitespace-nowrap text-xs font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-8" type="button" name="popup" id="closePopupButton">
              OK, Saya Mengerti
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let popup = document.getElementById("popupdppp");
    let closeButton = document.getElementById("closePopupButton");

    if (popup) {
        popup.classList.add("show");
    }

    if (closeButton) {
        closeButton.addEventListener("click", function() {
            if (popup) {
                popup.classList.remove("show");
            }
        });
    }
});
</script>
<style>
    .popup-structureeee {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(128, 128, 128, 0.7); 
  justify-content: center;
  align-items: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.popup-content {
  background: #212121;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
  transform: translateY(100%);
  transition: transform 0.3s ease;
}

.popup-structureeee.show .popup-content {
  transform: translateY(0);
}

.popup-structureeee.show {
  display: flex;
  opacity: 1;
}

</style>
@endif

<script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            document.getElementById('skeleton-loader').style.display = 'none';
            document.getElementById('itemList').classList.remove('hidden');
        }, 1500);
    });
</script>

@include('components.user.nominal-selection')
               
               
          

@include('components.user.quantity-selection')
           
            <div class="rounded-xl bg-murky-800 shadow-2xl" id="section-payment-channel">
              <div class="flex border-b border-murky-600">
                <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold"> 4 </div>
                <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4"> Pilih Metode Pembayaran </h3>
              </div>
              
                 <div id="skeleton-loaderr" class="skeleton-loader grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 px-4 mt-4 py-4">
                @for ($i = 0; $i < 4; $i++)
                    <div class="ph-item melpaaaaaa">
                        <div class="ph-col-12">
                            <div class="ph-picture"></div>
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
              <dl id="paymentList" class="w-full flex-col space-y-4 p-4 sm:p-6 hidden" x-data="{ selected: null, paymentSelected: '' }">
                  
      
                
                  <!--QRIS-->
                @foreach($pay_method as $p) 
                    @if($p->tipe == 'qris')
                                            <div x-bind:class="{ 'bg-white bj-shadow': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" class="relative flex cursor-pointer method-list rounded-xl border border-transparent bg-murky-200 p-4 shadow-sm outline-none md:p-4 hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" role="radio" aria-checked="false" method-id="{{$p->code}}" name="paymentMethod" @click="paymentSelected = '{{$p->code}}'">
                            <div class="flex items-center gap-2 max-w-xs">
                                <input type="radio" id="method_51" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                                <label for="method_51"></label>
                                <img src="{{$p->images}}" alt="qris" width="55" height="40" />
                                <div>
                                    <span class="block font-bjcredits text-xs font-semibold text-murky-800 sm:text-sm" id="headlessui-label-:riu:">QRIS</span>
                                    <p class="block text-xxs text-murky-800 sm:text-xs hargapembayaran" id="{{$p->code}}">Rp 0</p>
                                </div>
                            </div>
                            <div class="max-w-xs">
                                <div class="relative text-sm font-semibold text-murky-800 sm:text-base">
                                </div>
                            </div>
                            <div class="flex aspect-square w-8 items-center">
                                <div class="w-[4rem] absolute aspect-square -top-[9px] -right-[9px] overflow-hidden rounded-sm">
                                    <div class="absolute top-0 left-0 bg-orange-500 h-2 w-2"></div>
                                    <div class="absolute bottom-0 right-0 bg-orange-500 h-2 w-2"></div>
                                    <div class="absolute block w-square-diagonal py-1 text-center text-xxs font-semibold uppercase bottom-0 right-0 rotate-45 origin-bottom-right shadow-sm bg-orange-500 text-white">BEST PRICE</div>
                                </div>
                            </div>
                        </div>
                    @endif 
                @endforeach
                
                <!--end QRIS-->
                
                
                <!-- E-Wallet -->
                <div class="flex w-full transform flex-col justify-between rounded-xl bg-murky-600 text-left text-sm font-medium text-white duration-300 focus:outline-none accordion-header" data-state="">
                  <dt>
                    <button class="w-full disabled:opacity-75" id="disclosure-button-1" type="button" @click="selected !== 3 ? selected = 3 : selected = null" aria-expanded="false" aria-controls="disclosure-panel-1">
                      <div class="flex w-full justify-between px-4 py-2">
                        <span class="transform text-base font-medium leading-7 duration-300">
                          <div>E-Wallet</div>
                        </span>
                        <span class="ml-6 flex h-7 items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6 transform duration-300" x-bind:class="selected == 3 ? 'rotate-180' : 'rotate-0'">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                          </svg>
                        </span>
                      </div>
                    </button>
                    <div class="relative overflow-hidden transition-all max-h-0 duration-700 " x-ref="container1" x-bind:style="selected == 3 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : 'max-height: 0'" style="max-height: 239px;">
                      <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-1">
                        <div id="radiogroup-1" role="radiogroup" aria-labelledby="label-1">
                          <div id="eWalletList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none"> @foreach($pay_method as $p) @if($p->tipe == 'e-walet') <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" method-id="{{$p->code}}" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 bg-white bj-shadow hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out " id="radio-group-{{$p->code}}" role="radio" aria-checked="false" method-id="{{$p->code}}" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" tabindex="0" aria-labelledby="label-{{$p->code}}:" aria-describedby="description-{{$p->code}}" @click="paymentSelected = '{{$p->code}}'">
                              <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                              <label for="method_{{$p->id}}"></label>
                              <span class="flex w-full">
                                <span class="flex w-full flex-col justify-between">
                                  <div>
                                    <span class="block text-xs font-semibold text-murky-800">
                                      {{$p->name}}
                                    </span>
                                    <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>
                                     <hr>
                                  </div>
                                  <div class="flex w-full items-center justify-between">
                                    <div class="mt-1">
                                      <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800  text-dark.meltihhh">
                                        <h6 class="hargapembayaran" id="{{$p->code}}"></h6>
                                      </div>
                                    </div>
                                    <div class="relative aspect-[6/2] w-10">
                                      <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === '{{$p->code}}', 'grayscale': paymentSelected !== '{{$p->code}}' }" class="object-scale-down grayscale-0" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />
                                    </div>
                                  </div>
                                </span>
                              </span>
                            </div> @endif @endforeach </div>
                        </div>
                      </div>
                    </div>
                    <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo3" x-bind:style="selected == 3 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 3 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">
                      <div class="flex justify-end gap-x-2"> @foreach($pay_method as $p) @if($p->tipe == 'e-walet') <div class="relative aspect-[6/2] w-10">
                          <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />
                        </div> @endif @endforeach </div>
                    </div>
                  </dt>
                </div>
                
              
                <!-- Virtual Account -->
                <div class="flex w-full transform flex-col justify-between rounded-xl bg-murky-600 text-left text-sm font-medium text-white duration-300 focus:outline-none accordion-header" data-state="">
                  <dt>
                    <button class="w-full disabled:opacity-75" id="disclosure-button-2" type="button" @click="selected !== 5 ? selected = 5 : selected = null" aria-expanded="false" aria-controls="disclosure-panel-2">
                      <div class="flex w-full justify-between px-4 py-2">
                        <span class="transform text-base font-medium leading-7 duration-300">
                          <div>Virtual Account</div>
                        </span>
                        <span class="ml-6 flex h-7 items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6 transform duration-300" x-bind:class="selected == 5 ? 'rotate-180' : 'rotate-0'">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                          </svg>
                        </span>
                      </div>
                    </button>
                    <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container2" x-bind:style="selected == 5 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : 'max-height: 0'" style="max-height: 239px;">
                      <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-2">
                        <div id="radiogroup-2" role="radiogroup" aria-labelledby="label-2">
                          <div id="virtualAccountList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none"> @foreach($pay_method as $p) @if($p->tipe == 'virtual-account') <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" method-id="{{$p->code}}" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 bg-white bj-shadow hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" method-id="{{$p->code}}" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" tabindex="0" aria-labelledby="label-{{$p->code}}:" aria-describedby="description-{{$p->code}}" @click="paymentSelected = '{{$p->code}}'">
                              <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                              <label for="method_{{$p->id}}"></label>
                              <span class="flex w-full">
                                <span class="flex w-full flex-col justify-between">
                                  <div>
                                    <span class="block text-xs font-semibold text-murky-800">
                                      {{$p->name}}
                                    </span>
                                    <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>
                                     <hr>
                                  </div>
                                  <div class="flex w-full items-center justify-between">
                                    <div class="mt-1">
                                      <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800  text-dark.meltihhh">
                                        <h6 class="hargapembayaran" id="{{$p->code}}"></h6>
                                      </div>
                                    </div>
                                    <div class="relative aspect-[6/2] w-10">
                                      <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === 'QRIS', 'grayscale': paymentSelected !== 'QRIS' }" class="object-scale-down grayscale-0" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />
                                    </div>
                                  </div>
                                </span>
                              </span>
                            </div> @endif @endforeach </div>
                        </div>
                      </div>
                    </div>
                    <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo5" x-bind:style="selected == 5 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 5 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">
                      <div class="flex justify-end gap-x-2"> @foreach($pay_method as $p) @if($p->tipe == 'virtual-account') <div class="relative aspect-[6/2] w-10">
                          <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />
                        </div> @endif @endforeach </div>
                    </div>
                  </dt>
                </div>
                <!-- Convenience Store -->
                  
                 <!-- Convenience Store -->
                <div class="flex w-full transform flex-col justify-between rounded-xl bg-murky-600 text-left text-sm font-medium text-white duration-300 focus:outline-none accordion-header" data-state="">
                  <dt>
                    <button class="w-full disabled:opacity-75" id="disclosure-button-3" type="button" @click="selected !== 4 ? selected = 4 : selected = null" aria-expanded="false" aria-controls="disclosure-panel-3">
                      <div class="flex w-full justify-between px-4 py-2">
                        <span class="transform text-base font-medium leading-7 duration-300">
                          <div>Convenience Store</div>
                        </span>
                        <span class="ml-6 flex h-7 items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6 transform duration-300" x-bind:class="selected == 4 ? 'rotate-180' : 'rotate-0'">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                          </svg>
                        </span>
                      </div>
                    </button>
                    <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container3" x-bind:style="selected == 4 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : 'max-height: 0'" style="max-height: 239px;">
                      <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-3">
                        <div id="radiogroup-3" role="radiogroup" aria-labelledby="label-3">
                          <div id="convenienceStoreList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none"> @foreach($pay_method as $p) @if($p->tipe == 'convenience-store') <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" method-id="{{$p->code}}" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 bg-white bj-shadow hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" method-id="{{$p->code}}" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" tabindex="0" aria-labelledby="label-{{$p->code}}:" aria-describedby="description-{{$p->code}}" @click="paymentSelected = '{{$p->code}}'">
                              <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                              <label for="method_{{$p->id}}"></label>
                              <span class="flex w-full">
                                <span class="flex w-full flex-col justify-between">
                                  <div>
                                    <span class="block text-xs font-semibold text-murky-800">
                                      {{$p->name}}
                                    </span>
                                    <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>
                                     <hr>
                                  </div>
                                  <div class="flex w-full items-center justify-between">
                                    <div class="mt-1">
                                      <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800  text-dark.meltihhh" id="">
                                        <h6 class="hargapembayaran" id="{{$p->code}}"></h6>
                                      </div>
                                    </div>
                                    <div class="relative aspect-[6/2] w-10">
                                      <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === 'QRIS', 'grayscale': paymentSelected !== 'QRIS' }" class="object-scale-down grayscale-0" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />
                                    </div>
                                  </div>
                                </span>
                              </span>
                            </div> @endif @endforeach </div>
                        </div>
                      </div>
                    </div>
                    <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo4" x-bind:style="selected == 4 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 4 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">
                      <div class="flex justify-end gap-x-2"> @foreach($pay_method as $p) @if($p->tipe == 'convenience-store') <div class="relative aspect-[6/2] w-10">
                          <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />
                        </div> @endif @endforeach </div>
                    </div>
                  </dt>
                </div>
                <!-- pulsa -->
                
                <!--<div class="flex w-full transform flex-col justify-between rounded-xl bg-murky-600 text-left text-sm font-medium text-white duration-300 focus:outline-none accordion-header" data-state="">-->
                <!--  <dt>-->
                <!--    <button class="w-full disabled:opacity-75" id="disclosure-button-3" type="button" @click="selected !== 9 ? selected = 9 : selected = null" aria-expanded="false" aria-controls="disclosure-panel-3">-->
                <!--      <div class="flex w-full justify-between px-4 py-2">-->
                <!--        <span class="transform text-base font-medium leading-7 duration-300">-->
                <!--          <div>Pulsa</div>-->
                <!--        </span>-->
                <!--        <span class="ml-6 flex h-7 items-center">-->
                <!--          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6 transform duration-300" x-bind:class="selected == 9 ? 'rotate-180' : 'rotate-0'">-->
                <!--            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>-->
                <!--          </svg>-->
                <!--        </span>-->
                <!--      </div>-->
                <!--    </button>-->
                <!--    <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container3" x-bind:style="selected == 9 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : 'max-height: 0'" style="max-height: 239px;">-->
                <!--      <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-3">-->
                <!--        <div id="radiogroup-3" role="radiogroup" aria-labelledby="label-3">-->
                <!--          <div id="convenienceStoreList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none"> -->
                <!--          @foreach($pay_method as $p) @if($p->tipe == 'pulsa') -->
                         
                <!--          <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200 ': paymentSelected !== '{{$p->code}}' }" method-id="{{$p->code}}" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 bg-white bj-shadow hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" method-id="{{$p->code}}" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" tabindex="0" aria-labelledby="label-{{$p->code}}:" aria-describedby="description-{{$p->code}}" @click="paymentSelected = '{{$p->code}}'">-->
                <!--              <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />-->
                <!--              <label for="method_{{$p->id}}"></label>-->
                <!--              <span class="flex w-full">-->
                <!--                <span class="flex w-full flex-col justify-between">-->
                <!--                  <div>-->
                <!--                    <span class="block text-xs font-semibold text-murky-800">-->
                <!--                      {{$p->name}}-->
                <!--                    </span>-->
                <!--                    <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>-->
                <!--                     <hr>-->
                <!--                  </div>-->
                <!--                  <div class="flex w-full items-center justify-between">-->
                <!--                    <div class="mt-1">-->
                <!--                      <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800  text-dark.meltihhh" id="">-->
                <!--                        <h6 class="hargapembayaran" id="{{$p->code}}"></h6>-->
                <!--                      </div>-->
                <!--                    </div>-->
                <!--                    <div class="relative aspect-[6/2] w-10">-->
                <!--                      <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === 'QRIS', 'grayscale': paymentSelected !== 'QRIS' }" class="object-scale-down grayscale-0" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />-->
                <!--                    </div>-->
                <!--                  </div>-->
                <!--                </span>-->
                <!--              </span>-->
                <!--            </div> @endif @endforeach </div>-->
                <!--        </div>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--    <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo4" x-bind:style="selected == 9 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 9 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">-->
                <!--      <div class="flex justify-end gap-x-2"> @foreach($pay_method as $p) @if($p->tipe == 'pulsa') <div class="relative aspect-[6/2] w-10">-->
                <!--          <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />-->
                <!--        </div> @endif @endforeach </div>-->
                <!--    </div>-->
                <!--  </dt>-->
                <!--</div>-->
                
                
                
              </dl>
            </div>

                 <div class="rounded-xl bg-murky-800 shadow-2xl" id="promooo">
                 
                     <div class="flex border-b border-murky-600">
                <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold"> 5 </div>
                <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4"> Kode Promo </h3>
              </div>
                  <div class="p-4 sm:px-6 sm:pb-6">
                    <label for="voucher" class="block text-xs font-medium text-white pb-2">Kode Promo</label>
                    <div class="flex items-center space-x-2">
                      <div class="grow">
                        <div class="flex flex-col items-start">
                          <input class="PhoneInputInput relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white" type="text" id="voucher" name="voucher" placeholder="Masukkan Kode Promo Anda" required/>
                        </div>
                      </div>
                      <button type="button" id="btn-check" class="flex items-center justify-center rounded-md bg-primary-5400 py-2 px-4 text-xs font-semibold text-white hover:bg-orange-400 disabled:cursor-not-allowed disabled:opacity-75"> Gunakan </button>
                    </div>
                    <div class="pt-2 text-xs text-red-500"></div>


                  </div>
                </div>
                
                
                
@include('components.user.whatsapp-number')

                
                
                      <div class="inset-x-0 bottom-0 z-10  !mt-0 shad sticky rounded-t-lg pb-4 flex flex-col gap-4 bg-background">
                  <div class=" space-y-0">
                    <div class="rounded-lg border border-dashed bg-secondary p-2 text-sm md:hidden initial-element" style="display: flex;">
                      <div class="flex w-full flex-col space-y-0">
                        <div class="rounded-md p-4">
                                 <div class="text-center">Belum ada item produk yang dipilih.</div>
                        </div>
                      </div>
                    </div>
                    <div class="rounded-lg border border-dashed bg-secondary p-2 text-sm text-secondary-foreground md:hidden selected-element " style="display: none;">
                      <div class="mb-1 aspect-square timmel-5">
                        <img alt="icon" sizes="100vw" src="{{ $kategori->thumbnail }}" width="80" height="100" decoding="async" data-nimg="1" class="aspect-square timmel-5 rounded-lg object-cover" loading="lazy" style="color: transparent">
                      </div>
                      <div class="flex w-full flex-col space-y-1 ml-3">
                          
                        <div class="text-xs font-semibold cana select glowing-text">{{ $kategori->nama }}</div>
                        <div class="flex items-center gap-2 pt-0.5 font-semibold">
                            
                        <p class="text-xs font-semibold text-warning text-amber-300 selected-order"></p><span>-</span>
                            <div class="text-xs  select text-white" id="pesan"></div></div>
                        
                        <p class="text-xxs italic text-murky-300">**Waktu proses instan</p>
                        <div class="flex w-full items-center">
                          <p class="text-xs italic select"></p>
                        </div>
                      </div>
                    </div>
                    
                      <div class="mt-4"></div>
                    <div class="relative">
                      <button class="items-center justify-center rounded-md bg-primary-5400 px-4 py-2 text-sm font-medium text-white duration-300 hover:bg-primary-500 disabled:cursor-not-allowed disabled:opacity-75 btn-order relative flex w-full gap-2 overflow-hidden" type="button" id="order-check">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag h-4 w-4"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        <span>Pesan Sekarang!</span>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="mt-4 block rounded-xl bg-murky-800 shadow-2xl md:hidden">
                    <div class="flex border-b border-murky-600">
                        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b bg-primary-500  to-primary-600 px-3 py-2 text-xl font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-4 w-4">
                                <path
                                    fill-rule="evenodd"
                                    d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </div>
                         <h3
            class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4">
            Ulasan</h3>
                    </div>
                    
                     <div class="flow-root p-6">
                      
                        @php
                        $ratings = DB::table('ratings')->where('kategori_id', $kategori->id)->get();
                    
                        $totalStars = 0;
                        $totalReviews = $ratings->count();
                        $positiveReviews = 0;
                    
                        foreach ($ratings as $rating) {
                            $totalStars += $rating->bintang;
                            if ($rating->bintang >= 4) {
                                $positiveReviews++;
                            }
                        }
                    
                        if ($totalReviews > 0) {
                            $averageRating = $totalStars / $totalReviews;
                            $satisfactionPercentage = ($positiveReviews / $totalReviews) * 100;
                        } else {
                            $averageRating = 0; 
                            $satisfactionPercentage = 0;
                        }
                        @endphp
                    
                        <div class="flex flex-col  overflow-hidden ">
                            <div class="mx-6 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-8 w-8 flex-shrink-0 text-yellow-400">
                                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                </svg>
                                <div><span class="text-5xl text-besar">{{ number_format($averageRating, 1) }}</span> <span> / </span><span>5.0</span></div>
                            </div>
                            <div class="flex flex-col gap-1">
                               
                        <div class="mx-6 flex items-center justify-center text-xs font-bold">{{ number_format($satisfactionPercentage, 0) }}% pembeli merasa puas dengan produk ini.</div>
                        <div class="mx-6 flex items-center justify-center gap-2 text-xs">Dari {{ $totalReviews }} Ulasan.</div>
                            </div>
                        </div>
                        @php
                        $totalRatings = [
                            '5' => $ratings->where('bintang', 5)->count(),
                            '4' => $ratings->where('bintang', 4)->count(),
                            '3' => $ratings->where('bintang', 3)->count(),
                            '2' => $ratings->where('bintang', 2)->count(),
                            '1' => $ratings->where('bintang', 1)->count(),
                        ];
                        @endphp
                    
                    
                        <div class="flex flex-col  overflow-hidden pt-6">
                            @foreach($totalRatings as $rating => $count)
                            @php
                            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                            @endphp
                            <ul class="rating-list" style="list-style-type: none; padding-left: 0;">
                                <li class="rating-item" style="display: flex; align-items: center; margin-bottom: 5px;">
                                    <div class="rating-value" style="width: 30px; text-align: right; margin-right: 10px;">
                                        {{ $rating }}
                                    </div>
                                    <div class="star-rating" style="display: flex; align-items: center; margin-right: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" style="height: 20px; width: 20px; color: #ffc107;">
                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="bar" style="flex-grow: 1; height: 10px; background-color: #ddd; border-radius: 5px; overflow: hidden;">
                                        <div class="progress" style="height: 100%; background-color: #ffc107; border-radius: 5px; width: {{ $percentage }}%;"></div>
                                    </div>
                                    <div class="count" style="width: 50px; margin-left: 0px; text-align: right;">{{ $count }}</div>
                                </li>
                            </ul>

                            @endforeach
                        </div>
                    
                        @if($ratings->isEmpty())
                        <div class="py-4">
                            <div class="rounded-md border-l-4 border-yellow-400 bg-yellow-100 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-5 w-5 text-yellow-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3"><p class="text-sm text-yellow-700">Belum ada ulasan dan penilaian.</p></div>
                                </div>
                            </div>
                        </div>
                        @else
                       
                <div class="mt-6"><p class="text-sm text-secondary-foreground">Apakah kamu menyukai produk ini? Beri tahu kami dan calon pembeli lainnya tentang pengalamanmu.</p></div>
                         <hr>
                <div class="flow-root pt-5">
                    <div class="-my-6 divide-y">
                         @foreach($ratings->reverse()->take(5) as $rating)
                        <div class="py-3">
                            <div class="flex items-center">
                                <div class="w-full">
                                    <div class="flex items-start justify-between">
                                        @php
                                        $username = $rating->username ?? $rating->no_pembeli ?? 'Guest';
                                        if(!$username && isset($rating->no_pembeli)) {
                                            $username = $rating->no_pembeli;
                                        }
                                        $usernameLength = strlen($username);
                                        $sensorLength = $usernameLength <= 5 ? 2 : 4;
                                        $start = floor(($usernameLength - $sensorLength) / 2);
                                        $censoredUsername = substr_replace($username, str_repeat('*', $sensorLength), $start, $sensorLength);
                                        @endphp
                                        <h4 class="mt-0.5 text-xs font-bold text-white">{{ $censoredUsername }}</h4>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{{ $i <= $rating->bintang ? 'currentColor' : 'white' }}" aria-hidden="true" class="text-yellow-400 h-4 w-4 flex-shrink-0">
                                                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="sr-only">{{ $rating->bintang }} dari 5 bintang</p>
                                </div>
                            </div>
                            <div class="flex w-full justify-between pt-1 text-xxs">
                                <span>{{ $rating->layanan }}</span>
                                <span>{{ $rating->created_at }}</span>
                            </div>
                            <div class="text-murky-20 mt-1 space-y-6 text-xs italic">“{{ $rating->comment }}”</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
               <div class="flex justify-end pt-5 mt-5">
                   
    <a
        class="justify-center whitespace-nowrap text-xs font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent/75 hover:text-accent-foreground h-8 rounded-md px-4 bg-secondary/50 pr-3 flex items-center gap-2"
        type="button"
        href="/id/reviews"
        style="outline: none;"
    >
        <span>Lihat semua ulasan</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right h-4 w-4">
            <path d="M5 12h14"></path>
            <path d="m12 5 7 7-7 7"></path>
        </svg>
    </a>
</div>

                    </div>
                </div>
            </ul>
            
        @elseif($kategori->tipe === 'jokigendong')
        
           
      <ul class="col-span-3 flex flex-col space-y-8 md:col-span-2">
@include('components.user.account-data')
                    <!--end section input-->
     
            


@include('components.user.nominal-selection')
               
               
          

                      @if(in_array($kategori->tipe,['jokigendong']))
                <div class="rounded-xl bg-murky-800 shadow-2xl" id="quantity">
  
   <div class="flex border-b border-murky-600">
                <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold"> 3 </div>
                <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4"> Jumlah Pembelian </h3>
              </div>
              
  <div class="p-4 sm:px-6 sm:pb-6">
    <div class="flex items-center gap-x-4">
      <div class="flex-1">
        <div class="flex flex-col items-start">
         <input
                class="relative block w-full appearance-none border-murky-600 bg-murky-700 px-3 py-2 text-xs text-white placeholder-murky-200 focus:z-10 focus:border-primary-500 focus:outline-none focus:ring-primary-500 !rounded-md !border-0 !bg-murky-200 !text-murky-800 !placeholder-murky-800 accent-murky-800 !ring-0 placeholder:text-xs focus:!border-transparent focus:!bg-white focus:!ring-transparent disabled:cursor-not-allowed disabled:opacity-75"
                type="number" name="qty" id="qty" value="1" min="1" max="30" disabled required
                oninput="validateQtyInput(this)"
            />
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button type="button" id="incrementBtn" class="flex items-center justify-center rounded-md bg-murky-200 p-1.5 text-murky-800 disabled:cursor-not-allowed disabled:opacity-75">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
          </svg>
        </button>
        <button type="button" id="decrementBtn" class="flex items-center justify-center rounded-md bg-murky-200 p-1.5 text-murky-800 disabled:cursor-not-allowed disabled:opacity-75">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>
                @endif
           
            <div class="rounded-xl bg-murky-800 shadow-2xl" id="section-payment-channel">
              <div class="flex border-b border-murky-600">
                <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold"> 4 </div>
                <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4"> Pilih Metode Pembayaran </h3>
              </div>
              
                 <div id="skeleton-loaderr" class="skeleton-loader grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 px-4 mt-4 py-4">
                @for ($i = 0; $i < 4; $i++)
                    <div class="ph-item melpaaaaaa">
                        <div class="ph-col-12">
                            <div class="ph-picture"></div>
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
              <dl id="paymentList" class="w-full flex-col space-y-4 p-4 sm:p-6 hidden" x-data="{ selected: null, paymentSelected: '' }">
                  
      
                
                  <!--QRIS-->
                @foreach($pay_method as $p) 
                    @if($p->tipe == 'qris')
                        <div x-bind:class="{ 'bg-white bj-shadow': paymentSelected === '{{$p->name}}', 'bg-murky-200': paymentSelected !== '{{$p->name}}' }" class="relative flex cursor-pointer method-list rounded-xl border border-transparent bg-murky-200 p-4 shadow-sm outline-none md:p-4 hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" role="radio" aria-checked="false" method-id="{{$p->name}}" name="paymentMethod" @click="paymentSelected = '{{$p->name}}'">
                            <div class="flex items-center gap-2 max-w-xs">
                                <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->name}}" class="peer hidden" />
                                <label for="method_{{$p->id}}"></label>
                                <img src="{{$p->images}}" alt="qris" width="55" height="40" />
                                <div>
                                    <span class="block font-bjcredits text-xs font-semibold text-murky-800 sm:text-sm" id="headlessui-label-:riu:">{{$p->name}}</span>
                                    <p class="block text-xxs text-murky-800 sm:text-xs hargapembayaran" id="{{$p->name}}">Rp 0</p>
                                </div>
                            </div>
                            <div class="max-w-xs">
                                <div class="relative text-sm font-semibold text-murky-800 sm:text-base">
                                </div>
                            </div>
                            <div class="flex aspect-square w-8 items-center">
                                <div class="w-[4rem] absolute aspect-square -top-[9px] -right-[9px] overflow-hidden rounded-sm">
                                    <div class="absolute top-0 left-0 bg-orange-500 h-2 w-2"></div>
                                    <div class="absolute bottom-0 right-0 bg-orange-500 h-2 w-2"></div>
                                    <div class="absolute block w-square-diagonal py-1 text-center text-xxs font-semibold uppercase bottom-0 right-0 rotate-45 origin-bottom-right shadow-sm bg-orange-500 text-white">BEST PRICE</div>
                                </div>
                            </div>
                        </div>
                    @endif 
                @endforeach
                
                <!--end QRIS-->
                
                
                <!-- E-Wallet -->
                <div class="flex w-full transform flex-col justify-between rounded-xl bg-murky-600 text-left text-sm font-medium text-white duration-300 focus:outline-none accordion-header" data-state="">
                  <dt>
                    <button class="w-full disabled:opacity-75" id="disclosure-button-1" type="button" @click="selected !== 3 ? selected = 3 : selected = null" aria-expanded="false" aria-controls="disclosure-panel-1">
                      <div class="flex w-full justify-between px-4 py-2">
                        <span class="transform text-base font-medium leading-7 duration-300">
                          <div>E-Wallet</div>
                        </span>
                        <span class="ml-6 flex h-7 items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6 transform duration-300" x-bind:class="selected == 3 ? 'rotate-180' : 'rotate-0'">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                          </svg>
                        </span>
                      </div>
                    </button>
                    <div class="relative overflow-hidden transition-all max-h-0 duration-700 " x-ref="container1" x-bind:style="selected == 3 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : 'max-height: 0'" style="max-height: 239px;">
                      <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-1">
                        <div id="radiogroup-1" role="radiogroup" aria-labelledby="label-1">
                          <div id="eWalletList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none"> @foreach($pay_method as $p) @if($p->tipe == 'e-walet') <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" method-id="{{$p->code}}" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 bg-white bj-shadow hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out " id="radio-group-{{$p->code}}" role="radio" aria-checked="false" method-id="{{$p->code}}" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" tabindex="0" aria-labelledby="label-{{$p->code}}:" aria-describedby="description-{{$p->code}}" @click="paymentSelected = '{{$p->code}}'">
                              <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                              <label for="method_{{$p->id}}"></label>
                              <span class="flex w-full">
                                <span class="flex w-full flex-col justify-between">
                                  <div>
                                    <span class="block text-xs font-semibold text-murky-800">
                                      {{$p->name}}
                                    </span>
                                    <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>
                                     <hr>
                                  </div>
                                  <div class="flex w-full items-center justify-between">
                                    <div class="mt-1">
                                      <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800  text-dark.meltihhh">
                                        <h6 class="hargapembayaran" id="{{$p->code}}"></h6>
                                      </div>
                                    </div>
                                    <div class="relative aspect-[6/2] w-10">
                                      <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === '{{$p->code}}', 'grayscale': paymentSelected !== '{{$p->code}}' }" class="object-scale-down grayscale-0" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />
                                    </div>
                                  </div>
                                </span>
                              </span>
                            </div> @endif @endforeach </div>
                        </div>
                      </div>
                    </div>
                    <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo3" x-bind:style="selected == 3 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 3 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">
                      <div class="flex justify-end gap-x-2"> @foreach($pay_method as $p) @if($p->tipe == 'e-walet') <div class="relative aspect-[6/2] w-10">
                          <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />
                        </div> @endif @endforeach </div>
                    </div>
                  </dt>
                </div>
                
              
                <!-- Virtual Account -->
                <div class="flex w-full transform flex-col justify-between rounded-xl bg-murky-600 text-left text-sm font-medium text-white duration-300 focus:outline-none accordion-header" data-state="">
                  <dt>
                    <button class="w-full disabled:opacity-75" id="disclosure-button-2" type="button" @click="selected !== 5 ? selected = 5 : selected = null" aria-expanded="false" aria-controls="disclosure-panel-2">
                      <div class="flex w-full justify-between px-4 py-2">
                        <span class="transform text-base font-medium leading-7 duration-300">
                          <div>Virtual Account</div>
                        </span>
                        <span class="ml-6 flex h-7 items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6 transform duration-300" x-bind:class="selected == 5 ? 'rotate-180' : 'rotate-0'">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                          </svg>
                        </span>
                      </div>
                    </button>
                    <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container2" x-bind:style="selected == 5 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : 'max-height: 0'" style="max-height: 239px;">
                      <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-2">
                        <div id="radiogroup-2" role="radiogroup" aria-labelledby="label-2">
                          <div id="virtualAccountList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none"> @foreach($pay_method as $p) @if($p->tipe == 'virtual-account') <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" method-id="{{$p->code}}" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 bg-white bj-shadow hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" method-id="{{$p->code}}" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" tabindex="0" aria-labelledby="label-{{$p->code}}:" aria-describedby="description-{{$p->code}}" @click="paymentSelected = '{{$p->code}}'">
                              <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                              <label for="method_{{$p->id}}"></label>
                              <span class="flex w-full">
                                <span class="flex w-full flex-col justify-between">
                                  <div>
                                    <span class="block text-xs font-semibold text-murky-800">
                                      {{$p->name}}
                                    </span>
                                    <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>
                                     <hr>
                                  </div>
                                  <div class="flex w-full items-center justify-between">
                                    <div class="mt-1">
                                      <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800  text-dark.meltihhh">
                                        <h6 class="hargapembayaran" id="{{$p->code}}"></h6>
                                      </div>
                                    </div>
                                    <div class="relative aspect-[6/2] w-10">
                                      <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === 'QRIS', 'grayscale': paymentSelected !== 'QRIS' }" class="object-scale-down grayscale-0" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />
                                    </div>
                                  </div>
                                </span>
                              </span>
                            </div> @endif @endforeach </div>
                        </div>
                      </div>
                    </div>
                    <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo5" x-bind:style="selected == 5 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 5 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">
                      <div class="flex justify-end gap-x-2"> @foreach($pay_method as $p) @if($p->tipe == 'virtual-account') <div class="relative aspect-[6/2] w-10">
                          <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />
                        </div> @endif @endforeach </div>
                    </div>
                  </dt>
                </div>
                <!-- Convenience Store -->
                  
                 <!-- Convenience Store -->
                <div class="flex w-full transform flex-col justify-between rounded-xl bg-murky-600 text-left text-sm font-medium text-white duration-300 focus:outline-none accordion-header" data-state="">
                  <dt>
                    <button class="w-full disabled:opacity-75" id="disclosure-button-3" type="button" @click="selected !== 4 ? selected = 4 : selected = null" aria-expanded="false" aria-controls="disclosure-panel-3">
                      <div class="flex w-full justify-between px-4 py-2">
                        <span class="transform text-base font-medium leading-7 duration-300">
                          <div>Convenience Store</div>
                        </span>
                        <span class="ml-6 flex h-7 items-center">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6 transform duration-300" x-bind:class="selected == 4 ? 'rotate-180' : 'rotate-0'">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                          </svg>
                        </span>
                      </div>
                    </button>
                    <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container3" x-bind:style="selected == 4 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : 'max-height: 0'" style="max-height: 239px;">
                      <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-3">
                        <div id="radiogroup-3" role="radiogroup" aria-labelledby="label-3">
                          <div id="convenienceStoreList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none"> @foreach($pay_method as $p) @if($p->tipe == 'convenience-store') <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" method-id="{{$p->code}}" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 bg-white bj-shadow hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" method-id="{{$p->code}}" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" tabindex="0" aria-labelledby="label-{{$p->code}}:" aria-describedby="description-{{$p->code}}" @click="paymentSelected = '{{$p->code}}'">
                              <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                              <label for="method_{{$p->id}}"></label>
                              <span class="flex w-full">
                                <span class="flex w-full flex-col justify-between">
                                  <div>
                                    <span class="block text-xs font-semibold text-murky-800">
                                      {{$p->name}}
                                    </span>
                                    <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>
                                     <hr>
                                  </div>
                                  <div class="flex w-full items-center justify-between">
                                    <div class="mt-1">
                                      <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800  text-dark.meltihhh" id="">
                                        <h6 class="hargapembayaran" id="{{$p->code}}"></h6>
                                      </div>
                                    </div>
                                    <div class="relative aspect-[6/2] w-10">
                                      <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === 'QRIS', 'grayscale': paymentSelected !== 'QRIS' }" class="object-scale-down grayscale-0" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />
                                    </div>
                                  </div>
                                </span>
                              </span>
                            </div> @endif @endforeach </div>
                        </div>
                      </div>
                    </div>
                    <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo4" x-bind:style="selected == 4 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 4 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">
                      <div class="flex justify-end gap-x-2"> @foreach($pay_method as $p) @if($p->tipe == 'convenience-store') <div class="relative aspect-[6/2] w-10">
                          <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />
                        </div> @endif @endforeach </div>
                    </div>
                  </dt>
                </div>
                <!-- pulsa -->
                
                <!--<div class="flex w-full transform flex-col justify-between rounded-xl bg-murky-600 text-left text-sm font-medium text-white duration-300 focus:outline-none accordion-header" data-state="">-->
                <!--  <dt>-->
                <!--    <button class="w-full disabled:opacity-75" id="disclosure-button-3" type="button" @click="selected !== 9 ? selected = 9 : selected = null" aria-expanded="false" aria-controls="disclosure-panel-3">-->
                <!--      <div class="flex w-full justify-between px-4 py-2">-->
                <!--        <span class="transform text-base font-medium leading-7 duration-300">-->
                <!--          <div>Pulsa</div>-->
                <!--        </span>-->
                <!--        <span class="ml-6 flex h-7 items-center">-->
                <!--          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6 transform duration-300" x-bind:class="selected == 9 ? 'rotate-180' : 'rotate-0'">-->
                <!--            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>-->
                <!--          </svg>-->
                <!--        </span>-->
                <!--      </div>-->
                <!--    </button>-->
                <!--    <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container3" x-bind:style="selected == 9 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : 'max-height: 0'" style="max-height: 239px;">-->
                <!--      <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-3">-->
                <!--        <div id="radiogroup-3" role="radiogroup" aria-labelledby="label-3">-->
                <!--          <div id="convenienceStoreList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none"> -->
                <!--          @foreach($pay_method as $p) @if($p->tipe == 'pulsa') -->
                         
                <!--          <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200 ': paymentSelected !== '{{$p->code}}' }" method-id="{{$p->code}}" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 bg-white bj-shadow hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" method-id="{{$p->code}}" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" tabindex="0" aria-labelledby="label-{{$p->code}}:" aria-describedby="description-{{$p->code}}" @click="paymentSelected = '{{$p->code}}'">-->
                <!--              <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />-->
                <!--              <label for="method_{{$p->id}}"></label>-->
                <!--              <span class="flex w-full">-->
                <!--                <span class="flex w-full flex-col justify-between">-->
                <!--                  <div>-->
                <!--                    <span class="block text-xs font-semibold text-murky-800">-->
                <!--                      {{$p->name}}-->
                <!--                    </span>-->
                <!--                    <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>-->
                <!--                     <hr>-->
                <!--                  </div>-->
                <!--                  <div class="flex w-full items-center justify-between">-->
                <!--                    <div class="mt-1">-->
                <!--                      <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800  text-dark.meltihhh" id="">-->
                <!--                        <h6 class="hargapembayaran" id="{{$p->code}}"></h6>-->
                <!--                      </div>-->
                <!--                    </div>-->
                <!--                    <div class="relative aspect-[6/2] w-10">-->
                <!--                      <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === 'QRIS', 'grayscale': paymentSelected !== 'QRIS' }" class="object-scale-down grayscale-0" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />-->
                <!--                    </div>-->
                <!--                  </div>-->
                <!--                </span>-->
                <!--              </span>-->
                <!--            </div> @endif @endforeach </div>-->
                <!--        </div>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--    <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo4" x-bind:style="selected == 9 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 9 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">-->
                <!--      <div class="flex justify-end gap-x-2"> @foreach($pay_method as $p) @if($p->tipe == 'pulsa') <div class="relative aspect-[6/2] w-10">-->
                <!--          <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />-->
                <!--        </div> @endif @endforeach </div>-->
                <!--    </div>-->
                <!--  </dt>-->
                <!--</div>-->
                
                
                
              </dl>
            </div>

                 <div class="rounded-xl bg-murky-800 shadow-2xl" id="promooo">
                 
                     <div class="flex border-b border-murky-600">
                <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold"> 5 </div>
                <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4"> Kode Promo </h3>
              </div>
                  <div class="p-4 sm:px-6 sm:pb-6">
                    <label for="voucher" class="block text-xs font-medium text-white pb-2">Kode Promo</label>
                    <div class="flex items-center space-x-2">
                      <div class="grow">
                        <div class="flex flex-col items-start">
                          <input class="PhoneInputInput relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white" type="text" id="voucher" name="voucher" placeholder="Masukkan Kode Promo Anda" required/>
                        </div>
                      </div>
                      <button type="button" id="btn-check" class="flex items-center justify-center rounded-md bg-primary-5400 py-2 px-4 text-xs font-semibold text-white hover:bg-orange-400 disabled:cursor-not-allowed disabled:opacity-75"> Gunakan </button>
                    </div>
                    <div class="pt-2 text-xs text-red-500"></div>


                  </div>
                </div>
                
                
                
     <div class="rounded-xl bg-murky-800 shadow-2xl jumpToWhatsApp" id="whatsappp">
 
        <div class="flex border-b border-murky-600">
                   <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold"> 6 </div>
                   <h3 class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4"> No. WhatsApp </h3>
                 </div>
    <div class="p-4 sm:px-6">
        <label for="nomor" class="block text-xs font-medium text-white pb-2">No. WhatsApp</label>
        <div class="PhoneInput">
          
            <input
            type="number"
            id="nomor"
            autocomplete="off"
            name="whatsapp"
            placeholder="Contoh 08213456789"
            class="PhoneInputInput relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white"
            value=""
            id="phoneNumberInput"
        />

        </div>
        <span class="text-xxs italic"> </br> </span>
        
    <p class="flex items-center gap-2 rounded-md bg-primary-5400 px-4 py-2.5 text-xs/6">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info h-4 w-4">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M12 16v-4"></path>
        <path d="M12 8h.01"></path>
    </svg>
    <span>Bukti transaksi akan kami kirim ke whatsapp yang kamu isi di atas.</span>
</p>
    </div>

</div>

                
                
                      <div class="inset-x-0 bottom-0 z-10  !mt-0 shad sticky rounded-t-lg pb-4 flex flex-col gap-4 bg-background">
                  <div class=" space-y-0">
                    <div class="rounded-lg border border-dashed bg-secondary p-2 text-sm md:hidden initial-element" style="display: flex;">
                      <div class="flex w-full flex-col space-y-0">
                        <div class="rounded-md p-4">
                                 <div class="text-center">Belum ada item produk yang dipilih.</div>
                        </div>
                      </div>
                    </div>
                    <div class="rounded-lg border border-dashed bg-secondary p-2 text-sm text-secondary-foreground md:hidden selected-element " style="display: none;">
                      <div class="mb-1 aspect-square timmel-5">
                        <img alt="icon" sizes="100vw" src="{{ $kategori->thumbnail }}" width="80" height="100" decoding="async" data-nimg="1" class="aspect-square timmel-5 rounded-lg object-cover" loading="lazy" style="color: transparent">
                      </div>
                      <div class="flex w-full flex-col space-y-1 ml-3">
                          
                        <div class="text-xs font-semibold cana select glowing-text">{{ $kategori->nama }}</div>
                        <div class="flex items-center gap-2 pt-0.5 font-semibold">
                            
                        <p class="text-xs font-semibold text-warning text-amber-300 selected-order"></p><span>-</span>
                            <div class="text-xs  select text-white" id="pesan"></div></div>
                        
                        <p class="text-xxs italic text-murky-300">**Waktu proses instan</p>
                        <div class="flex w-full items-center">
                          <p class="text-xs italic select"></p>
                        </div>
                      </div>
                    </div>
                    
                      <div class="mt-4"></div>
                    <div class="relative">
                      <button class="items-center justify-center rounded-md bg-primary-5400 px-4 py-2 text-sm font-medium text-white duration-300 hover:bg-primary-500 disabled:cursor-not-allowed disabled:opacity-75 btn-order relative flex w-full gap-2 overflow-hidden" type="button" id="order-check">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag h-4 w-4"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        <span>Pesan Sekarang!</span>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="mt-4 block rounded-xl bg-murky-800 shadow-2xl md:hidden">
                    <div class="flex border-b border-murky-600">
                        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b bg-primary-500  to-primary-600 px-3 py-2 text-xl font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-4 w-4">
                                <path
                                    fill-rule="evenodd"
                                    d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </div>
                         <h3
            class="flex w-full items-center justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4">
            Ulasan</h3>
                    </div>
                    
                     <div class="flow-root p-6">
                      
                        @php
                        $ratings = DB::table('ratings')->where('kategori_id', $kategori->id)->get();
                    
                        $totalStars = 0;
                        $totalReviews = $ratings->count();
                        $positiveReviews = 0;
                    
                        foreach ($ratings as $rating) {
                            $totalStars += $rating->bintang;
                            if ($rating->bintang >= 4) {
                                $positiveReviews++;
                            }
                        }
                    
                        if ($totalReviews > 0) {
                            $averageRating = $totalStars / $totalReviews;
                            $satisfactionPercentage = ($positiveReviews / $totalReviews) * 100;
                        } else {
                            $averageRating = 0; 
                            $satisfactionPercentage = 0;
                        }
                        @endphp
                    
                        <div class="flex flex-col  overflow-hidden ">
                            <div class="mx-6 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-8 w-8 flex-shrink-0 text-yellow-400">
                                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                </svg>
                                <div><span class="text-5xl text-besar">{{ number_format($averageRating, 1) }}</span> <span> / </span><span>5.0</span></div>
                            </div>
                            <div class="flex flex-col gap-1">
                               
                        <div class="mx-6 flex items-center justify-center text-xs font-bold">{{ number_format($satisfactionPercentage, 0) }}% pembeli merasa puas dengan produk ini.</div>
                        <div class="mx-6 flex items-center justify-center gap-2 text-xs">Dari {{ $totalReviews }} Ulasan.</div>
                            </div>
                        </div>
                        @php
                        $totalRatings = [
                            '5' => $ratings->where('bintang', 5)->count(),
                            '4' => $ratings->where('bintang', 4)->count(),
                            '3' => $ratings->where('bintang', 3)->count(),
                            '2' => $ratings->where('bintang', 2)->count(),
                            '1' => $ratings->where('bintang', 1)->count(),
                        ];
                        @endphp
                    
                    
                        <div class="flex flex-col  overflow-hidden pt-6">
                            @foreach($totalRatings as $rating => $count)
                            @php
                            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                            @endphp
                            <ul class="rating-list" style="list-style-type: none; padding-left: 0;">
                                <li class="rating-item" style="display: flex; align-items: center; margin-bottom: 5px;">
                                    <div class="rating-value" style="width: 30px; text-align: right; margin-right: 10px;">
                                        {{ $rating }}
                                    </div>
                                    <div class="star-rating" style="display: flex; align-items: center; margin-right: 10px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" style="height: 20px; width: 20px; color: #ffc107;">
                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="bar" style="flex-grow: 1; height: 10px; background-color: #ddd; border-radius: 5px; overflow: hidden;">
                                        <div class="progress" style="height: 100%; background-color: #ffc107; border-radius: 5px; width: {{ $percentage }}%;"></div>
                                    </div>
                                    <div class="count" style="width: 50px; margin-left: 0px; text-align: right;">{{ $count }}</div>
                                </li>
                            </ul>

                            @endforeach
                        </div>
                    
                        @if($ratings->isEmpty())
                        <div class="py-4">
                            <div class="rounded-md border-l-4 border-yellow-400 bg-yellow-100 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-5 w-5 text-yellow-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3"><p class="text-sm text-yellow-700">Belum ada ulasan dan penilaian.</p></div>
                                </div>
                            </div>
                        </div>
                        @else
                       
                <div class="mt-6"><p class="text-sm text-secondary-foreground">Apakah kamu menyukai produk ini? Beri tahu kami dan calon pembeli lainnya tentang pengalamanmu.</p></div>
                         <hr>
                <div class="flow-root pt-5">
                    <div class="-my-6 divide-y">
                         @foreach($ratings->reverse()->take(5) as $rating)
                        <div class="py-3">
                            <div class="flex items-center">
                                <div class="w-full">
                                    <div class="flex items-start justify-between">
                                        @php
                                        $username = $rating->username ?? $rating->no_pembeli ?? 'Guest';
                                        if(!$username && isset($rating->no_pembeli)) {
                                            $username = $rating->no_pembeli;
                                        }
                                        $usernameLength = strlen($username);
                                        $sensorLength = $usernameLength <= 5 ? 2 : 4;
                                        $start = floor(($usernameLength - $sensorLength) / 2);
                                        $censoredUsername = substr_replace($username, str_repeat('*', $sensorLength), $start, $sensorLength);
                                        @endphp
                                        <h4 class="mt-0.5 text-xs font-bold text-white">{{ $censoredUsername }}</h4>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{{ $i <= $rating->bintang ? 'currentColor' : 'white' }}" aria-hidden="true" class="text-yellow-400 h-4 w-4 flex-shrink-0">
                                                    <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="sr-only">{{ $rating->bintang }} dari 5 bintang</p>
                                </div>
                            </div>
                            <div class="flex w-full justify-between pt-1 text-xxs">
                                <span>{{ $rating->layanan }}</span>
                                <span>{{ $rating->created_at }}</span>
                            </div>
                            <div class="text-murky-20 mt-1 space-y-6 text-xs italic">{{ $rating->comment }}”</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
               <div class="flex justify-end pt-5 mt-5">
                   
    <a
        class="items-center justify-center whitespace-nowrap text-xs font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent/75 hover:text-accent-foreground h-8 rounded-md px-4 bg-secondary/50 pr-3 flex items-center gap-2"
        type="button"
        href="{{ url('/') }}#testimonials"
        style="outline: none;"
    >
        <span>Lihat semua ulasan</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right h-4 w-4">
            <path d="M5 12h14"></path>
            <path d="m12 5 7 7-7 7"></path>
        </svg>
    </a>
</div>

                    </div>
                </div>
            </ul>
        @elseif($kategori->tipe === 'giftskin')
        
        
      <ul class="col-span-3 flex flex-col space-y-8 md:col-span-2">
@include('components.user.account-data')
                    <!--end section input-->
                    
                        

                
					
					

                
                

@include('components.user.nominal-selection')
               
               
    

                
        @include('components.user.payment-method')
                
        

@include('components.user.promo-code')
                
                
            
                
                
                
                
                
                
                
                
                
                
@include('components.user.whatsapp-number')

                
                
                
                
               
@include('components.user.order-action')
@include('components.user.reviews')
            </ul>
        
        
        @elseif($kategori->tipe === 'vilogml')
        
      <ul class="col-span-3 flex flex-col space-y-8 md:col-span-2">
                @include('components.user.account-data')
                    <!--end section input-->
     
    

                @include('components.user.nominal-selection')
               
               
          

                @include('components.user.quantity-selection')
           
                @include('components.user.payment-method')
                @include('components.user.promo-code')
                @include('components.user.whatsapp-number')
                @include('components.user.order-action')
                @include('components.user.reviews')
            </ul>
        @endif
        
    @else
      <ul class="col-span-3 flex flex-col space-y-8 md:col-span-2">
                @include('components.user.account-data')
                    <!--end section input-->
                    
                        

                
                @include('components.user.nominal-selection')

                @include('components.user.popup-notice')

                @include('components.user.payment-method')
                <!--<div class="flex w-full transform flex-col justify-between rounded-xl bg-murky-600 text-left text-sm font-medium text-white duration-300 focus:outline-none accordion-header" data-state="">-->
                <!--  <dt>-->
                <!--    <button class="w-full disabled:opacity-75" id="disclosure-button-3" type="button" @click="selected !== 9 ? selected = 9 : selected = null" aria-expanded="false" aria-controls="disclosure-panel-3">-->
                <!--      <div class="flex w-full justify-between px-4 py-2">-->
                <!--        <span class="transform text-base font-medium leading-7 duration-300">-->
                <!--          <div>Pulsa</div>-->
                <!--        </span>-->
                <!--        <span class="ml-6 flex h-7 items-center">-->
                <!--          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-6 w-6 transform duration-300" x-bind:class="selected == 9 ? 'rotate-180' : 'rotate-0'">-->
                <!--            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>-->
                <!--          </svg>-->
                <!--        </span>-->
                <!--      </div>-->
                <!--    </button>-->
                <!--    <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container3" x-bind:style="selected == 9 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : 'max-height: 0'" style="max-height: 239px;">-->
                <!--      <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-3">-->
                <!--        <div id="radiogroup-3" role="radiogroup" aria-labelledby="label-3">-->
                <!--          <div id="convenienceStoreList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none"> -->
                <!--          @foreach($pay_method as $p) @if($p->tipe == 'pulsa') -->
                         
                <!--          <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200 ': paymentSelected !== '{{$p->code}}' }" method-id="{{$p->code}}" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 bg-white bj-shadow hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" method-id="{{$p->code}}" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" tabindex="0" aria-labelledby="label-{{$p->code}}:" aria-describedby="description-{{$p->code}}" @click="paymentSelected = '{{$p->code}}'">-->
                <!--              <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />-->
                <!--              <label for="method_{{$p->id}}"></label>-->
                <!--              <span class="flex w-full">-->
                <!--                <span class="flex w-full flex-col justify-between">-->
                <!--                  <div>-->
                <!--                    <span class="block text-xs font-semibold text-murky-800">-->
                <!--                      {{$p->name}}-->
                <!--                    </span>-->
                <!--                    <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>-->
                <!--                     <hr>-->
                <!--                  </div>-->
                <!--                  <div class="flex w-full items-center justify-between">-->
                <!--                    <div class="mt-1">-->
                <!--                      <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800  text-dark.meltihhh" id="">-->
                <!--                        <h6 class="hargapembayaran" id="{{$p->code}}"></h6>-->
                <!--                      </div>-->
                <!--                    </div>-->
                <!--                    <div class="relative aspect-[6/2] w-10">-->
                <!--                      <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === 'QRIS', 'grayscale': paymentSelected !== 'QRIS' }" class="object-scale-down grayscale-0" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />-->
                <!--                    </div>-->
                <!--                  </div>-->
                <!--                </span>-->
                <!--              </span>-->
                <!--            </div> @endif @endforeach </div>-->
                <!--        </div>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--    <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo4" x-bind:style="selected == 9 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 9 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">-->
                <!--      <div class="flex justify-end gap-x-2"> @foreach($pay_method as $p) @if($p->tipe == 'pulsa') <div class="relative aspect-[6/2] w-10">-->
                <!--          <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />-->
                <!--        </div> @endif @endforeach </div>-->
                <!--    </div>-->
                <!--  </dt>-->
                <!--</div>-->
                
                
                
              </dl>
            </div>
                
        
                @include('components.user.promo-code')

                @include('components.user.whatsapp-number')

                @include('components.user.order-action')

                @include('components.user.reviews')
            </ul>
        @endif
          </div>
             </div>
            </div>
          </ul>
        </div>
      </div>

@if(in_array($kategori->kode, ['mobile-legends']))
<script type="text/javascript">document.addEventListener("DOMContentLoaded",(function(){let e=document.getElementById("closePopupButton"),t=document.querySelector(".popup-structure");e.addEventListener("click",(function(){t.style.display="none",localStorage.setItem("hidePopup","true")})),"true"===localStorage.getItem("hidePopup")&&(t.style.display="none"),document.getElementById("specialList").addEventListener("click",(function(e){let n=e.target.closest(".product-list");if(n){n.getAttribute("data-layanan").toLowerCase().includes("weekly diamond pass")&&(t.style.display="block")}}))})),document.addEventListener("DOMContentLoaded",(function(){let e=document.querySelectorAll(".popup-slide"),t=!1;e.length>0&&(e[0].classList.add("show"),t=!0),document.addEventListener("click",(function(n){Array.from(e).some((e=>e.contains(n.target)))||(t=!0)}))}));</script>
@endif
<div id="react-notif"></div>
@include('components.user.footer')
@push('custom_script')
<script>document.addEventListener("DOMContentLoaded",function(){document.querySelectorAll('[name="nominal"]').forEach(function(e){e.addEventListener("click",function(){let e;(e=document.getElementById("section-payment-channel"))&&e.scrollIntoView({behavior:"smooth"})})})});var listGroupItems=document.querySelectorAll(".method-list");listGroupItems.forEach(function(e){e.addEventListener("click",function(){var e=this.querySelector(".text-xs").textContent.trim();document.querySelector("#pesan").textContent=e,showSelectedElement()})}),window.onload=function(){var e=document.getElementById("nomor"),t=localStorage.getItem("savedNumber");t&&(e.value=t),e.addEventListener("input",function(){localStorage.setItem("savedNumber",e.value)})},document.addEventListener("DOMContentLoaded",function(){let e=document.getElementById("section-payment-channel"),t=document.getElementById("whatsappp");function n(e,t){let n=e.getBoundingClientRect(),o=window.scrollY||window.pageYOffset,i=n.top+o-window.innerHeight/2+n.height/2;window.scrollTo({top:i,behavior:"smooth"}),t&&"function"==typeof t&&setTimeout(t,100)}document.querySelectorAll(".bg-product").forEach(function(t){t.addEventListener("click",function(){n(e)})}),document.querySelectorAll(".method-list").forEach(function(o){o.addEventListener("click",function(){n(e,function(){n(t)})})})}),window.addEventListener("load",function(){setTimeout(function(){document.getElementById("skeleton-loader").style.display="none",document.getElementById("itemList").classList.remove("hidden")},1500)}),window.addEventListener("load",function(){setTimeout(function(){document.getElementById("skeleton-loaderr").style.display="none",document.getElementById("paymentList").classList.remove("hidden")},1500)});</script>
<script>function showInitialElement(){var e=document.querySelector(".initial-element"),t=document.querySelector(".selected-element");e.style.display="flex",t.style.display="none"}function showSelectedElement(){var e=document.querySelector(".initial-element"),t=document.querySelector(".selected-element");e.style.display="none",t.style.display="flex"}function updateSelectedElement(e,t){var l=document.querySelector(".text-xs.font-semibold.selected-order"),n=document.querySelector(".text-xs.font-semibold.text-warning.selected-order");document.querySelector(".flex.w-full.items-center p.text-xs.italic"),l.textContent=e,n.textContent=t}function updateSelectedElements(e){document.querySelector(".text-xs.font-semibold.text-warning.selected-order").textContent=e}var listGroupItems=document.querySelectorAll(".method-list");listGroupItems.forEach(function(e){e.addEventListener("click",function(){updateSelectedElements(this.querySelector(".h6")?this.querySelector(".h6").textContent:this.querySelector(".hargapembayaran").textContent),showSelectedElement()})});var productListItems=document.querySelectorAll(".product-list");productListItems.forEach(function(e){e.addEventListener("click",function(){var e;updateSelectedElement(this.querySelector("#namalayanan").textContent,this.querySelector(".harga")?this.querySelector(".harga").textContent:this.querySelector(".text-dark.meltih").textContent),showSelectedElement(),document.querySelector(".selected-order").style.display="block"})}),showInitialElement();</script>
@if(in_array($kategori->tipe, ['joki', 'jokigendong' ,'vilogml' ]))
<script>function updateQtyDisplay(t){$("#qty-display").text(`{{ $kategori->nama }} x ${t} Qty`)}function updatePrice(t){t=Math.max(1,t);var a=$(".product-list.active").attr("product-id");$("#nominal").val(a),$.ajax({url:"{{ route('ajax.price') }}",dataType:"json",type:"POST",data:{_token:"{{ csrf_token() }}",nominal:a},success:function(a){var e=a.harga*t;changeHarga(e),$(".selected-order").text(formatToRupiah(e)),updateQtyDisplay(t)}})}function toggleDisabledButtons(t){t>=30?$("#incrementBtn").attr("disabled",!0):$("#incrementBtn").removeAttr("disabled"),t<=1?$("#decrementBtn").attr("disabled",!0):$("#decrementBtn").removeAttr("disabled")}function updateQtyDisplay(t){$("#qty-display").text(`{{ $kategori->nama }} x ${t} Qty`)}$(".product-list").click(function(){let t=$(this).attr("product-id");$(".product-list").removeClass("active"),$(this).addClass("active"),$("#nominal").val(t),$.ajax({url:"{{ route('ajax.price') }}",dataType:"json",type:"POST",data:{_token:"{{ csrf_token() }}",nominal:t},success:function(t){var a=Math.max(1,parseInt($("#qty").val())),e=t.harga*a;changeHarga(e),toggleDisabledButtons(a),$(".selected-order").text(formatToRupiah(e)),updateQtyDisplay(a)}})}),$("#incrementBtn").on("click",function(){var t=Math.max(1,parseInt($("#qty").val()));t<30&&(t++,$("#qty").val(t),updatePrice(t),toggleDisabledButtons(t),updateQtyDisplay(t))}),$("#decrementBtn").on("click",function(){var t=Math.max(1,parseInt($("#qty").val()));t>1&&(t--,$("#qty").val(t),updatePrice(t),toggleDisabledButtons(t),updateQtyDisplay(t))}),toggleDisabledButtons(Math.max(1,parseInt($("#qty").val()))),updateQtyDisplay(Math.max(1,parseInt($("#qty").val())));</script>
@endif
<script>function changeHarga(a){(isNaN(a=parseFloat(a))||a<0)&&(a=0);let t=formatToRupiah(a);$("#SALDO").html(t);let z=formatToRupiah(a +250+0.007*a);$("#QRIS_CUSTOM").html(z);let q=formatToRupiah(a+0.025*a);$("#DANA_CUSTOM").html(q),$("#OVO_CUSTOM").html(q);let u=formatToRupiah(a+0.025*a);$("#SHOPEE_CUSTOM").html(u),$("#LINKAJA_CUSTOM").html(u);let o=formatToRupiah(a+100+.007*a);$("#QRIS").html(o),$("#QRIS2").html(o);let e=formatToRupiah(a+.017*a);$("#QRISREALTIME").html(e);let i=formatToRupiah(a+4200);$("#BCAVA").html(i);let n=formatToRupiah(a+3e3);$("#ALFAMART").html(n),$("#ALFAMIDI").html(n),$("#INDOMARET").html(n);let l=formatToRupiah(a+2.5*a/100);$("#OVOPUSH").html(l),$("#DANA").html(l),$("#SHOPEEPAY").html(l);let r=formatToRupiah(a+3*a/100);$("#GOPAY").html(r),$("#LINKAJA").html(r);let s=formatToRupiah(a+3500);$("#BNIVA").html(s),$("#MYBVA").html(s),$("#PERMATAVA").html(s),$("#BRIVA").html(s),$("#MANDIRIVA").html(s),$("#SMSVA").html(s),$("#MUAMALATVA").html(s),$("#CIMBVA").html(s),$("#SAMPOERNAVA").html(s),$("#BSIVA").html(s);let c=formatToRupiah(a+32*a/100),h=formatToRupiah(a+25*a/100);$("#TELKOMSEL").html(c),$("#AXIS").html(h),$("#XL").html(h),$("#TRI").html(h),$("#SMARTFREN").html(h)}function formatToRupiah(a){return"Rp "+a.toLocaleString("id-ID",{maximumFractionDigits:0})}function validateQtyInput(a){a.value.includes("-")&&(a.value=a.value.replace("-","")),a.value<1?a.value=1:a.value>30&&(a.value=30)}function scrollToElement(a){$("html, body").animate({scrollTop:$("#"+a).offset().top},1e3)}function showToast(a,t="error"){var o=document.getElementById("react-notif"),e=document.createElement("div");e.className="toast","success"===t&&e.classList.add("success");var i=document.createElement("div");i.className="toast-icon","success"===t?i.innerHTML='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" width="16" color="rgba(34, 197, 94, 0.8)"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>':i.innerHTML='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" width="16" color="rgba(244, 63, 94, 0.8)"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>';var n=document.createElement("div");n.className="toast-message",n.textContent=a,e.appendChild(i),e.appendChild(n),o.appendChild(e),setTimeout(function(){e.remove()},3e3)}$(".accordion-button").css("pointer-events","none"),$(".accordion-header").addClass("hide-payment"),$(".product-list").click(function(){let a=$(this).attr("product-id");$(".product-list").removeClass("active"),$(this).addClass("active"),$("#nominal").val(a),$.ajax({url:"{{ route('ajax.price') }}",dataType:"json",type:"POST",data:{_token:"{{ csrf_token() }}",nominal:a},success:function(a){void 0!==a.harga&&!isNaN(a.harga)&&parseFloat(a.harga)>=0&&changeHarga(a.harga)}})}),$(".accordion-header").click(function(){0===$(".product-list.active").length&&(showToast("Mohon untuk pilih item terlebih dahulu"),scrollToElement("section-nominal"))}),$(".method-list").click(function(){let a=$(this).attr("method-id");$(".method-list").removeClass("active"),$(this).addClass("active"),$("#metode").val(a)}),$("#order-check").on("click",function(){var a=$("#user_id").val(),t=$("#zone").val(),o=$("#email_joki").val(),e=$("#password_joki").val(),i=$("#loginvia_joki").val(),n=$("#nickname_joki").val(),l=$("#request_joki").val(),r=$("#catatan_joki").val(),s=$("#tglmain_joki").val(),c=$("#jambooking_joki").val(),h=$("#nominal").val(),m=$("#qty").val(),u=$("#metode").val(),d=$("#nomor").val(),k=$("#voucher").val(),v=$("#ktg_tipe").val();if("joki"===v||"vilogml"===v){if(!o||!e||!i||!n){showToast("Silahkan lengkapi semua data Informasi Joki / ML Vilog");return}}else if("jokigendong"===v){if(!s||!c||!i||!n){showToast("Silahkan lengkapi semua data Informasi Joki Gendong");return}}else if(!a&&!t){showToast("Mohon isi UID atau Zone");return}if(!h||!u||!d){showToast("Silahkan lengkapi semua data Informasi Pesanan");return}if(!d){showToast("Silahkan lengkapi nomor WhatsApp");return}$.ajax({url:"{{ route('ajax.confirmation') }}",dataType:"JSON",type:"POST",data:{_token:"{{ csrf_token() }}",uid:a,zone:t,service:h,payment_method:u,nomor:d,email_joki:o,password_joki:e,loginvia_joki:i,nickname_joki:n,request_joki:l,catatan_joki:r,tglmain_joki:s,jambooking_joki:c,qty:m,ktg_tipe:v,voucher:k},beforeSend:function(){$(".load").addClass("show")},success:function(p){$(".load").removeClass("show"),p.status?Swal.fire({html:`${p.data}`,showCancelButton:!0,confirmButtonText:"Pesan Sekarang",cancelButtonText:"Batalkan",customClass:{htmlContainer:"swal-text"}}).then(p=>{if(p.isConfirmed){var f=$("#nick").text();$.ajax({url:"{{ route('ordered') }}",dataType:"JSON",type:"POST",data:{_token:"{{ csrf_token() }}",nickname:f,uid:a,zone:t,service:h,payment_method:u,nomor:d,voucher:k,email_joki:o,password_joki:e,loginvia_joki:i,nickname_joki:n,request_joki:l,catatan_joki:r,tglmain_joki:s,jambooking_joki:c,qty:m,ktg_tipe:v},beforeSend:function(){$(".load").addClass("show")},success:function(a){$(".load").removeClass("show"),a.status?(showToast("Berhasil membuat pesanan!","success"),window.location=`/id/invoices/${a.order_id}`):showToast("Terdapat kesalahan!","error")},error:function(a){$(".load").removeClass("show"),console.log(a)}})}}):Swal.fire({title:"Oops...",text:p.data||"User ID tidak ditemukan.",icon:"error"})},error:function(a){$(".load").removeClass("show"),422===a.status?Swal.fire({title:"Oops...",text:"Pastikan anda sudah mengisi semua data yang diperlukan.",icon:"error"}):Swal.fire({title:"Oops...",text:"Terjadi kesalahan. Silakan coba lagi.",icon:"error"})}})}),$("#btn-check").on("click",function(){var a=$("#voucher").val(),t=$("#nominal").val();function o(){$("#notification").remove()}$.ajax({url:"{{ route('check.voucher') }}",dataType:"JSON",type:"POST",data:{_token:"{{ csrf_token() }}",voucher:a,service:t},success:function(a){setTimeout(o,3e3),showToast("Voucher berhasil digunakan","success")},error:function(a){setTimeout(o,4e3),showToast("Voucher tidak ditemukan","error")}}),$(document).on("click","#closeNotification",function(){o()})});</script>
@endpush
@endsection
