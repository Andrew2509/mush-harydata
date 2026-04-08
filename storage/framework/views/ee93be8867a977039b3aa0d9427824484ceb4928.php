
<?php if(session('refund_claimed')): ?>
<div id="refundNotification" class="fixed top-4 right-4 z-[200] max-w-md animate-fade-in-up">
    <div class="bg-emerald-500/10 border border-emerald-500/30 backdrop-blur-xl rounded-2xl p-4 shadow-2xl shadow-emerald-500/10">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-emerald-400">account_balance_wallet</span>
            </div>
            <div class="flex-1">
                <h3 class="text-emerald-400 font-bold text-sm">Dana Dikembalikan!</h3>
                <p class="text-white text-sm mt-1">
                    <strong>Rp. <?php echo e(number_format(session('refund_claimed')['total'], 0, '.', ',')); ?></strong>
                    dari <?php echo e(session('refund_claimed')['count']); ?> transaksi gagal telah ditambahkan ke saldo Anda.
                </p>
                <div class="mt-2 text-xs text-slate-400">
                    <?php $__currentLoopData = session('refund_claimed')['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="inline-block mr-2">• <?php echo e($detail); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <button onclick="document.getElementById('refundNotification').remove()" class="text-slate-400 hover:text-white flex-shrink-0">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    </div>
</div>
<script>setTimeout(() => { const el = document.getElementById('refundNotification'); if(el) el.style.opacity = '0'; setTimeout(() => { if(el) el.remove(); }, 500); }, 10000);</script>
<?php endif; ?>

<!-- Navigation -->
<nav class="sticky top-0 z-50 glass-nav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-16 gap-6 w-full">
            <div class="flex items-center flex-shrink-0">
                <a href="<?php echo e(url('/')); ?>" class="flex items-center">
                    <?php if($config->logo_header): ?>
                        <img src="<?php echo e(asset($config->logo_header)); ?>" alt="Logo" class="h-12 w-auto">
                    <?php else: ?>
                        <div
                            class="w-12 h-12 bg-primary flex items-center justify-center rounded-lg shadow-lg shadow-primary/20">
                            <span class="text-background-dark font-black text-xl"><?php echo e(substr(ENV('APP_NAME'), 0, 1)); ?></span>
                        </div>
                    <?php endif; ?>
                </a>
            </div>

            <div class="hidden lg:flex items-center gap-8 flex-shrink-0 text-sm font-bold ml-4">
                <a class="<?php echo e(Request::is('/') || Request::is('id') ? 'text-white' : 'text-slate-300 hover:text-white'); ?> transition-colors"
                    href="<?php echo e(url('/')); ?>">Beranda</a>
                <a class="<?php echo e(Route::currentRouteName() == 'cari' ? 'text-white' : 'text-slate-300 hover:text-white'); ?> transition-colors"
                    href="<?php echo e(route('cari')); ?>">Cek Transaksi</a>
                <a class="<?php echo e(Route::currentRouteName() == 'leaderboardd' ? 'text-white' : 'text-slate-300 hover:text-white'); ?> transition-colors"
                    href="<?php echo e(route('leaderboardd')); ?>">Leaderboard</a>
                <div class="relative dropdown group">
                    <button
                        class="flex items-center gap-1 text-slate-300 group-hover:text-white transition-colors font-bold cursor-pointer">
                        Lainnya <span class="material-symbols-outlined text-sm">expand_more</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="<?php echo e(route('hitungwr')); ?>"
                            class="block px-4 py-2 hover:bg-white/10 rounded-xl transition-colors text-white text-sm">Win
                            Rate</a>
                        <a href="<?php echo e(route('hitungpointmw')); ?>"
                            class="block px-4 py-2 hover:bg-white/10 rounded-xl transition-colors text-white text-sm">Magic
                            Wheel</a>
                        <a href="<?php echo e(route('hitungpointzodiac')); ?>"
                            class="block px-4 py-2 hover:bg-white/10 rounded-xl transition-colors text-white text-sm">Zodiac</a>
                        <a href="<?php echo e(route('sus.index')); ?>"
                            class="block px-4 py-2 hover:bg-secondary/20 rounded-xl transition-colors text-secondary text-sm font-black italic">Analisis SUS</a>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex flex-1 max-w-[600px] ml-10">
                <button @click="isSearchModalOpen = true" class="w-full relative flex items-center bg-transparent border border-white/10 rounded-full h-10 px-4 focus-within:border-white/30 hover:bg-white/5 transition-all text-left">
                    <span class="material-symbols-outlined text-slate-400 text-lg mr-2">search</span>
                    <span class="text-slate-500 text-sm font-medium flex-1">Cari game favoritmu...</span>
                    <div class="hidden lg:flex items-center justify-center px-1.5 py-0.5 rounded bg-white/10 text-[10px] text-slate-400 font-mono ml-2 border border-white/5 whitespace-nowrap">
                        CTRL K
                    </div>
                </button>
            </div>

            <div class="flex lg:hidden items-center ml-auto gap-2">
                <button @click="isSearchModalOpen = true" class="md:hidden p-2 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined">search</span>
                </button>
                <button @click="isMobileMenuOpen = true" class="p-2 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>

            <div class="hidden lg:flex items-center gap-6 flex-shrink-0 ml-auto justify-end">
                <?php if(auth()->guard()->check()): ?>
                    <div class="relative dropdown group">
                        <button
                            class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/50 border border-white/10 hover:border-secondary/50 transition-all shadow-blue-glow">
                            <div class="text-right hidden sm:block">
                                <p class="text-[10px] text-slate-400 leading-none uppercase tracking-tighter">Saldo</p>
                                <p class="text-xs font-bold text-white">Rp
                                    <?php echo e(number_format(Auth::user()->balance, 0, ',', '.')); ?></p>
                            </div>
                            <div class="w-8 h-8 rounded-lg bg-secondary/20 flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined text-xl">account_circle</span>
                            </div>
                        </button>
                        <div class="dropdown-content">
                            <div class="px-4 py-3 border-b border-white/5">
                                <p class="text-xs text-slate-400">Telah masuk sebagai</p>
                                <p class="text-sm font-bold text-white truncate"><?php echo e(Auth::user()->name); ?></p>
                            </div>
                            <a href="<?php echo e(route('dashboard')); ?>"
                                class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/10 rounded-xl transition-colors text-white text-sm">
                                <span class="material-symbols-outlined text-sm">dashboard</span> Dashboard
                            </a>
                            <a href="<?php echo e(route('deposit')); ?>"
                                class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/10 rounded-xl transition-colors text-white text-sm">
                                <span class="material-symbols-outlined text-sm">account_balance_wallet</span> Deposit
                            </a>
                            <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-1 border-t border-white/5 pt-1">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="flex w-full items-center gap-3 px-4 py-2.5 hover:bg-red-500/20 text-red-400 rounded-xl transition-colors text-sm">
                                    <span class="material-symbols-outlined text-sm">logout</span> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-white font-bold hover:text-slate-300 transition-colors whitespace-nowrap text-sm">Masuk</a>
                    <a href="<?php echo e(route('register')); ?>" class="px-6 py-2 rounded-full bg-white text-black font-bold hover:bg-slate-200 transition-colors whitespace-nowrap text-sm">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Sidebar (Slide-over) -->
<div x-show="isMobileMenuOpen" 
     class="fixed inset-0 z-[100] lg:hidden" 
     x-description="Mobile menu, show/hide based on mobile menu state." 
     x-ref="dialog" 
     aria-modal="true"
     x-cloak>
    <!-- Background backdrop -->
    <div x-show="isMobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"
         @click="isMobileMenuOpen = false"></div>

    <div class="fixed inset-y-0 left-0 z-[101] w-full max-w-xs transition duration-300 transform bg-[#060B18] border-r border-white/10 shadow-2xl overflow-y-auto no-scrollbar"
         x-show="isMobileMenuOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">
        
        <div class="flex items-center justify-between p-6 border-b border-white/5">
            <a href="<?php echo e(url('/')); ?>" class="flex items-center">
                <?php if($config->logo_header): ?>
                    <img src="<?php echo e(asset($config->logo_header)); ?>" alt="Logo" class="h-8 w-auto">
                <?php else: ?>
                    <span class="text-white font-black text-xl uppercase tracking-tighter"><?php echo e(ENV('APP_NAME')); ?></span>
                <?php endif; ?>
            </a>
            <button @click="isMobileMenuOpen = false" class="p-2 rounded-lg hover:bg-white/5 text-slate-400">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="p-6 space-y-6">
            <!-- Mobile Search Bar -->
            <button @click="isSearchModalOpen = true; isMobileMenuOpen = false" class="w-full flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10 text-slate-400 hover:text-white transition-all text-sm font-medium">
                <span class="material-symbols-outlined text-xl">search</span>
                Cari game favoritmu...
            </button>

            <?php if(auth()->guard()->check()): ?>
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-secondary/20 flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined">account_circle</span>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm"><?php echo e(Auth::user()->name); ?></p>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider">Member Premium</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-white/5 flex justify-between items-center">
                    <span class="text-xs text-slate-400">Saldo Akun</span>
                    <span class="text-sm font-black text-white">Rp <?php echo e(number_format(Auth::user()->balance, 0, ',', '.')); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <nav class="space-y-1">
                <a href="<?php echo e(url('/')); ?>" class="flex items-center gap-4 p-3 rounded-xl <?php echo e(Request::is('/') || Request::is('id') ? 'bg-secondary/10 text-secondary' : 'text-slate-300 hover:bg-white/5'); ?> transition-all text-sm font-bold">
                    <span class="material-symbols-outlined text-xl">home</span> Beranda
                </a>
                <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-4 p-3 rounded-xl <?php echo e(Route::currentRouteName() == 'dashboard' ? 'bg-secondary/10 text-secondary' : 'text-slate-300 hover:bg-white/5'); ?> transition-all text-sm font-bold">
                    <span class="material-symbols-outlined text-xl">dashboard</span> Dashboard
                </a>
                <a href="<?php echo e(route('deposit')); ?>" class="flex items-center gap-4 p-3 rounded-xl <?php echo e(Route::currentRouteName() == 'deposit' ? 'bg-secondary/10 text-secondary' : 'text-slate-300 hover:bg-white/5'); ?> transition-all text-sm font-bold">
                    <span class="material-symbols-outlined text-xl">account_balance_wallet</span> Deposit
                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('cari')); ?>" class="flex items-center gap-4 p-3 rounded-xl <?php echo e(Route::currentRouteName() == 'cari' ? 'bg-secondary/10 text-secondary' : 'text-slate-300 hover:bg-white/5'); ?> transition-all text-sm font-bold">
                    <span class="material-symbols-outlined text-xl">history</span> Cek Transaksi
                </a>
                <a href="<?php echo e(route('leaderboardd')); ?>" class="flex items-center gap-4 p-3 rounded-xl <?php echo e(Route::currentRouteName() == 'leaderboardd' ? 'bg-secondary/10 text-secondary' : 'text-slate-300 hover:bg-white/5'); ?> transition-all text-sm font-bold">
                    <span class="material-symbols-outlined text-xl">leaderboard</span> Leaderboard
                </a>

            </nav>

            <div class="space-y-3 pt-4 border-t border-white/5">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-3">Tools & Fitur</p>
                <div class="grid grid-cols-1 gap-2">
                    <a href="<?php echo e(route('hitungwr')); ?>" class="flex items-center gap-4 p-3 rounded-xl text-slate-300 hover:bg-white/5 transition-all text-sm font-bold">
                        <span class="material-symbols-outlined text-xl text-pink-500">query_stats</span> Win Rate
                    </a>
                    <a href="<?php echo e(route('hitungpointmw')); ?>" class="flex items-center gap-4 p-3 rounded-xl text-slate-300 hover:bg-white/5 transition-all text-sm font-bold">
                        <span class="material-symbols-outlined text-xl text-purple-500">Auto_Fix_High</span> Magic Wheel
                    </a>
                    <a href="<?php echo e(route('sus.index')); ?>" class="flex items-center gap-4 p-3 rounded-xl bg-secondary/10 text-secondary border border-secondary/20 transition-all text-sm font-bold">
                        <span class="material-symbols-outlined text-xl">poll</span> Analisis SUS
                    </a>
                </div>
            </div>

            <div class="pt-6">
                <?php if(auth()->guard()->check()): ?>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 p-3 rounded-xl bg-red-500/10 text-red-500 font-bold text-sm">
                            <span class="material-symbols-outlined text-xl">logout</span> Keluar Akun
                        </button>
                    </form>
                <?php else: ?>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="<?php echo e(route('login')); ?>" class="flex items-center justify-center p-3 rounded-xl bg-white/5 border border-white/10 text-white font-bold text-sm">Masuk</a>
                        <a href="<?php echo e(route('register')); ?>" class="flex items-center justify-center p-3 rounded-xl bg-secondary text-white font-bold text-sm shadow-lg shadow-secondary/20">Daftar</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Search Modal Overlay (Futuristic v3) -->
<div id="searchModal" 
     x-show="isSearchModalOpen" 
     @open-search.window="isSearchModalOpen = true; $nextTick(() => document.getElementById('modalSearchInput')?.focus())"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 font-[Rajdhani,sans-serif] antialiased" 
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95">
    
    <!-- Overlay backdrop -->
    <div class="fixed inset-0 bg-[#050508]/80 backdrop-blur-sm transition-opacity" 
         style="background-image: radial-gradient(circle at 15% 50%, rgba(0, 229, 255, 0.08) 0%, transparent 25%), radial-gradient(circle at 85% 30%, rgba(139, 92, 246, 0.08) 0%, transparent 25%), linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px); background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;" 
         @click="isSearchModalOpen = false"></div>

    <!-- Modal content -->
    <main class="relative z-10 w-full max-w-2xl">
        <div class="search-container bg-[rgba(10,10,15,0.9)] rounded-[2rem] border border-[#00E5FF]/20 shadow-2xl overflow-hidden relative" style="backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);">
            <div class="absolute inset-0 border border-[#00E5FF]/10 rounded-[2rem] pointer-events-none shadow-[inset_0_0_20px_rgba(0,229,255,0.05)]"></div>
            <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-[#00E5FF]/40 to-transparent"></div>
            
            <div class="p-6">
                <form action="<?php echo e(route('cari')); ?>" method="GET" class="relative group">
                    <div class="absolute -inset-0.5 bg-[#00E5FF]/40 rounded-full opacity-100 blur-sm"></div>
                    <div class="relative flex items-center bg-black rounded-full border border-[#00E5FF] shadow-[0_0_10px_rgba(0,229,255,0.5),0_0_20px_rgba(0,229,255,0.3)] p-1">
                        <span class="material-symbols-outlined text-[#00E5FF] ml-4 text-2xl group-focus-within:animate-pulse">search</span>
                        <input id="modalSearchInput" name="q" autocomplete="off" class="w-full bg-transparent border-none text-white placeholder-gray-500 focus:ring-0 text-lg py-2.5 px-3 tracking-wide focus:outline-none" style="font-family: 'Rajdhani', sans-serif;" placeholder="Cari game favoritmu..." type="text"/>
                        <button type="button" @click="isSearchModalOpen = false" class="text-gray-500 hover:text-white transition-colors mr-3 rounded-full p-1 hover:bg-white/10 flex items-center pr-2">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <div id="defaultSearchState" class="px-6 pb-6 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-[#00E5FF] text-[10px] sm:text-xs tracking-[0.2em] uppercase font-bold flex items-center gap-2" style="font-family: 'Orbitron', sans-serif;">
                        <span class="w-1.5 h-1.5 bg-[#00E5FF] rounded-full animate-pulse"></span>
                        Game Populer
                    </h2>
                    <a href="<?php echo e(url('/')); ?>" class="text-[10px] text-gray-400 hover:text-[#00E5FF] transition-colors tracking-wider uppercase" style="font-family: 'Orbitron', sans-serif;">View All</a>
                </div>
                
                <div class="flex overflow-x-auto sm:grid sm:grid-cols-4 gap-4 sm:gap-6 pt-8 pb-4 -mx-2 px-2 no-scrollbar snap-x">
                    <?php if(isset($kategoris)): ?>
                        <?php $populerCount = 0; ?>
                        <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($category->tipe == 'populer' && $populerCount < 8): ?>
                                <?php
                                    $themeColor = '#00E5FF'; // Default Cyan
                                    $namaLower = strtolower($category->nama);
                                    if(str_contains($namaLower, 'free fire')) $themeColor = '#f97316'; // Orange
                                    elseif(str_contains($namaLower, 'genshin')) $themeColor = '#c084fc'; // Purple
                                    elseif(str_contains($namaLower, 'valorant')) $themeColor = '#ef4444'; // Red
                                    elseif(str_contains($namaLower, 'pubg')) $themeColor = '#fbbf24'; // Yellow
                                    $populerCount++;
                                ?>
                                <a href="<?php echo e(url('/id/'.$category->kode)); ?>" class="group relative flex flex-col items-center cursor-pointer flex-shrink-0 w-[90px] sm:w-auto snap-center" style="--theme-color: <?php echo e($themeColor); ?>;">
                                    <div class="relative w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                                        <div class="absolute inset-0 rounded-full border-2 transition-all duration-300 border-[var(--theme-color)] opacity-30 group-hover:opacity-100" style="box-shadow: 0 0 0 rgba(0,0,0,0); /* placeholder */" onmouseover="this.style.boxShadow='0 0 8px var(--theme-color), 0 0 15px var(--theme-color)'" onmouseout="this.style.boxShadow='none'"></div>
                                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full overflow-hidden relative z-10 border border-white/10 group-hover:border-transparent">
                                            <img alt="<?php echo e($category->nama); ?>" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110" src="<?php echo e($category->thumbnail); ?>"/>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center relative z-20">
                                        <h3 class="text-white font-bold text-xs sm:text-sm tracking-wide transition-colors group-hover:text-[var(--theme-color)]" style="font-family: 'Orbitron', sans-serif; color: white;" onmouseover="this.style.color='var(--theme-color)'" onmouseout="this.style.color='white'"><?php echo e($category->nama); ?></h3>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-wider group-hover:text-gray-300"><?php echo e($category->sub_nama ?? 'Developer'); ?></p>
                                    </div>
                                    <div class="tooltip absolute -top-8 opacity-0 transform translate-y-2 transition-all duration-300 text-black font-bold text-[10px] py-1 px-2 rounded pointer-events-none whitespace-nowrap z-[100] group-hover:opacity-100 group-hover:translate-y-0 bg-[var(--theme-color)]" style="font-family: 'Orbitron', sans-serif; box-shadow: 0 0 12px var(--theme-color);">
                                        TOP UP NOW
                                        <div class="absolute bottom-[-3px] left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 rotate-45 bg-[var(--theme-color)]"></div>
                                    </div>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <!-- No categories defined globally -->
                    <?php endif; ?>
                </div>
            </div>

            <div id="activeSearchState" class="px-2 pb-6 hidden animate-fade-in-up">
                <div class="custom-scrollbar max-h-[450px] overflow-y-auto px-4">
                    
                    <!-- Mobile Legends -->
                    <a href="<?php echo e(url('/id/mobile-legends')); ?>" class="group flex items-center p-4 rounded-2xl cursor-pointer hover:bg-white/5 transition-all duration-200 border border-transparent hover:border-white/10">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-[#00E5FF]/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="w-14 h-14 rounded-full overflow-hidden border border-white/10 relative z-10">
                                <img alt="Mobile Legends" class="w-full h-full object-cover" src="https://mustopup.com/assets/thumbnail/mlbb.png"/>
                            </div>
                        </div>
                        <div class="ml-5 flex-grow">
                            <h3 class="text-white font-bold text-lg tracking-wide group-hover:text-[#00E5FF] transition-colors" style="font-family: 'Orbitron', sans-serif;">Mobile Legends</h3>
                            <p class="text-sm text-gray-400" style="font-family: 'Rajdhani', sans-serif;">Moonton</p>
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                            <span class="material-symbols-outlined text-[#00E5FF]">chevron_right</span>
                        </div>
                    </a>

                    <!-- Free Fire -->
                    <a href="<?php echo e(url('/id/free-fire')); ?>" class="group flex items-center p-4 rounded-2xl cursor-pointer hover:bg-white/5 transition-all duration-200 border border-transparent hover:border-white/10">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-orange-500/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="w-14 h-14 rounded-full overflow-hidden border border-white/10 relative z-10">
                                <img alt="Free Fire" class="w-full h-full object-cover" src="https://mustopup.com/assets/thumbnail/ff.png"/>
                            </div>
                        </div>
                        <div class="ml-5 flex-grow">
                            <h3 class="text-white font-bold text-lg tracking-wide group-hover:text-orange-500 transition-colors" style="font-family: 'Orbitron', sans-serif;">Free Fire</h3>
                            <p class="text-sm text-gray-400" style="font-family: 'Rajdhani', sans-serif;">Garena</p>
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                            <span class="material-symbols-outlined text-orange-500">chevron_right</span>
                        </div>
                    </a>

                    <!-- Genshin Impact -->
                    <a href="<?php echo e(url('/id/genshin-impact')); ?>" class="group flex items-center p-4 rounded-2xl cursor-pointer hover:bg-white/5 transition-all duration-200 border border-transparent hover:border-white/10">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-[#c084fc]/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="w-14 h-14 rounded-full overflow-hidden border border-white/10 relative z-10">
                                <img alt="Genshin Impact" class="w-full h-full object-cover" src="https://mustopup.com/assets/thumbnail/ghensin.png"/>
                            </div>
                        </div>
                        <div class="ml-5 flex-grow">
                            <h3 class="text-white font-bold text-lg tracking-wide group-hover:text-[#c084fc] transition-colors" style="font-family: 'Orbitron', sans-serif;">Genshin Impact</h3>
                            <p class="text-sm text-gray-400" style="font-family: 'Rajdhani', sans-serif;">HoYoverse</p>
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                            <span class="material-symbols-outlined text-[#c084fc]">chevron_right</span>
                        </div>
                    </a>
                    
                    <!-- Sausage Man (Demo match User UI) -->
                    <a href="<?php echo e(url('/id/sausage-man')); ?>" class="group flex items-center p-4 rounded-2xl cursor-pointer hover:bg-white/5 transition-all duration-200 border border-transparent hover:border-white/10">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-[#00E5FF]/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="w-14 h-14 rounded-full overflow-hidden border border-white/10 relative z-10">
                                <img onerror="this.src='https://placehold.co/100x100/1e293b/white?text=SM'" alt="Sausage Man" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBz54fmC9gLcTWTqP9AgWKmYFFhZHypneetHJXkxcZsbP4piMd-htadox9a_gjEJtMP_gKd0U1aaadpTHSgqkU5ZESfF8y0sJn8IqtzDRHMchWCEfW5al4jlnLe41i4y8XIepISt1nw7Vn4l01AkonHOYsTeEiNiGi3AT50IJIGvJYR_BHO0ZWEPX0XYh-Ms_RYJH5zFLOT_nvwMe_LtkO6r2O1-c4qYjkkHW-Y6_KzBH0Y_P0aMWinLmXpObV3uoMpExQxbdjYAqfR"/>
                            </div>
                        </div>
                        <div class="ml-5 flex-grow">
                            <h3 class="text-white font-bold text-lg tracking-wide group-hover:text-[#00E5FF] transition-colors" style="font-family: 'Orbitron', sans-serif;">Sausage Man</h3>
                            <p class="text-sm text-gray-400" style="font-family: 'Rajdhani', sans-serif;">X.D. Network</p>
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                            <span class="material-symbols-outlined text-[#00E5FF]">chevron_right</span>
                        </div>
                    </a>

                    <!-- Valorant -->
                    <a href="<?php echo e(url('/id/valorant')); ?>" class="group flex items-center p-4 rounded-2xl cursor-pointer hover:bg-white/5 transition-all duration-200 border border-transparent hover:border-white/10">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-red-500/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="w-14 h-14 rounded-full overflow-hidden border border-white/10 relative z-10">
                                <img alt="Valorant" class="w-full h-full object-cover" src="https://mustopup.com/assets/thumbnail/valo.png"/>
                            </div>
                        </div>
                        <div class="ml-5 flex-grow">
                            <h3 class="text-white font-bold text-lg tracking-wide group-hover:text-red-500 transition-colors" style="font-family: 'Orbitron', sans-serif;">Valorant</h3>
                            <p class="text-sm text-gray-400" style="font-family: 'Rajdhani', sans-serif;">Riot Games</p>
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                            <span class="material-symbols-outlined text-red-500">chevron_right</span>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="border-t border-white/5 bg-black/40 px-8 py-4 flex justify-between items-center text-[10px] text-gray-500 tracking-widest uppercase" style="font-family: 'Orbitron', sans-serif;">
                <div class="flex gap-6">
                    
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-gray-600">Navigate</span>
                    <div class="flex gap-1.5 font-sans">
                        <kbd class="px-2 py-0.5 bg-white/5 rounded border border-white/10 text-gray-400">TAB</kbd>
                        <kbd class="px-2 py-0.5 bg-white/5 rounded border border-white/10 text-gray-400">ENTER</kbd>
                        <kbd class="px-2 py-0.5 bg-white/5 rounded border border-white/10 text-gray-400">ESC</kbd>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>

    // Search toggle logic
    let searchTimeout;
    document.getElementById('modalSearchInput').addEventListener('input', function() {
        const defaultState = document.getElementById('defaultSearchState');
        const activeState = document.getElementById('activeSearchState');
        const activeContainer = activeState.querySelector('.custom-scrollbar');
        const query = this.value.trim();
        
        if (query.length > 0) {
            defaultState.classList.add('hidden');
            activeState.classList.remove('hidden');
            
            clearTimeout(searchTimeout);
            
            activeContainer.innerHTML = '<div class="text-center py-8 text-[#00E5FF] font-bold tracking-widest text-sm flex justify-center items-center gap-2" style="font-family: \'Orbitron\', sans-serif;"><span class="w-2 h-2 bg-[#00E5FF] rounded-full animate-bounce"></span><span class="w-2 h-2 bg-[#00E5FF] rounded-full animate-bounce" style="animation-delay: 0.1s"></span><span class="w-2 h-2 bg-[#00E5FF] rounded-full animate-bounce" style="animation-delay: 0.2s"></span></div>';
            
            searchTimeout = setTimeout(() => {
                fetch("<?php echo e(url('/id/cari/index')); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({ data: query })
                })
                .then(response => response.text())
                .then(html => {
                    if(html.trim() === '') {
                        activeContainer.innerHTML = '<div class="text-center py-8 text-gray-400 text-sm tracking-wider" style="font-family: \'Rajdhani\', sans-serif;">Game tidak ditemukan. Coba kata kunci lain.</div>';
                    } else {
                        activeContainer.innerHTML = html;
                    }
                })
                .catch(error => {
                    activeContainer.innerHTML = '<div class="text-center py-8 text-red-500 text-sm tracking-wider" style="font-family: \'Rajdhani\', sans-serif;">Terjadi kesalahan sistem.</div>';
                });
            }, 300); // Debounce typing 300ms
        } else {
            defaultState.classList.remove('hidden');
            activeState.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function(event) {
        if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
            event.preventDefault();
            // Since we can't easily reach Alpine scope from here without a custom event
            // we dispatch a custom event that Alpine can listen for
            window.dispatchEvent(new CustomEvent('open-search'));
        }
    });
</script>

<style>
    @import  url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Rajdhani:wght@400;500;600;700&display=swap');
    
    .animate-fade-in-up {
        animation: fadeInUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes  fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 229, 255, 0.3);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 229, 255, 0.5);
    }
</style>
<?php /**PATH E:\muslihinnnn (1)\harydata\resources\views/components/user/navbar.blade.php ENDPATH**/ ?>