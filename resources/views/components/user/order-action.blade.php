<div class="inset-x-0 bottom-0 z-10  !mt-0 shad sticky bottom-0 rounded-t-lg pb-4 flex flex-col gap-4 bg-background">
    <div class=" space-y-0">
        <div class="rounded-lg border border-dashed bg-secondary p-2 text-sm  rounded-lg md:hidden initial-element" style="display: flex;">
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
                    <div class="text-xs  select text-white" id="pesan"></div>
                </div>
                <p class="text-xxs italic text-murky-300">**Waktu proses instan</p>
            </div>
        </div>
        <div class="mt-4"></div>
        <div class="relative">
            <button class="flex items-center justify-center rounded-md bg-primary-5400 px-4 py-2 text-sm font-medium text-white duration-300 hover:bg-primary-500 disabled:cursor-not-allowed disabled:opacity-75 btn-order relative w-full gap-2 overflow-hidden" type="button" id="order-check">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag h-4 w-4">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                    <path d="M3 6h18"></path>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <span>Pesan Sekarang!</span>
            </button>
        </div>
    </div>
</div>
