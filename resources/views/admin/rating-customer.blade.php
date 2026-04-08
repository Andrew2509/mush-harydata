@extends('layouts.admin')

@section("content")
<style>
    .rating-color {
        color: #fbc634;
    }
</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-12 mb-4 order-0">
    </div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-1">Semua ulasan customer</h4>
                <div id="loadingSpinnerRatings" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="table-responsive d-none" id="ratingsTableContainer">
                    <table class="table table-dark table-bordered table-striped m-0">
                        <thead>
                            <tr>
                                <!--<th>No</th>-->
                                <th>Rating</th>
                                <th>Ulasan</th>
                                <th>Membeli</th>
                                <!--<th>Tanggal</th>-->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ratings as $key => $rating)
                            <tr>
                                <!--<th scope="row">{{ $loop->iteration }}</th>-->
                                <td>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $rating->bintang)
                                            <i class="fa fa-star rating-color"></i>
                                        @else
                                            <i class="fa fa-star"></i>
                                        @endif
                                    @endfor
                                </td>
                                <td class="text-white">{{ $rating->comment }}</td>
                                <td class="text-white">{{ $rating->layanan }}</td>
                                <!--<td class="text-light">{{ $rating->created_at->format('Y-m-d H:i:s') }}</td>-->
                                <td>
                                    <form action="{{ route('rating-customer.destroy', $rating->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Simulate a delay for demonstration purposes
    setTimeout(function() {
        $('#loadingSpinnerRatings').addClass('d-none');
        $('#ratingsTableContainer').removeClass('d-none');
    }, 500); // Adjust the delay as needed
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('.table').DataTable();
    });
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</div>
@endsection
