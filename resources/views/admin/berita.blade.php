@extends('layouts.admin')

@section('content')
<!-- start page title -->

            <div class="container-xxl flex-grow-1 container-p-y">
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="card mb-3">
    <div class="card-body">
        <h4 class="mb-3 header-title mt-0">Tambah Gambar</h4>
        <form action="{{ route('berita.post') }}" method="POST" enctype="multipart/form-data" id="berita">
            @csrf
            <div class="form-group">
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label" for="example-fileinput">Foto Banner</label>
                    <div class="col-lg-10">
                        <input type="file" class="form-control" name="banner">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">Deskripsi</label>
                    <div class="col-lg-10">
                        <textarea name="deskripsi" id="summernotee"></textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">Tipe</label>
                    <div class="col-lg-10">
                        <select class="form-control"name="tipe">
                            <option value="banner">Banner Beranda (Legacy)</option>
                            <option value="banner_beranda">Banner Beranda (Desktop)</option>
                            <option value="banner_beranda_mobile">Banner Beranda (Mobile)</option>
                            <option value="banner_topup">Banner Topup (Desktop)</option>
                            <option value="banner_topup_mobile">Banner Topup (Mobile)</option>
                            <option value="popupp">Popup</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-1">Semua Gambar</h4>
                <div class="table-responsive-xxl">
                    <table class="table table-dark table-bordered table-striped m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Tipe</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $berita as $data)
                            <tr>
                                <th scope="row">{{ $data->id }}</th>
                                <td>
                                    @php
                                        $extension = pathinfo($data->path, PATHINFO_EXTENSION);
                                        $is_video = in_array(strtolower($extension), ['mp4', 'webm', 'ogg', 'mov']);
                                    @endphp
                                    @if($is_video)
                                        <video src="{{ asset($data->path) }}" width="250" height="75" style="border-radius: 10px;" muted loop onmouseover="this.play()" onmouseout="this.pause()"></video>
                                    @else
                                        <img src="{{ asset($data->path) }}" alt="Thumbnail" width="250" height="75" style="border-radius: 10px;">
                                    @endif
                                </td>
                                <td>{{ $data->tipe }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td><a class="btn btn-danger" href="/berita/hapus/{{ $data->id }}">Hapus</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#summernote').summernote();
        // var quill = new Quill('#snow-editor', {
        //     theme: 'snow',
        //     modules: {
        //         'toolbar': [[{ 'font': [] }, { 'size': [] }], ['bold', 'italic', 'underline', 'strike'], [{ 'color': [] }, { 'background': [] }], [{ 'script': 'super' }, { 'script': 'sub' }], [{ 'header': [false, 1, 2, 3, 4, 5, 6] }, 'blockquote', 'code-block'], [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }], ['direction', { 'align': [] }], ['link', 'image', 'video', 'formula'], ['clean']]
        //     },
        // })
        // $("#berita").on("submit",function() {
        //     $("#deskripsi").val(myEditor.children[0].innerHTML);
        // })
    });
</script>
@endsection
