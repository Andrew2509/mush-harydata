@extends('layouts.admin')

@section('content')
<style>
    .sidebar-menu-card .list-group-item {
        background-color: transparent;
        color: #e8eaf6;
        border: none;
        padding: 0.8rem 1.2rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sidebar-menu-card .list-group-item:hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: #00f0ff;
    }
    .sidebar-menu-card .list-group-item.active {
        background-color: rgba(0, 240, 255, 0.12);
        color: #00f0ff;
        border-left: 3px solid #00f0ff;
    }
    .top-tab-nav {
        border-bottom: 2px solid rgba(255, 255, 255, 0.08);
        margin-bottom: 1.5rem;
        overflow-x: auto;
        white-space: nowrap;
        flex-wrap: nowrap;
    }
    .top-tab-nav .nav-link {
        color: #a5a9c0;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.8rem 1.2rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .top-tab-nav .nav-link:hover {
        color: #00f0ff;
    }
    .top-tab-nav .nav-link.active {
        color: #00f0ff;
        border-bottom-color: #007bff;
        background: transparent;
    }
    .brand-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        max-height: 450px;
        overflow-y: auto;
        padding: 5px;
    }
    .brand-btn {
        border: 1px solid rgba(0, 123, 255, 0.25);
        background-color: rgba(0, 123, 255, 0.03);
        color: #007bff;
        font-weight: 500;
        font-size: 0.85rem;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .brand-btn:hover {
        border-color: #007bff;
        background-color: rgba(0, 123, 255, 0.08);
        color: #007bff;
    }
    .brand-btn.active {
        background-color: #007bff !important;
        border-color: #007bff !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    }
    .search-kategori-input {
        background-color: rgba(15, 20, 40, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: #fff !important;
        border-radius: 6px;
    }
    .search-kategori-input::placeholder {
        color: #5a6380;
    }
    .search-kategori-input:focus {
        border-color: #00f0ff !important;
        box-shadow: 0 0 8px rgba(0, 240, 255, 0.2) !important;
    }
    .profit-card {
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        background-color: rgba(15, 20, 40, 0.4);
    }
    .preview-table th {
        color: #5a6380;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Path Title --}}
    <div style="font-size:0.85rem;color:#a5a9c0;margin-bottom:1rem;">
        Daftar Produk Prabayar <i class="fas fa-chevron-right mx-2" style="font-size:0.7rem;"></i> <span style="color:#fff;">Tambah</span>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error') || isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') ?? $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Left Sidebar --}}
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card sidebar-menu-card" style="border:1px solid rgba(255,255,255,0.08);border-radius:12px;">
                <div class="list-group list-group-flush">
                    <a href="{{ url('/layanan') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-box"></i> Produk Baru
                    </a>
                    <a href="{{ route('produk.get') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-download"></i> Daftar Produk
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9 col-md-8">
            <div class="card" style="border:1px solid rgba(255,255,255,0.08);border-radius:12px;">
                <div class="card-body p-4">
                    {{-- Search & Bulk Sync --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                        <div style="max-width: 320px; width: 100%;">
                            <input type="text" id="searchKategori" class="form-control search-kategori-input" placeholder="Cari kategori...">
                        </div>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#syncAllModal" style="display:inline-flex;align-items:center;gap:6px;">
                            <i class="fas fa-sync-alt"></i> Ambil Semua Produk dari Digiflazz
                        </button>
                    </div>

                    {{-- Horizontal Tabs --}}
                    <ul class="nav nav-tabs top-tab-nav" id="categoryTabs" role="tablist">
                        @foreach(array_keys($groupedBrands) as $index => $groupName)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if($index === 0) active @endif" id="tab-{{ Str::slug($groupName) }}" data-bs-toggle="tab" data-bs-target="#pane-{{ Str::slug($groupName) }}" type="button" role="tab" aria-controls="pane-{{ Str::slug($groupName) }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                    {{ $groupName }}
                                    @if($index === 0)
                                        <span class="badge bg-primary ms-1" style="font-size:0.65rem;">{{ count($groupedBrands[$groupName]) }}</span>
                                    @endif
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Tab Content (Brand Badges) --}}
                    <div class="tab-content" id="categoryTabContent">
                        @foreach($groupedBrands as $groupName => $brands)
                            <div class="tab-pane fade @if($loop->index === 0) show active @endif" id="pane-{{ Str::slug($groupName) }}" role="tabpanel" aria-labelledby="tab-{{ Str::slug($groupName) }}">
                                @if(empty($brands))
                                    <div class="text-center py-4 text-muted">Tidak ada kategori untuk grup ini.</div>
                                @else
                                    <div class="brand-grid mb-4">
                                        @foreach($brands as $brand)
                                            <button type="button" class="brand-btn" data-brand="{{ $brand }}">
                                                {{ $brand }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Form Area (Hidden until a brand is selected) --}}
                    <div id="syncFormContainer" style="display: none; margin-top: 2rem;">
                        <hr style="border-color: rgba(255,255,255,0.08); margin-bottom: 2rem;">
                        
                        <div class="card profit-card p-4">
                            <h5 style="color:#00f0ff;font-family:'Orbitron',sans-serif;margin-bottom:1.5rem;" id="selectedBrandTitle">
                                SINKRONISASI PRODUK: <span id="brandNameDisplay"></span>
                            </h5>

                            <form action="{{ route('produk.get.post') }}" method="POST" id="produkForm">
                                @csrf
                                <input type="hidden" name="provider" value="digiflazz">
                                <input type="hidden" name="kategori" id="hiddenKategoriInput">

                                <div class="row">
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="form-label text-white">Profit Publik (%)</label>
                                        <input type="number" step="0.01" class="form-control" name="profit" required placeholder="0">
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="form-label text-white">Profit Member (%)</label>
                                        <input type="number" step="0.01" class="form-control" name="profit_member" required placeholder="0">
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="form-label text-white">Profit Platinum (%)</label>
                                        <input type="number" step="0.01" class="form-control" name="profit_platinum" required placeholder="0">
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="form-label text-white">Profit Gold (%)</label>
                                        <input type="number" step="0.01" class="form-control" name="profit_gold" required placeholder="0">
                                    </div>
                                </div>

                                <div class="mb-4 d-flex align-items-center gap-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="ubahRouteCheckbox" name="ubah_route">
                                        <label class="form-check-label text-white ms-2" for="ubahRouteCheckbox">Sync Product? (Gunakan jika ingin update harga yang sudah ada)</label>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-secondary me-2" type="button" id="cancelFormBtn">Batal</button>
                                    <button class="btn btn-primary" type="submit" id="submitFormBtn">Simpan & Ambil Produk</button>
                                </div>
                            </form>
                        </div>

                        {{-- Product Preview list --}}
                        <div class="mt-4">
                            <h5 class="text-white mb-3">Daftar Produk Digiflazz (<span id="previewCount">0</span>)</h5>
                            <div class="table-responsive" style="max-height: 350px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px;">
                                <table class="table table-dark table-striped m-0 preview-table">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Provider SKU</th>
                                            <th>Harga Asli</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="previewProductBody">
                                        <!-- Will be rendered dynamically via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const pricelist = @json($pricelist ?? []);
    
    $(document).ready(function () {
        // Handle Brand Badge Click
        $('.brand-btn').on('click', function () {
            $('.brand-btn').removeClass('active');
            $(this).addClass('active');

            const brand = $(this).data('brand');
            $('#hiddenKategoriInput').val(brand);
            $('#brandNameDisplay').text(brand);

            // Filter products for this brand
            const brandProducts = pricelist.filter(p => p.brand && p.brand.trim() === brand.trim());
            $('#previewCount').text(brandProducts.length);

            let tbodyHtml = '';
            if (brandProducts.length === 0) {
                tbodyHtml = '<tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada detail produk.</td></tr>';
            } else {
                brandProducts.forEach(p => {
                    const priceFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(p.price);
                    const statusBadge = p.buyer_product_status 
                        ? '<span class="badge bg-success">Tersedia</span>' 
                        : '<span class="badge bg-danger">Habis</span>';

                    tbodyHtml += `
                        <tr>
                            <td>${p.product_name}</td>
                            <td><code>${p.buyer_sku_code}</code></td>
                            <td>${priceFormatted}</td>
                            <td>${statusBadge}</td>
                        </tr>
                    `;
                });
            }
            $('#previewProductBody').html(tbodyHtml);

            // Show Form
            $('#syncFormContainer').slideDown();
        });

        // Search Filter
        $('#searchKategori').on('keyup', function () {
            const query = $(this).val().toLowerCase();
            
            $('.brand-btn').each(function () {
                const brandName = $(this).text().toLowerCase();
                if (brandName.includes(query)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Toggle form action route based on "Sync Product?" checkbox
        $('#ubahRouteCheckbox').on('change', function () {
            const formAction = this.checked ? "{{ route('sync.produk.get.post') }}" : "{{ route('produk.get.post') }}";
            $('#produkForm').attr('action', formAction);
        });

        // Cancel button
        $('#cancelFormBtn').on('click', function() {
            $('#syncFormContainer').slideUp();
            $('.brand-btn').removeClass('active');
        });
    });
</script>

{{-- Modal Sync All --}}
<div class="modal fade" id="syncAllModal" tabindex="-1" aria-labelledby="syncAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #1a2238; border: 1px solid rgba(0, 240, 255, 0.15); border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <h5 class="modal-title text-white" id="syncAllModalLabel">
                    <i class="fas fa-sync-alt text-info me-2"></i> Sinkronisasi Masal Produk Digiflazz
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('produk.sync.all.digiflazz') }}" method="POST">
                @csrf
                <div class="modal-body text-white">
                    <p style="font-size:0.9rem;color:#a5a9c0;">
                        Fitur ini akan menyinkronkan seluruh produk prabayar aktif dari API Digiflazz ke database website Anda.
                    </p>
                    <div class="alert alert-info py-2" style="font-size:0.8rem;background:rgba(0,240,255,0.08);border-color:rgba(0,240,255,0.15);color:#00f0ff;border-radius:8px;">
                        <i class="fas fa-info-circle me-1"></i> 
                        Kategori baru akan dibuat otomatis jika belum terdaftar. Produk lama akan diperbarui menggunakan persentase profit lamanya, sedangkan produk baru akan diimpor dengan profit default di bawah ini.
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Profit Publik (%)</label>
                            <input type="number" step="0.01" class="form-control" name="profit" required value="5.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Profit Member (%)</label>
                            <input type="number" step="0.01" class="form-control" name="profit_member" required value="4.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Profit Platinum (%)</label>
                            <input type="number" step="0.01" class="form-control" name="profit_platinum" required value="3.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Profit Gold (%)</label>
                            <input type="number" step="0.01" class="form-control" name="profit_gold" required value="2.00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Mulai Sinkronisasi Semua</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
