<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- SEO Meta Tags -->
    @include('components.user.seo')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="te6_kjcXFcgjdrdjiu-K15FFkNv-PntWR4IqJtvHFtQ" />

    <!-- Favicon -->

    <!-- Stylesheets and Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="{{ asset('/assets/css/simple.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap');

        /* Prevent Alpine.js elements from flashing before initialization */
        [x-cloak] { display: none !important; }

        :root {
            --warna_1: <?= $config->warna1 ?? '#f5c754' ?>;
            --warna_2: <?= $config->warna2 ?? '#0f172a' ?>;
            --warna_3: <?= $config->warna3 ?? '#1e293b' ?>;
            --warna_4: <?= $config->warna4 ?? '#ffffff' ?>;
        }

        .prose :where(ol > li):not(:where([class~=not-prose] *))::marker {
            font-weight: 400;
            color: var(--warna_2) !important
        }

        .border-murky-800\/75 {
            border-color: rgba(52, 55, 59, .75)
        }

        .text-amber-300 {
            --tw-text-opacity: 1;
            color: rgb(252 211 77 / var(--tw-text-opacity))
        }

        .ring-orange-200\/20 {
            --tw-ring-color: #4b4d4d59
        }

        .area {
            background-image: linear-gradient(to right, var(--tw-gradient-stops));
            --tw-gradient-from: hsl(var(--muted)) var(--tw-gradient-from-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
            --tw-gradient-to: hsl(var(--muted)/0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), hsl(var(--muted)) var(--tw-gradient-via-position), var(--tw-gradient-to);
            --tw-gradient-to: hsl(var(--muted)) var(--tw-gradient-to-position);
            position: relative
        }

        .before\:from-white:before,
        .from-murky-800 {
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to)
        }

        .bg-secondary {
            background-color: #000
        }

        .text-xs\/6 {
            font-size: .75rem;
            line-height: 1.5rem
        }

        .bg-card {
            background-color: #6e6d6d
        }

        .py-2\.5 {
            padding-top: .625rem;
            padding-bottom: .625rem
        }

        .bg-primary {
            background-color: var(--warna_1)
        }

        .popup-structure,
        .popup-structuree {
            display: none;
            position: fixed;
            z-index: 1000;
            height: 100%;
            background-color: rgba(128, 128, 128, .7);
            opacity: 0;
            transition: opacity .3s;
            width: 100%;
            left: 0
        }

        .to-murky-800 {
            --tw-gradient-to: var(--warna_2) var(--tw-gradient-to-position)
        }

        .from-murky-800 {
            --tw-gradient-from: var(--warna_2) var(--tw-gradient-from-position);
            --tw-gradient-to: rgba(52, 55, 59, 0) var(--tw-gradient-to-position)
        }

        .popup-structure {
            top: -32px;
            justify-content: center;
            align-items: center
        }

        .popup-structure.show,
        .popup-structuree.show {
            display: flex;
            opacity: 1
        }

        .popup-structuree {
            top: 0;
            justify-content: center;
            align-items: center
        }

        .bg-title-productasasasas,
        .efwasdsf2f34f34v34v3v4 {
            position: relative;
            overflow: hidden
        }

        .popup-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .5);
            animation: .3s forwards slideUp
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%)
            }

            to {
                transform: translateY(0)
            }
        }

        .marquee-content {
            display: flex;
            flex-wrap: nowrap;
            width: 100%;
            gap: 30px
        }

        @keyframes leftMarquee {
            0% {
                transform: translateX(0)
            }

            100% {
                transform: translateX(-50%)
            }
        }

        .assdafsdvsvasgdsgsdgwgreragwgwrgeargwrgergegsvdsDVSVcsdvdszvsbwtergerg43t34f34343ff34g34gG2:hover {
            animation-play-state: paused
        }

        .efwasdsf2f34f34v34v3v4:after,
        .efwasdsf2f34f34v34v3v4:before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            width: 20px;
            z-index: 2;
            pointer-events: none
        }

        .efwasdsf2f34f34v34v3v4:before {
            left: 0;
            background-image: linear-gradient(to right, #fa9b05, rgba(241, 244, 245, 0))
        }

        .efwasdsf2f34f34v34v3v4:after {
            right: 0;
            background-image: linear-gradient(to left, #a26401, rgba(241, 244, 245, 0))
        }

        @media (min-width:768px) {
            .md\:px-27 {
                padding-left: 1rem;
                padding-right: 1rem
            }

            .efwasdsf2f34f34v34v3v4:after,
            .efwasdsf2f34f34v34v3v4:before {
                width: 100px
            }
        }

        @media (min-width:1024px) {

            .efwasdsf2f34f34v34v3v4:after,
            .efwasdsf2f34f34v34v3v4:before {
                width: 150px
            }
        }

        .bg-muted\/50 {
            background-color: #27272a80
        }

        .rounded-2xl {
            border-radius: 1rem
        }

        @keyframes meteor {
            0% {
                transform: rotate(215deg) translateX(0);
                opacity: 1
            }

            70% {
                opacity: 1
            }

            to {
                transform: rotate(215deg) translateX(-1000px);
                opacity: 0
            }
        }

        .animate-meteor-effect {
            animation: 5s linear infinite meteor
        }

        .py-m {
            padding-top: 30rem
        }

        .before\:content-\[\'\'\]:before {
            --tw-content: "";
            content: var(--tw-content)
        }

        .before\:to-transparent:before {
            content: var(--tw-content);
            --tw-gradient-to: transparent var(--tw-gradient-to-position)
        }

        .before\:from-white:before {
            content: var(--tw-content);
            --tw-gradient-from: #fff var(--tw-gradient-from-position);
            --tw-gradient-to: hsla(0, 0%, 100%, 0) var(--tw-gradient-to-position)
        }

        .before\:bg-gradient-to-r:before {
            content: var(--tw-content);
            background-image: linear-gradient(to right, var(--tw-gradient-stops))
        }

        .before\:-translate-y-\[0\%\]:before,
        .before\:transform:before {
            content: var(--tw-content);
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
        }

        .before\:-translate-y-\[0\%\]:before {
            --tw-translate-y: -0%
        }

        .before\:w-\[80px\]:before {
            content: var(--tw-content);
            width: 80px
        }

        .before\:h-\[1px\]:before {
            content: var(--tw-content);
            height: 1px
        }

        .before\:top-1\/2:before {
            content: var(--tw-content);
            top: 50%
        }

        .before\:absolute:before {
            content: var(--tw-content);
            position: absolute
        }

        .bg-title-productasasasas {
            --tw-bg-opacity: 1;
            background-color: var(--warna_2);
            --tw-text-opacity: 1;
            color: rgb(255 255 255 / var(--tw-text-opacity));
            background-repeat: repeat-x;
            background-position: top;
            background-size: clamp(0em, 20rem, 80em) auto, cover
        }

        .bg-title-productasasasas::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 1100px;
            height: 100%;
            background: linear-gradient(to right, var(--warna_1), transparent);
            pointer-events: none;
            z-index: 1
        }

        .bg-title-productasasasas .container,
        .bg-title-productasasasas .flex,
        .bg-title-productasasasas h2,
        .bg-title-productasasasas p {
            position: relative;
            z-index: 2
        }

        .assdafsdvsvasgdsgsdgwgreragwgwrgeargwrgergegsvdsDVSVcsdvdszvsbwtergerg43t34f34343ff34g34gG2 {
            display: flex;
            width: 100%;
            animation: leftMarquee 50s linear infinite;
        }

        .melpaaaaa,
        .melpaaaaaa,
        .melpaaaaaa3 {
            display: inline-block;
            width: auto;
            padding: 5px 10px;
            border-radius: 50px;
            margin-bottom: 5px;
            background: var(--warna_3)
        }

        .melpaaaaa,
        .melpaaaaaa,
        .melpaaaaaa3,
        .skeleton-loader .ph-col-12,
        .skeleton-loader .ph-item {
            background: var(--warna_3)
        }

        .melpaaaaa {
            height: 35px
        }

        .melpaaaaaa {
            height: 65px
        }

        .melpaaaaaa3 {
            height: 95px
        }

        .skeleton-loader {
            display: grid;
            gap: 1rem
        }

        .skeleton-loader .ph-item {
            display: flex;
            flex-direction: column;
            padding: 0;
            border-radius: 15px;
            margin-bottom: -5px
        }

        .ph-item {
            background-color: var(--warna_3);
            border: 1px solid var(--warna_3);
            border-radius: 2px;
            direction: ltr;
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 2px;
            overflow: hidden;
            padding: 30px 15px 15px;
            position: relative
        }

        .skeleton-loader .ph-picture {
            width: 100%;
            height: 100px;
            background: var(--warna_3);
            border-radius: 4px
        }

        .skeleton-loader .ph-row {
            margin-top: 10px
        }

        .skeleton-loader .ph-col-12 {
            width: 100%;
            height: 20px;
            border-radius: 4px;
            margin-bottom: 10px
        }
        .glass-nav {
            background: rgba(6, 11, 24, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .dropdown-content {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            width: 14rem;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 0.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transform: translateY(1rem);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
        }

        .dropdown:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Navbar responsive utilities - ensures correct display regardless of page-level CSS */
        @media (min-width: 1024px) {
            .lg\:hidden { display: none !important; }
            .lg\:flex { display: flex !important; }
            .hidden.lg\:flex { display: flex !important; }
        }
        @media (max-width: 1023px) {
            .hidden.lg\:flex { display: none !important; }
            .hidden.lg\:block { display: none !important; }
        }
        @media (min-width: 768px) {
            .hidden.md\:flex { display: flex !important; }
        }
        @media (max-width: 767px) {
            .hidden.md\:flex { display: none !important; }
        }
    </style>



    @yield('custom_style')
</head>

<body class="text-white antialiased" :class="{ 'overflow-hidden': isSearchModalOpen || isMobileMenuOpen }" 
    x-data="{ isSearchModalOpen: false, isMobileMenuOpen: false }"
    x-on:keydown.escape="isSearchModalOpen = false; isMobileMenuOpen = false">




    <main class="relative">
        <div id="app">
            @yield('content')
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="/assets/js/oo324ddod2323sd2d.js" nonce="YOUR_GENERATED_NONCE"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" nonce="YOUR_GENERATED_NONCE"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha256-CDOy6cOibCWEdsRiZuaHf8dSGGJRYuBGC+mjoJimHGw=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" nonce="YOUR_GENERATED_NONCE"></script>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer nonce="YOUR_GENERATED_NONCE">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer nonce="YOUR_GENERATED_NONCE"></script>
    <script nonce="YOUR_GENERATED_NONCE">
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
    </script>
    <script nonce="YOUR_GENERATED_NONCE">
        var delay = function() {
            var e = 0;
            return function(r, a) {
                clearTimeout(e), e = setTimeout(r, a)
            }
        }();
        $("#searchProds").keyup(function() {
            let e = $(this).val();
            e.length < 1 ? ($(".resultsearch").removeClass("show"), $(".resultsearch li").remove()) : delay(
                function() {
                    $.ajax({
                        url: "{{ url('/id/cari/index') }}",
                        method: "POST",
                        data: {
                            data: e
                        },
                        beforeSend: function() {
                            $(".resultsearch li").remove()
                        },
                        success: function(e) {
                            $(".resultsearch").append(e), $(".resultsearch").addClass("show")
                        }
                    })
                }, 100)
        });
    </script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script>
        const searchProds = document.getElementById('searchProds');
        if (searchProds) {
            searchProds.addEventListener('input', function() {
                var lottieContainer = document.getElementById('lottie-container');
                if (lottieContainer) {
                    lottieContainer.style.display = 'none';
                }
            });
        }
    </script>


    @stack('custom_script')
</body>

</html>
