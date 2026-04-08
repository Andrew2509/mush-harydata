<div class="rounded-xl bg-murky-800 shadow-2xl" id="quantity">
    <div class="flex border-b border-murky-600">
        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold"> 3 </div>
        <h3 class="flex w-full items-center justify-between text-sm/6 rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4"> Jumlah Pembelian </h3>
    </div>
    <div class="p-4 sm:px-6 sm:pb-6">
        <div class="flex items-center gap-x-4">
            <div class="flex-1">
                <div class="flex flex-col items-start">
                    <input
                        class="relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white disabled:cursor-not-allowed disabled:opacity-75 accent-murky-800 placeholder:text-xs"
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
