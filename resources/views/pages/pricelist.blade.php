@extends('layouts.user')

@section('custom_style')


<style>
    .accordion-button {
            box-shadow: none !important;
        }
        
        .product .box {
            margin-bottom: 40px;
        }
</style>


@endsection


@section('content')

@include('components.user.navbar')
<section id="price-list" class="relative space-y-12 pb-24">
    <div class="relative flex items-center bg-murky-700 py-8 shadow-2xl md:py-12">
        <div class="absolute h-full w-full">
            <div class="area">
                <ul class="rectangle">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
        <div class="container relative z-20">
            <h2 class="max-w-2xl text-3xl font-bold tracking-tight text-white sm:text-4xl">Daftar Harga</h2>
            <p class="mt-6 max-w-3xl">Semua daftar harga dari produk kami. <br> Pilih produk untuk melihat daftar
                harga </p>
        </div>
    </div>
    <div class="container">
        <div class="border-l-4 border-cyan-400 bg-cyan-100 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" aria-hidden="true" class="h-5 w-5 text-cyan-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z">
                        </path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="max-w-5xl text-sm text-cyan-900">Koneksi tersedia melalui metode POST (API) dan metode
                        GET (H2H). Silahkan baca <a href="/id/documentation/api"
                            class="font-semibold underline decoration-primary-500 underline-offset-2"
                            target="_blank" rel="noopener noreferrer" style="outline: none;">Dokumentasi</a> untuk
                        memulai integrasi dengan {{ ENV('APP_NAME') }}. </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <div class="col-span-2">
                    <div class="flex flex-col items-start">
                        <input
                            class="relative block w-full appearance-none rounded-none px-3 py-2 text-xs text-white placeholder-murky-200 focus:z-10 focus:border-primary-500 focus:outline-none focus:ring-primary-500 disabled:cursor-not-allowed disabled:opacity-75 !rounded-md !border-0 !bg-murky-200 !text-murky-800 !placeholder-murky-800 accent-murky-800 !ring-0 placeholder:text-xs focus:!border-transparent focus:!bg-white focus:!ring-transparent !rounded-md"
                            type="text" id="search" placeholder="#code @item">
                    </div>
                </div>
                <div class="flex justify-start md:col-start-4 md:justify-end">
                    <button id="refresh" type="button"
                        class="h-full  rounded-md bg-murky-600 px-4 text-xs duration-300 ease-in-out hover:bg-murky-500">Segarkan</button>
                </div>
                <div>
                    <select id="filter" class="relative block w-full appearance-none rounded-none px-3 py-2 text-xs text-white placeholder-murky-200 focus:z-10 focus:border-primary-500 focus:outline-none focus:ring-primary-500 disabled:cursor-not-allowed disabled:opacity-75 !rounded-md !border-0 !bg-murky-200 !text-murky-800 !placeholder-murky-800 accent-murky-800 !ring-0 placeholder:text-xs focus:!border-transparent focus:!bg-white focus:!ring-transparent !rounded-md">
    <option value="">Pilih Produk</option>
    @foreach($kategoris as $kategori)
        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
    @endforeach
</select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-x-4">
                <button type="button" id="downloadcsv"
                    class="inline-flex w-full items-center justify-center rounded-md border border-murky-500 bg-murky-600 px-4 py-2 text-xs hover:bg-murky-700 disabled:cursor-not-allowed disabled:opacity-75 md:w-auto">Download
                    CSV</button>
                <button type="button" id="downloadxlsx"
                    class="inline-flex w-full items-center justify-center rounded-md border border-murky-500 bg-murky-600 px-4 py-2 text-xs hover:bg-murky-700 disabled:cursor-not-allowed disabled:opacity-75 md:w-auto">Download
                    XLSX</button>
                <select id="entries"
                    class="inline-flex w-full cursor-pointer items-center justify-center rounded-md border border-murky-500 bg-murky-600 py-2 text-xs ring-inset hover:bg-murky-700 focus:ring-2 focus:ring-primary-500 md:w-auto">
                    <option value="5">5<!-- --> Entries</option>
                    <option value="10" selected>10<!-- --> Entries</option>
                    <option value="25">25<!-- --> Entries</option>
                    <option value="50">50<!-- --> Entries</option>
                    <option value="100">100<!-- --> Entries</option>
                </select>
            </div>
            <div class="-mx-4 overflow-x-auto ring-1 ring-murky-600 sm:mx-0 sm:rounded-lg">
                <table class="min-w-full divide-y divide-murky-600">
                    <thead>
                        <tr>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                <div
                                    class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Code</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                <div
                                    class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Item</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                <div
                                    class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Public</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                <div
                                    class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Member</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                <div
                                    class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Platinum</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                <div
                                    class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Gold</div>
                            </th>
                            <th scope="col" colspan="1"
                                class="table-cell px-3 py-3.5 text-left text-xs font-semibold text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
                                <div
                                    class="cursor-pointer select-none flex whitespace-nowrap items-center justify-between">
                                    Status</div>
                            </th>
                        </tr>
                    </thead>
                   <tbody id="result_filter">
                        <?php $no=1;?>
                                    @foreach($datas as $d)
                                    @php
                                        if($d->status == "available"){
                                            $label_pesanan = "success";
                                        }else{
                                            $label_pesanan = "danger";
                                        }
                                    @endphp
                    <tr class="even:bg-murky-700/50">
    <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
        <div class="whitespace-nowrap">{{ $d->provider_id}}</div>
    </td>
    <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
        <div class="whitespace-nowrap">{{ $d->nama_kategori }} - {{ $d->layanan }}</div>
    </td>
    <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
        <div class="whitespace-nowrap"> Rp.{{ number_format($d->harga,0,',','.') }}</div>
    </td>
    <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
        <div class="whitespace-nowrap"> Rp.{{ number_format($d->harga_member, 0, ',', '.') }}</div>
    </td>
    <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
        <div class="whitespace-nowrap"> Rp.{{ number_format($d->harga_platinum, 0, ',', '.') }}</div>
    </td>
    <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
        <div class="whitespace-nowrap"> Rp.{{ number_format($d->harga_gold, 0, ',', '.') }}</div>
    </td>
    <td class="table-cell px-3 py-3.5 text-left text-xs font-medium text-white first:table-cell first:pl-4 sm:first:pl-6 first:pr-4 last:relative last:table-cell sm:last:pr-6 [&amp;:nth-last-child(2)]:table-cell">
        <div class="whitespace-nowrap"><span class="inline-flex rounded-sm px-2 text-xs font-semibold leading-5 print:p-0 bg-emerald-200 text-emerald-900">available</span></div>
    </td>
</tr>
        
                                    <?php $no++ ;?>
                                    @endforeach
              </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@include('components.user.footer')
@push('custom_script')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil semua baris data dari tabel
        const rows = document.querySelectorAll("#result_filter tr");

        // Sembunyikan semua baris kecuali 10 pertama
        for (let i = 10; i < rows.length; i++) {
            rows[i].style.display = "none";
        }

        // Tambahkan event listener untuk select element
        document.getElementById("entries").addEventListener("change", function() {
            const selectedValue = parseInt(this.value); // Ambil nilai pilihan sebagai angka
            for (let i = 0; i < rows.length; i++) {
                if (i < selectedValue) {
                    rows[i].style.display = ""; // Tampilkan baris yang dipilih
                } else {
                    rows[i].style.display = "none"; // Sembunyikan baris yang tidak dipilih
                }
            }
        });
    });
</script>


@endpush



@endsection
