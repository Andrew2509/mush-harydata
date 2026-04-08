<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Login — {{ config('app.name', 'Dashboard') }}</title>
    <script src="{{ asset('js/tailwind-cdn.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#22d3ee",
                        "modal-dark": "#111222",
                        "input-dark": "#1a1c2e",
                    },
                    fontFamily: {
                        display: ["Rajdhani", "sans-serif"],
                        body: ["Roboto", "sans-serif"],
                    },
                    boxShadow: {
                        'glow': '0 0 15px rgba(34, 211, 238, 0.4)',
                        'input-focus': '0 0 0 2px rgba(34, 211, 238, 0.2)',
                    }
                },
            },
        };
    </script>
    <style>
        .custom-checkbox:checked {
            background-color: #22d3ee;
            border-color: #22d3ee;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-btn {
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row font-body">

    {{-- Left panel — full height video --}}
    <div class="w-full md:w-5/12 relative bg-purple-900 overflow-hidden min-h-[240px] md:min-h-screen">
        <video
            id="bg-video"
            autoplay loop muted playsinline preload="auto"
            class="absolute inset-0 w-full h-full object-cover"
            style="object-position: center 20%;"
            src="{{ asset('video/617837642701099810.mp4') }}"
        ></video>
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-purple-900/40 to-transparent"></div>

        {{-- Floating light orb --}}
        <div class="hidden md:block absolute -left-8 top-12 w-24 h-24 bg-purple-500/30 blur-xl rounded-full mix-blend-screen animate-pulse"></div>

        {{-- Greeting text --}}
        <div class="absolute bottom-10 left-8 z-10">
            <h2 class="text-white font-display font-bold text-4xl tracking-wide drop-shadow-lg">
                WELCOME<span class="text-primary">.</span>
            </h2>
            <p class="text-gray-300 text-sm mt-1 font-medium tracking-wide">admin dashboard access</p>
        </div>
    </div>

    {{-- Right panel — full height login form --}}
    <div class="w-full md:w-7/12 min-h-screen flex items-center justify-center bg-modal-dark p-8 md:p-16">
        <div class="w-full max-w-md">
            <div class="mb-8">
                <h1 class="text-white font-display font-bold text-xl tracking-widest uppercase">
                    Login <span class="text-gray-600 mx-2">/</span>
                    <span class="text-gray-500 text-base">Admin Panel</span>
                </h1>
                <div class="mt-2 h-0.5 w-16 bg-gradient-to-r from-primary to-purple-500 rounded-full"></div>
            </div>

            {{-- Error messages --}}
            @if(session('error'))
                <div class="flex items-center gap-3 rounded bg-red-500/10 border border-red-500/30 px-4 py-3 text-sm text-red-400 mb-5">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="flex items-center gap-3 rounded bg-green-500/10 border border-green-500/30 px-4 py-3 text-sm text-green-400 mb-5">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="flex items-start gap-3 rounded bg-red-500/10 border border-red-500/30 px-4 py-3 text-sm text-red-400 mb-5">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Login form --}}
            <form method="POST" action="{{ route('post.adminlogin') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="remember" value="true" />

                {{-- Username --}}
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider" for="username">
                        <i class="fa-regular fa-user mr-1 text-primary/60"></i> Username
                    </label>
                    <input
                        class="w-full bg-input-dark border border-gray-800 rounded px-4 py-3 text-gray-200 placeholder-gray-600 focus:ring-1 focus:ring-primary focus:border-primary focus:shadow-input-focus transition-all duration-300"
                        id="username" name="username" type="text"
                        placeholder="Enter your username" autocomplete="username"
                        required value="{{ old('username') }}"
                    />
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider" for="password">
                            <i class="fa-solid fa-lock mr-1 text-primary/60"></i> Password
                        </label>
                        <div class="flex gap-1" id="strength-dots">
                            <div class="w-3 h-1 bg-gray-700 rounded-full transition-all duration-300"></div>
                            <div class="w-3 h-1 bg-gray-700 rounded-full transition-all duration-300"></div>
                            <div class="w-3 h-1 bg-gray-700 rounded-full transition-all duration-300"></div>
                            <div class="w-3 h-1 bg-gray-700 rounded-full transition-all duration-300"></div>
                            <div class="w-3 h-1 bg-gray-700 rounded-full transition-all duration-300"></div>
                        </div>
                    </div>
                    <div class="relative">
                        <input
                            class="w-full bg-input-dark border border-gray-800 rounded px-4 py-3 pr-12 text-gray-200 placeholder-gray-600 focus:ring-1 focus:ring-primary focus:border-primary focus:shadow-input-focus transition-all duration-300"
                            id="password" name="password" type="password"
                            placeholder="••••••••" autocomplete="current-password" required
                        />
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-primary transition-colors" type="button" id="toggle-password">
                            <i class="fa-regular fa-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-primary focus:ring-offset-0 focus:ring-primary custom-checkbox" type="checkbox" name="remember" />
                        <span class="text-xs text-gray-400 group-hover:text-gray-300 transition-colors">Remember me on this device</span>
                    </label>
                </div>

                {{-- Submit button --}}
                <button
                    class="w-full bg-gradient-to-r from-primary to-cyan-400 hover:from-cyan-400 hover:to-primary text-gray-900 font-display font-bold uppercase tracking-widest py-3 rounded shadow-glow hover:shadow-[0_0_25px_rgba(34,211,238,0.6)] transition-all duration-300 mt-4 shimmer-btn relative overflow-hidden"
                    type="submit"
                >
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Masuk Dashboard
                    </span>
                </button>

                {{-- Back link --}}
                <div class="text-center mt-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs text-gray-500 hover:text-primary transition-colors duration-300">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Halaman Utama
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password
        const toggleBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        // Password strength
        const dots = document.querySelectorAll('#strength-dots > div');
        passwordInput.addEventListener('input', function () {
            const val = this.value;
            let s = 0;
            if (val.length >= 4) s++;
            if (val.length >= 8) s++;
            if (/[A-Z]/.test(val)) s++;
            if (/[0-9]/.test(val)) s++;
            if (/[^A-Za-z0-9]/.test(val)) s++;
            dots.forEach((d, i) => {
                if (i < s) { d.classList.remove('bg-gray-700'); d.classList.add('bg-primary'); d.style.boxShadow = '0 0 5px rgba(34,211,238,0.8)'; }
                else { d.classList.add('bg-gray-700'); d.classList.remove('bg-primary'); d.style.boxShadow = 'none'; }
            });
        });

        // Force video
        const v = document.getElementById('bg-video');
        function fp() { if (v && v.paused) v.play().catch(() => {}); }
        fp(); setTimeout(fp, 500);
        document.addEventListener('click', fp, { once: true });
        document.addEventListener('touchstart', fp, { once: true });
    </script>
</body>
</html>
