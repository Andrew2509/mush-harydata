<div class="rounded-xl bg-murky-800 shadow-2xl" id="section-nominal">
    <div class="flex border-b border-murky-600">
        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold">2</div>
        <h3 class="flex w-full items-center text-sm/6 justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4">Pilih Nominal</h3>
    </div>

    <!-- Button container for package selection -->
    <div class="scroll-container">
        <button @click="selectedPaket = 'all'" class="button-3d"> Semua</button>
        @foreach($pakets as $paket)
            <button @click="selectedPaket = '{{ $loop->index }}'" class="button-3d">{{ $paket['nama'] }}</button>
        @endforeach
    </div>
    
    <div id="skeleton-loader" class="skeleton-loader grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-3 px-4 mt-4 py-4">
        @for ($i = 0; $i < 12; $i++)
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
    
    <div id="itemList" class="flex flex-col space-y-4 p-4 sm:p-6" style="display: none;" x-data="{ selectedProduct: '' }">
        @foreach($pakets as $paket) 
            <section id="{{ $loop->index }}">
                <h3 class="font-semibold">{{ $paket['nama'] }}</h3>
                <div id="radiogroup-{{ $loop->index }}" role="radiogroup" aria-labelledby="label-{{ $loop->index }}">
                    <div id="specialList" class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-3 " role="none"> 
                        @foreach(collect($paket['layanan'])->sortBy('harga') as $nom)
                            <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': selectedProduct === '{{ $nom['id'] }}', 'bg-murky-200': selectedProduct !== '{{ $nom['id'] }}' }" 
                                 data-layanan="{{ $nom['layanan'] }}" 
                                 class="relative flex product-list cursor-pointer rounded-xl border border-transparent bg-murky-200 p-2.5 shadow-sm outline-none md:p-4 hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out " 
                                 id="product-{{ $nom['id'] }}" 
                                 product-id="{{ $nom['id'] }}" 
                                 role="radio" 
                                 aria-checked="false" 
                                 name="nominal" 
                                 value="{{ $nom['id'] }}" 
                                 tabindex="0" 
                                 aria-labelledby="label-{{ $nom['id'] }}" 
                                 aria-describedby="description-{{ $nom['id'] }}" 
                                 @click="selectedProduct = '{{ $nom['id'] }}'" 
                                 {{Request::get('fs') == $nom['id'] ? 'checked' : ''}}>
                                
                                <input type="radio" name="nominal" value="{{ $nom['id'] }}" class="peer hidden" />
                                <span class="flex flex-1">
                                    <span class="flex flex-col justify-between">
                                        <span class="trunc block text-xs text-murky-800 font-semibold" id="namalayanan">{{ $nom['layanan'] }}</span>
                                        <div>
                                            @if($nom['is_flash_sale'] == 1 && $nom['expired_flash_sale'] >= date('Y-m-d H:i:s')) 
                                                <span class="mt-1 flex items-center text-xs font-semibold text-murky-600 harga">Rp&nbsp;{{ number_format($nom['harga_flash_sale'], 0, ',', '.') }}</span>
                                                <span class="flex items-center text-xs font-semibold italic line-through decoration-[0.9px] text-murky-600 decoration-destructive">Rp&nbsp;{{ number_format($nom['harga'], 0, ',', '.') }}</span> 
                                            @else 
                                                <span class="mt-1 flex items-center text-xs font-semibold text-murky-600 harga">Rp&nbsp;{{ number_format($nom['harga'], 0, ',', '.') }}</span> 
                                            @endif
                                        </div>
                                    </span>
                                    @if($nom['is_flash_sale'] == 1 && $nom['expired_flash_sale'] >= date('Y-m-d'))
                                        @if($flashsale->count() > 0)
                                            <div class="w-[4rem] absolute aspect-square -top-[9px] -right-[9px] overflow-hidden rounded-sm">
                                                <div class="absolute top-0 left-0 bg-orange-700 h-2 w-2"></div>
                                                <div class="absolute bottom-0 right-0 bg-orange-700 h-2 w-2"></div>
                                                <div class="absolute block w-square-diagonal py-1 text-center text-xxs font-semibold uppercase bottom-0 right-0 rotate-45 origin-bottom-right shadow-sm bg-orange-500 text-foreground">{{ number_format(($nom['harga']-$nom['harga_flash_sale']) / $nom['harga']*100, 0) }}% OFF</div>
                                            </div>   
                                        @endif
                                    @endif
                                </span> 
                                @if($nom['product_logo']) 
                                    <div class="flex aspect-square w-8 items-center">
                                        <img alt="{{ $nom['layanan'] }}" fetchpriority="high" width="300" height="300" decoding="async" data-nimg="1" class="object-contain object-right" sizes="80vh" src="{{ $nom['product_logo'] }}" style="color: transparent;" />
                                    </div>
                                @endif
                                <div x-bind:class="{ 'block': selectedProduct === '{{ $nom['id'] }}', 'hidden': selectedProduct !== '{{ $nom['id'] }}' }"></div>
                            </div>
                        @endforeach 
                    </div>
                </div>
            </section>
        @endforeach
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            const loader = document.getElementById('skeleton-loader');
            const list = document.getElementById('itemList');
            if (loader) loader.style.display = 'none';
            if (list) list.classList.remove('hidden');
        }, 1500);
    });
</script>

