<div class="rounded-xl bg-murky-800 shadow-2xl" id="section-input">
    <input type="hidden" id="nominal">
    <input type="hidden" id="metode">
    <input type="hidden" id="ktg_tipe" value="{{ $kategori->tipe }}">

    <div class="flex border-b border-murky-600">
        <div class="flex items-center justify-center rounded-tl-xl bg-gradient-to-b from-primary-400 to-primary-600 px-3 py-2 text-xl font-semibold"> 1 </div>
        <h3 class="flex w-full items-center justify-between rounded-tr-xl text-sm/6 bg-gradient-to-b from-murky-800 to-murky-800 px-2 py-2 text-base font-semibold leading-6 text-white sm:px-4"> Masukkan Data Akun Kamu </h3>
    </div>

    @php
        if($kategori->field_2 !== null){
            $field2Values = explode(',', $kategori->field_2);
            $selectValue = isset($field2Values[2]) ? trim($field2Values[2]) : null;
        }
        
        $fieldSelectTitle = explode(',', $kategori->field_select_title);
        $fieldSelect = explode(',', $kategori->field_select);
        $field1Values = explode(',', $kategori->field_1);
    @endphp

    @if($kategori->field_2 !== null)
        <div class="grid grid-cols-2 gap-4 p-4 sm:px-6 sm:pb-4">
            <div>
                <label for="user_id" class="block text-xs font-medium text-white pb-2">{{ $field1Values[0] }}</label>
                <div class="flex flex-col items-start">
                    <input 
                        class="relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white disabled:cursor-not-allowed disabled:opacity-75 accent-murky-800 placeholder:text-xs" 
                        type="{{ $field1Values[2] }}" 
                        id="user_id" name="user_id" 
                        placeholder="{{ $field1Values[1] }}"/> 
                </div>
            </div>
            @if($selectValue == "select")
                <div>
                    <label for="zone" class="block text-xs font-medium text-white pb-2"> {{ $field2Values[0] }}</label>
                    <select class="relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white disabled:cursor-not-allowed disabled:opacity-75 accent-murky-800 placeholder:text-xs" id="zone">
                        <option value="">{{ $field2Values[1] }}</option>
                        @foreach($fieldSelectTitle as $key => $fst)
                            <option value="{{ $fieldSelect[$key] }}">{{ $fst }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif($selectValue == "text" || $selectValue == "number" || $selectValue == "password")
                <div>
                    <label for="zone" class="block text-xs font-medium text-white pb-2">{{ $field2Values[0] }}</label>
                    <div class="flex flex-col items-start">
                        <input
                            class="relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white disabled:cursor-not-allowed disabled:opacity-75 accent-murky-800 placeholder:text-xs"
                            type="{{ $field2Values[2] }}"
                            name="zone_id" id="zone"
                            placeholder="{{ $field2Values[1] }}"/>
                    </div>
                </div>
            @endif
        </div>
    @elseif(in_array($kategori->tipe,['joki', 'vilogml']))
        <div class="grid grid-cols-2 gap-4 p-4 sm:px-6 sm:pb-4">
            <div>
                <label for="email_joki" class="block text-xs font-medium text-white pb-2">{{ $kategori->tipe == 'vilogml' ? 'Email' : 'Email/No. Hp' }}</label>
                <div class="flex flex-col items-start">
                    <input
                        class="PhoneInputInput relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white"
                        type="email"
                        id="email_joki"
                        name="email_joki"
                        placeholder="{{ $kategori->tipe == 'vilogml' ? 'Ketikan Email' : 'Ketikan Email/No. Hp' }}"
                        required
                    />
                </div>
            </div>
            <div>
                <label for="password_joki" class="block text-xs font-medium text-white pb-2">Password</label>
                <div class="flex flex-col items-start">
                    <input
                        class="PhoneInputInput relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white"
                        type="password"
                        id="password_joki"
                        name="password_joki"
                        placeholder="Ketikan Password"
                        required
                    />
                </div>
            </div>
            <div>
                <label for="loginvia_joki" class="block text-xs font-medium text-white pb-2">Login Via</label>
                <select
                    id="loginvia_joki"
                    name="loginvia_joki"
                    class="PhoneInputInput relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white"
                    required
                >
                    <option value="" disabled selected>Login Via</option>
                    <option value="moonton">Moonton (Rekomendasi)</option>
                    <option value="vk">VK</option>
                    <option value="tiktok">Tiktok</option>
                    <option value="facebook">Facebook</option>
                </select>
            </div>
            <div>
                <label for="nickname_joki" class="block text-xs font-medium text-white pb-2">{{ $kategori->tipe == 'vilogml' ? 'User ID' : 'Nickname' }}</label>
                <div class="flex flex-col items-start">
                    <input
                        class="PhoneInputInput relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white"
                        type="text"
                        id="nickname_joki"
                        name="nickname_joki"
                        placeholder="{{ $kategori->tipe == 'vilogml' ? 'Ketikan User ID' : 'Ketikan Nickname' }}"
                        required
                    />
                </div>
            </div>
            <div>
                <label for="request_joki" class="block text-xs font-medium text-white pb-2">{{ $kategori->tipe == 'vilogml' ? 'Server ID' : 'Request Hero' }}</label>
                <div class="flex flex-col items-start">
                    <input
                        class="PhoneInputInput relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white"
                        type="text"
                        id="request_joki"
                        name="request_joki"
                        placeholder="{{ $kategori->tipe == 'vilogml' ? 'Ketikan Server ID' : 'Min Request 3 Hero (Diusahakan)' }}"
                        required
                    />
                </div>
            </div>
            <div>
                <label for="catatan_joki" class="block text-xs font-medium text-white pb-2">{{ $kategori->tipe == 'vilogml' ? 'Catatan' : 'Catatan untuk Penjoki' }}</label>
                <div class="flex flex-col items-start">
                    <input
                        class="PhoneInputInput relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white"
                        type="text"
                        id="catatan_joki"
                        name="catatan_joki"
                        placeholder="{{ $kategori->tipe == 'vilogml' ? 'Catatan' : 'Catatan untuk Penjoki' }}"
                        required
                    />
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-2 gap-4 p-4 sm:px-6 sm:pb-4">
            <div>
                <label for="user_id" class="block text-xs font-medium text-white pb-2">{{ $field1Values[0] }}</label>
                <div class="flex flex-col items-start">
                            class="relative block w-full appearance-none rounded-md border-0 bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:z-10 focus:border-transparent focus:outline-none focus:ring-transparent focus:bg-white disabled:cursor-not-allowed disabled:opacity-75 accent-murky-800 placeholder:text-xs"
                        type="{{ $field1Values[2] }}"
                        id="user_id" name="user_id"
                        placeholder="{{ $field1Values[1] }}"/> 
                </div>
            </div>
        </div>
    @endif

    <div class="px-4 pb-4 text-[10px] sm:px-6 sm:pb-6">
        <div>
            <p><em>{!! $kategori->deskripsi_field !!}</em></p>
        </div>
    </div>
</div>
