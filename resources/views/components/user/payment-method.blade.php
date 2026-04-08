<div class="rounded-xl bg-murky-800 shadow-2xl" id="section-payment-channel">
    <div class="flex border-b border-murky-600">
        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold"> 4 </div>
        <h3 class="flex w-full items-center text-sm/6 justify-between rounded-tr-xl bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4"> Pilih Metode Pembayaran </h3>
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
    <dl id="paymentList" class="flex w-full flex-col space-y-4 p-4 sm:p-6" style="display: none;" x-data="{ selected: null, paymentSelected: '' }">
        <!--QRIS-->
        @foreach($pay_method as $p) 
            @if($p->tipe == 'qris')
                <div x-bind:class="{ 'bg-white bj-shadow': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" class="relative flex cursor-pointer method-list rounded-xl border border-transparent bg-murky-200 p-4 shadow-sm outline-none md:p-4 hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" role="radio" aria-checked="false" method-id="{{$p->code}}" name="paymentMethod" @click="paymentSelected = '{{$p->code}}'">
                    <div class="flex items-center gap-2 max-w-xs">
                        <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                        <label for="method_{{$p->id}}"></label>
                        <img src="{{$p->images}}" alt="qris" width="55" height="40" />
                        <div>
                            <span class="block font-bjcredits text-xs font-semibold text-murky-800 sm:text-sm">QRIS</span>
                            <p class="block text-xxs text-murky-800 sm:text-xs hargapembayaran" id="{{$p->code}}">Rp 0</p>
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
                <div class="relative overflow-hidden transition-all max-h-0 duration-700 " x-ref="container1" x-bind:style="selected == 3 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : 'max-height: 0'">
                    <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-1">
                        <div id="radiogroup-1" role="radiogroup" aria-labelledby="label-1">
                            <div id="eWalletList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none">
                                @foreach($pay_method as $p)
                                    @if($p->tipe == 'e-walet')
                                        <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" name="paymentMethod" value="{{$p->code}}" tabindex="0" @click="paymentSelected = '{{$p->code}}'">
                                            <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                                            <label for="method_{{$p->id}}"></label>
                                            <span class="flex w-full">
                                                <span class="flex w-full flex-col justify-between">
                                                    <div>
                                                        <span class="block text-xs font-semibold text-murky-800">{{$p->name}}</span>
                                                        <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>
                                                        <hr>
                                                    </div>
                                                    <div class="flex w-full items-center justify-between">
                                                        <div class="mt-1">
                                                            <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800">
                                                                <h6 class="hargapembayaran" id="{{$p->code}}"></h6>
                                                            </div>
                                                        </div>
                                                        <div class="relative aspect-[6/2] w-10">
                                                            <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === '{{$p->code}}', 'grayscale': paymentSelected !== '{{$p->code}}' }" class="object-scale-down" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />
                                                        </div>
                                                    </div>
                                                </span>
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo3" x-bind:style="selected == 3 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 3 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">
                    <div class="flex justify-end gap-x-2">
                        @foreach($pay_method as $p)
                            @if($p->tipe == 'e-walet')
                                <div class="relative aspect-[6/2] w-10">
                                    <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />
                                </div>
                            @endif
                        @endforeach
                    </div>
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
                <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container2" x-bind:style="selected == 5 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : 'max-height: 0'">
                    <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-2">
                        <div id="radiogroup-2" role="radiogroup" aria-labelledby="label-2">
                            <div id="virtualAccountList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none">
                                @foreach($pay_method as $p)
                                    @if($p->tipe == 'virtual-account')
                                        <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" name="paymentMethod" value="{{$p->code}}" tabindex="0" @click="paymentSelected = '{{$p->code}}'">
                                            <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                                            <label for="method_{{$p->id}}"></label>
                                            <span class="flex w-full">
                                                <span class="flex w-full flex-col justify-between">
                                                    <div>
                                                        <span class="block text-xs font-semibold text-murky-800">{{$p->name}}</span>
                                                        <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>
                                                        <hr>
                                                    </div>
                                                    <div class="flex w-full items-center justify-between">
                                                        <div class="mt-1">
                                                            <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800">
                                                                <h6 class="hargapembayaran" id="{{$p->code}}"></h6>
                                                            </div>
                                                        </div>
                                                        <div class="relative aspect-[6/2] w-10">
                                                            <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === '{{$p->code}}', 'grayscale': paymentSelected !== '{{$p->code}}' }" class="object-scale-down" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />
                                                        </div>
                                                    </div>
                                                </span>
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo5" x-bind:style="selected == 5 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 5 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">
                    <div class="flex justify-end gap-x-2">
                        @foreach($pay_method as $p)
                            @if($p->tipe == 'virtual-account')
                                <div class="relative aspect-[6/2] w-10">
                                    <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </dt>
        </div>
        
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
                <div class="relative overflow-hidden transition-all max-h-0 duration-700" x-ref="container3" x-bind:style="selected == 4 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : 'max-height: 0'">
                    <div class="px-4 pt-2 pb-4 text-sm text-murky-300" id="disclosure-panel-3">
                        <div id="radiogroup-3" role="radiogroup" aria-labelledby="label-3">
                            <div id="convenienceStoreList" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-2 xl:grid-cols-3" role="none">
                                @foreach($pay_method as $p)
                                    @if($p->tipe == 'convenience-store')
                                        <div x-bind:class="{ 'bg-white bj-shadow ring-2 ring-primary-500 ring-offset-2 ring-offset-murky-800 duration-300 ease-in-out': paymentSelected === '{{$p->code}}', 'bg-murky-200': paymentSelected !== '{{$p->code}}' }" class="method-list relative flex cursor-pointer overflow-hidden payment-method rounded-xl border border-transparent p-2.5 shadow-sm outline-none md:p-4 hover:ring-2 hover:ring-primary-500 hover:ring-offset-2 hover:ring-offset-murky-800 duration-300 ease-in-out" id="radio-group-{{$p->code}}" role="radio" aria-checked="false" name="paymentMethod" value="{{$p->code}}" tabindex="0" @click="paymentSelected = '{{$p->code}}'">
                                            <input type="radio" id="method_{{$p->id}}" name="paymentMethod" value="{{$p->code}}" class="peer hidden" />
                                            <label for="method_{{$p->id}}"></label>
                                            <span class="flex w-full">
                                                <span class="flex w-full flex-col justify-between">
                                                    <div>
                                                        <span class="block text-xs font-semibold text-murky-800">{{$p->name}}</span>
                                                        <span class="mt-0 flex items-center text-xxs text-murky-600">{{$p->keterangan}}</span>
                                                        <hr>
                                                    </div>
                                                    <div class="flex w-full items-center justify-between">
                                                        <div class="mt-1">
                                                            <div class="relative z-30 mt-0 text-xs font-semibold leading-4 text-murky-800">
                                                                <h6 class="hargapembayaran" id="{{$p->code}}"></h6>
                                                            </div>
                                                        </div>
                                                        <div class="relative aspect-[6/2] w-10">
                                                            <img src="{{$p->images}}" x-bind:class="{ 'grayscale-0': paymentSelected === '{{$p->code}}', 'grayscale': paymentSelected !== '{{$p->code}}' }" class="object-scale-down" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" />
                                                        </div>
                                                    </div>
                                                </span>
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-hidden transition-all max-h-0 w-full rounded-b-md bg-murky-300" x-ref="logo4" x-bind:style="selected == 4 ? 'max-height: 0' : 'max-height: 30px'" x-bind:class="selected == 4 ? 'px-0 py-0' : 'px-4 pt-2.5 pb-5'">
                    <div class="flex justify-end gap-x-2">
                        @foreach($pay_method as $p)
                            @if($p->tipe == 'convenience-store')
                                <div class="relative aspect-[6/2] w-10">
                                    <img class="object-scale-down" src="{{$p->images}}" style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;" alt="{{$p->name}}" />
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </dt>
        </div>
    </dl>
</div>
