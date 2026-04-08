<div class="mt-8 {{ $is_mobile ? 'block md:hidden' : 'hidden md:block' }} relative group">
    <!-- Component Background/Glow -->
    <div class="absolute -inset-0.5 bg-gradient-to-r from-primary/20 to-secondary/20 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-500"></div>
    
    <div class="relative bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-8 border-b border-slate-800 bg-slate-900/50">
            <div class="max-w-2xl relative z-10">
                <h2
                    class="text-2xl md:text-3xl font-display font-black text-white mb-2 drop-shadow-[0_0_10px_rgba(14,165,233,0.5)] uppercase">
                    TESTIMONIALS
                    <span class="inline-block w-2h-2 bg-primary rounded-full ml-1 animate-pulse"></span>
                </h2>
                <p class="text-slate-400 text-xs leading-relaxed font-body font-medium">
                    Terima kasih untuk semua pelanggan yang memberi kami ulasan dan peringkat. Kepercayaan Anda adalah
                    prioritas utama kami.
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
            <!-- Left: Overall Rating -->
            <div class="flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-900/50 border border-slate-800 relative overflow-hidden group/rating">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover/rating:opacity-100 transition-opacity duration-500"></div>
                
                <div class="relative flex items-center gap-4 mb-4">
                    <span class="text-6xl font-display font-black text-white leading-none tracking-tighter">
                        {{ number_format($averageRating, 1) }}
                    </span>
                    <div class="flex flex-col">
                        <div class="flex gap-1 mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-sm {{ $i <= round($averageRating) ? 'text-yellow-400 fill-1' : 'text-slate-700' }}">star</span>
                            @endfor
                        </div>
                        <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Dari {{ $totalReviews }} Ulasan</span>
                    </div>
                </div>
                
                <div class="relative px-4 py-2 rounded-full bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-bold tracking-tight">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-xs">sentiment_satisfied_alt</span>
                        {{ number_format($satisfactionPercentage, 0) }}% pembeli merasa puas
                    </span>
                </div>
            </div>

            <!-- Right: Rating Breakdown -->
            <div class="flex flex-col gap-3 justify-center">
                @foreach(range(5, 1) as $rating)
                    @php
                        $count = $totalRatingsCount->get($rating, 0);
                        $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-4 group/item">
                        <div class="flex items-center gap-1 w-8">
                            <span class="text-sm font-bold text-slate-400 group-hover/item:text-white transition-colors">{{ $rating }}</span>
                            <span class="material-symbols-outlined text-xs text-yellow-400 fill-1">star</span>
                        </div>
                        <div class="flex-grow h-2.5 bg-slate-800 rounded-full overflow-hidden border border-slate-700/50">
                            <div class="h-full bg-gradient-to-r from-primary to-primary-600 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-500 w-10 text-right">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="px-6 pb-6">
            <hr class="border-slate-800 mb-8 opacity-50">

        @if($ratings->isEmpty())
        <div class="py-8 text-center">
            <div class="inline-flex items-center justify-center p-4 rounded-full bg-slate-800 border border-slate-700 mb-4 opacity-50">
                <span class="material-symbols-outlined text-4xl text-slate-600">reviews</span>
            </div>
            <p class="text-slate-500 font-bold tracking-tight">Belum ada ulasan untuk produk ini.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($ratings->reverse()->take(5) as $rating)
                <div class="group/review p-5 rounded-2xl bg-slate-900/40 border border-slate-800/50 hover:bg-slate-800/60 hover:border-slate-700 transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex flex-col">
                            @php
                                $username = $rating->username ?? $rating->no_pembeli ?? 'Guest';
                                $usernameLength = strlen($username);
                                $sensorLength = $usernameLength <= 5 ? 2 : 4;
                                $start = floor(($usernameLength - $sensorLength) / 2);
                                $censoredUsername = substr_replace($username, str_repeat('*', $sensorLength), $start, $sensorLength);
                            @endphp
                            <h4 class="text-sm font-black text-white tracking-wide uppercase">{{ $censoredUsername }}</h4>
                            <span class="text-[10px] font-bold text-slate-500 tracking-widest uppercase mt-0.5">{{ $rating->layanan }}</span>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="flex gap-0.5 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-xs {{ $i <= $rating->bintang ? 'text-yellow-400 fill-1' : 'text-slate-700' }}">star</span>
                                @endfor
                            </div>
                            <span class="text-[9px] font-bold text-slate-600 tracking-tighter uppercase">{{ $rating->created_at }}</span>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="absolute -left-2 -top-1 font-serif text-3xl text-primary/10 select-none">“</span>
                        <p class="text-xs text-slate-300 leading-relaxed font-body font-medium transition-colors group-hover/review:text-white">
                            {{ $rating->comment ?: 'Tidak ada komentar.' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Action Button -->
        <div class="mt-8 flex justify-center lg:justify-end">
            <a href="{{ route('reviews') }}" class="relative group/btn inline-flex items-center gap-3 px-6 py-2.5 bg-slate-900 border border-slate-700 rounded-xl overflow-hidden active:scale-95 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-secondary/10 opacity-0 group-hover/btn:opacity-100 transition-opacity"></div>
                <span class="relative z-10 text-xs font-black text-white uppercase tracking-widest">Lihat Semua Ulasan</span>
                <span class="relative z-10 material-symbols-outlined text-sm text-primary group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-primary to-transparent opacity-0 group-hover/btn:opacity-100 transition-opacity"></div>
            </a>
        </div>
    </div>
</div>
