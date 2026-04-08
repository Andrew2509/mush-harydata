@extends('layouts.admin')

@section('content')

<style>
  
.hvrbutton {
    transition: all 0.3s ease-in-out;
     border-radius:10px;
    
}

.hvrbutton:hover {
    transform: perspective(1000px) rotateY(10deg);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
   
}

</style>
<div class="container-xxl flex-grow-1 container-p-y">
<div class="card mb-3">
    <div class="card-body">
            <!-- Display validation errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

            <div class="card-header p-4">Sync Product Automatic</div>
            <div class="card-body">
                
                <!--<form method="POST" action="{{ route('sync.produk.get.post') }}">-->
                <!--    @csrf-->
                <!--    <div class="pb-2 sm:mt-0 mr-auto flex flex-col md:flex-row gap-2">-->
                <!--        <button type="submit" class="rounded-4 inline-flex items-center justify-center rounded-md px-4 py-2.5 font-medium duration-300 gap-2 disabled:cursor-not-allowed bg-secondary-400 text-theme-text disabled:bg-secondary-200 hvrbutton">-->
                <!--            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" width="16" height="16">-->
                <!--                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"></path>-->
                <!--            </svg>-->
                <!--            <div>Sync</div>-->
                <!--        </button>-->
                        
                <!--        <a href="javascript:;" onclick="modal('EditProfit', '{{ route('detail.produk.get', 1) }}')">-->
                <!--            <button class="rounded-4 inline-flex items-center justify-center rounded-md px-4 py-2.5 font-medium duration-300 gap-2 disabled:cursor-not-allowed bg-secondary-400 text-theme-text disabled:bg-secondary-200 hvrbutton" type="button">-->
                <!--              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" width="16" height="16">-->
                <!--                 <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"></path>-->
                <!--              </svg>-->
                              
                <!--              <div>Update Price</div>-->
                <!--            </button>-->
                <!--        </a>-->
                <!--    </div>-->
                <!-- </form>-->
                
                <form action="" method="POST" id="produkForm">
                    @csrf
                    <table class="table">
                        
                        <div class="mb-3 row">
                            <label class="col-lg-2 col-form-label">Provider</label>
                            <div class="col-lg-10">
                                <select class="form-select" name="provider">
                                    <option value="digiflazz">DigiFlazz</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label class="col-lg-2 col-form-label">Kategori</label>
                            <div class="col-lg-10">
                                <select class="form-select" name="kategori" id="kategoriSelect">
                                        <option value="">Pilih Kategori Game...</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->nama }}">{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-2 row profit-inputs" style="display: none;">
                            <label class="col-lg-2 col-form-label">Profit</label>
                            <div class="col-lg-10">
                                <input type="number" step="0.01" class="form-control" value="{{ old('profit') }}" name="profit">
                            </div>
                            
                        </div>
                        
                        <div class="mb-2 row profit-inputs" style="display: none;">
                        <label class="col-lg-2 col-form-label">Profit Member</label>
                            <div class="col-lg-10">
                                <input type="number" step="0.01" class="form-control" value="{{ old('profit_member') }}" name="profit_member">
                            </div>
                        </div>
                        
                        <div class="mb-2 row profit-inputs" style="display: none;">
                            <label class="col-lg-2 col-form-label">Profit Platinum</label>
                            <div class="col-lg-10">
                                <input type="number" step="0.01" class="form-control" value="{{ old('profit_platinum') }}" name="profit_platinum">
                            </div>
                            
                        </div>
                        
                        <div class="mb-2 row profit-inputs" style="display: none;">
                        <label class="col-lg-2 col-form-label">Profit Gold</label>
                            <div class="col-lg-10">
                                <input type="number" step="0.01" class="form-control" value="{{ old('profit_gold') }}" name="profit_gold">
                            </div>
                        </div>
                    </table>
                   <div class="mb-2 row">
                        <label class="col-lg-2 col-form-label">Sync Product?</label>
                        <div class="col-lg-10">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ubahRouteCheckbox" name="ubah_route">
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/layanan') }}" class="btn btn-warning float-start">Kembali</a>
                    <div class="text-end">
                        <button class="btn btn-default" type="reset">Batal</button>
                        <button class="btn btn-primary" type="submit" name="tombol" value="submit">Simpan</button>
                        @if($errors->has('message'))
                            <div class="alert alert-danger">
                                {{ $errors->first('message') }}
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>

    document.getElementById('ubahRouteCheckbox').addEventListener('change', function () {
        var formAction = this.checked ? "{{ route('sync.produk.get.post') }}" : "{{ route('produk.get.post') }";
        document.getElementById('produkForm').setAttribute('action', formAction);
    });
    const kategoriSelect = document.getElementById('kategoriSelect');
    const profitInputs = document.querySelectorAll('.profit-inputs');

    kategoriSelect.addEventListener('change', function() {
        const selectedValue = this.value;

        
        if (selectedValue) {
            profitInputs.forEach(inputRow => {
                inputRow.style.display = 'block';
            });
        } else {
            profitInputs.forEach(inputRow => {
                inputRow.style.display = 'none';
            });
        }
    });
    
    function modal(name, link) {
        var myModal = new bootstrap.Modal($('#modal-detail'))
        $.ajax({
            type: "GET",
            url: link,
            beforeSend: function() {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html('Loading...');
            },
            success: function(result) {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html(result);
            },
            error: function() {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html('There is an error...');
            }
        });
        myModal.show();
    }
    
</script>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="modal-detail" style="border-radius:7%">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-detail-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-detail-body"></div>
        </div>
    </div>
</div>


@endsection
