@php
    $username = $rating->username ?? $rating->no_pembeli ?? 'Guest';
    $usernameLength = strlen($username);
    $sensorLength = $usernameLength <= 5 ? 2 : 4;
    $start = floor(($usernameLength - $sensorLength) / 2);
    $censoredUsername = substr_replace($username, str_repeat('*', $sensorLength), $start, $sensorLength);
    
    // Choose a random avatar (using the ones provided in the user's example)
    $avatars = [
        'https://lh3.googleusercontent.com/aida-public/AB6AXuAVLOgJGR2exWWdvGX78-PDjzE94afWGjTRe_F7t-iFyC6wpdFU61aOSE0Ba0J4xSpmR3fNFRThNOCa5eNgIuk86ClepmpTt6AJjJk3k8dcuzFtfWSDutF3XVWBCnjy1gRZrwazCovXrPmAlwktuSP-AjHRBZuLeC41QFb8ZDSogKbi6ZrTzNLztYkPX-poC2PEFlVREN9Pt6l_V6UJ0dLdVZD5D1CR31g0Ljz_T2_qUxP9OMSioH3d1iRjSpitvFTvElcrnO3fnOba',
        'https://lh3.googleusercontent.com/aida-public/AB6AXuBFpexaNcd6k-xW_svSdgts3JsB56NWTWdZXoRi03yoIXxOy-RYyxbaQh-k6HkGEpt6ToxTTkz530xhs6PXSa7_LkQnWCmj6JI46Z93uVkvhLlG3RE_AcIVq3T7kwSq9oayZwFy-luSPRjEcsY3XfCjMZyXOyxVlkXJZ94bAn0Qfyjg2nK-fVrFoFSRWcLi0yNUk1hxtXUGaIYLOeKP8QCbry7tWhLHF91eJds8ZrNyfkiQQKBpjsT-0LxUYAGbTERDdo41JlljwNnD',
        'https://lh3.googleusercontent.com/aida-public/AB6AXuB9ABBRhxxk0CkQsg9l2dgdg4Ew6CB6OP6busWmHjJXRoMvmdbGBpV42r91B6NrADCnOd2fITgYYfqQ7p7SKn_IxYHuZ6RtEMLIVFE-zHEm6aSdnK79KH8eVXcqTtWe2do_IXzoimqP80ivFH_4GqiKOuYVIppKNjI4hjC-w6XtBAvuoKkwCud3p-A4xnmQnzhvTVR8grOOuh1b1_2KKmL15NP4qT5mc2IFIyN5vRnqgqf9__OQlxuxUoThjAIPlFZHR4za4HnETPQE'
    ];
    $avatar = $avatars[$rating->id % 3];
@endphp

<div class="w-[280px] md:w-[400px] glass glass-card rounded-2xl p-4 md:p-6 transition-all duration-300 flex-shrink-0">
    <div class="flex justify-between items-start mb-4">
        <div class="flex items-center gap-3">
            <img alt="User Avatar" class="w-10 h-10 md:w-12 md:h-12 rounded-full border-2 border-slate-600 object-cover" src="{{ $avatar }}"/>
            <div>
                <h4 class="font-bold text-white font-display tracking-wide uppercase text-[10px] md:text-[12px]">{{ $censoredUsername }}</h4>
                <span class="text-[9px] md:text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $rating->kategori_nama ?: 'Produk' }} Player</span>
            </div>
        </div>
        <div class="flex text-yellow-400 text-[10px] gap-0.5">
            @for($i = 1; $i <= 5; $i++)
                <i class="{{ $i <= $rating->bintang ? 'fas' : 'far' }} fa-star"></i>
            @endfor
        </div>
    </div>
    <p class="text-slate-300 text-xs md:text-sm leading-relaxed border-t border-slate-700/50 pt-3 md:pt-4 font-body">
        "{{ $rating->comment ?: 'Pelayanan sangat memuaskan, proses cepat dan aman! Rekomended banget.' }}"
    </p>
    <div class="mt-4 flex items-center justify-between text-[10px] text-slate-500 font-bold uppercase tracking-wider">
        <div class="flex items-center gap-1.5">
            <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($rating->created_at)->diffForHumans() }}
        </div>
        <div class="text-primary/60">{{ $rating->layanan }}</div>
    </div>
</div>
