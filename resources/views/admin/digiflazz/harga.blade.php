@extends('layouts.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-12 mb-4 order-0">
    </div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-1">Harga Produk Digiflazz</h4>
                <div id="loadingSpinnerDigiflazz" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="table-responsive d-none" id="digiflazzTableContainer">
                    <table class="table table-dark table-bordered table-striped m-0" id="digiflazz-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Seller Name</th>
                                <th>Price</th>
                                <th>Provider ID</th>
                                <!--<th>Buyer Product Status</th>-->
                                <th>Seller Product Status</th>
                                <!--<th>Unlimited Stock</th>-->
                                <!--<th>Stock</th>-->
                                <!--<th>Multi</th>-->
                                <!--<th>Start Cut Off</th>-->
                                <!--<th>End Cut Off</th>-->
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $product)
                            <tr>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['category'] }}</td>
                                <td>{{ $product['brand'] }}</td>
                                <td>{{ $product['type'] }}</td>
                                <td>{{ $product['seller_name'] }}</td>
                                <td>{{ $product['price'] }}</td>
                                <td>{{ $product['buyer_sku_code'] }}</td>
                                <!--<td>{{ $product['buyer_product_status'] ? 'Yes' : 'No' }}</td>-->
                                <td>{{ $product['seller_product_status'] ? 'Aktif' : 'Tidak Aktif' }}</td>
                                <!--<td>{{ $product['unlimited_stock'] ? 'Yes' : 'No' }}</td>-->
                                <!--<td>{{ $product['stock'] }}</td>-->
                                <!--<td>{{ $product['multi'] ? 'Yes' : 'No' }}</td>-->
                                <!--<td>{{ $product['start_cut_off'] }}</td>-->
                                <!--<td>{{ $product['end_cut_off'] }}</td>-->
                                <td>{{ $product['desc'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<script>
$(document).ready(function() {
    setTimeout(function() {
        $('#loadingSpinnerDigiflazz').addClass('d-none');
        $('#digiflazzTableContainer').removeClass('d-none');
        $('#digiflazz-table').DataTable();
    }, 500); // Adjust the delay as needed
});
</script>


<script type="text/javascript">
    $(document).ready(function(){
        $('.table').DataTable({
           
        });
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
