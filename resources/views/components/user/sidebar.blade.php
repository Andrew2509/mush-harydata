{{-- Desktop Sidebar (hidden on mobile) --}}
<aside class="hidden md:block w-64 flex-shrink-0">
    <div class="glass-panel rounded-xl p-4 sticky top-28 glow-cyan">
        <ul class="space-y-1">
            <li>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg {{ Route::is('dashboard') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-[0_0_15px_rgba(6,182,212,0.15)] font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-all group font-medium' }}" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined text-xl {{ Route::is('dashboard') ? '' : 'group-hover:text-cyan-400 transition-colors' }}">dashboard</span>
                    <span class="text-sm">Dashboard</span>
                </a>
            </li>
            <li>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg {{ Route::is('riwayat') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-[0_0_15px_rgba(6,182,212,0.15)] font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-all group font-medium' }}" href="{{ route('riwayat') }}">
                    <span class="material-symbols-outlined text-xl {{ Route::is('riwayat') ? '' : 'group-hover:text-cyan-400 transition-colors' }}">history</span>
                    <span class="text-sm">Transaksi</span>
                </a>
            </li>
            <li>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg {{ Route::is('deposit') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-[0_0_15px_rgba(6,182,212,0.15)] font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-all group font-medium' }}" href="{{ route('deposit') }}">
                    <span class="material-symbols-outlined text-xl {{ Route::is('deposit') ? '' : 'group-hover:text-cyan-400 transition-colors' }}">account_balance_wallet</span>
                    <span class="text-sm">Deposit</span>
                </a>
            </li>
            <li>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg {{ Route::is('editProfile') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-[0_0_15px_rgba(6,182,212,0.15)] font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-all group font-medium' }}" href="{{ route('editProfile') }}">
                    <span class="material-symbols-outlined text-xl {{ Route::is('editProfile') ? '' : 'group-hover:text-cyan-400 transition-colors' }}">person</span>
                    <span class="text-sm">Profile</span>
                </a>
            </li>
            <li class="pt-4 mt-4 border-t border-white/5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all font-medium text-left">
                        <span class="material-symbols-outlined text-xl">logout</span>
                        <span class="text-sm">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>

{{-- Mobile Bottom Navigation (visible only on mobile) --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-slate-900/95 backdrop-blur-lg border-t border-white/10 safe-area-bottom">
    <div class="flex items-center justify-around py-2 px-2">
        <a href="{{ url('/') }}" class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-xl text-slate-500 hover:text-white transition-colors">
            <span class="material-symbols-outlined text-xl">home</span>
            <span class="text-[10px] font-medium">Beranda</span>
        </a>
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-xl {{ Route::is('dashboard') ? 'text-cyan-400' : 'text-slate-500 hover:text-white' }} transition-colors">
            <span class="material-symbols-outlined text-xl">dashboard</span>
            <span class="text-[10px] {{ Route::is('dashboard') ? 'font-bold' : 'font-medium' }}">Dashboard</span>
        </a>
        <a href="{{ route('riwayat') }}" class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-xl {{ Route::is('riwayat') ? 'text-cyan-400' : 'text-slate-500 hover:text-white' }} transition-colors">
            <span class="material-symbols-outlined text-xl">history</span>
            <span class="text-[10px] {{ Route::is('riwayat') ? 'font-bold' : 'font-medium' }}">Transaksi</span>
        </a>
        <a href="{{ route('deposit') }}" class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-xl {{ Route::is('deposit') ? 'text-cyan-400' : 'text-slate-500 hover:text-white' }} transition-colors">
            <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
            <span class="text-[10px] {{ Route::is('deposit') ? 'font-bold' : 'font-medium' }}">Deposit</span>
        </a>
        <a href="{{ route('editProfile') }}" class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-xl {{ Route::is('editProfile') ? 'text-cyan-400' : 'text-slate-500 hover:text-white' }} transition-colors">
            <span class="material-symbols-outlined text-xl">person</span>
            <span class="text-[10px] {{ Route::is('editProfile') ? 'font-bold' : 'font-medium' }}">Profile</span>
        </a>
    </div>
</nav>
