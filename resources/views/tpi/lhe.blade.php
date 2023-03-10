@extends('layouts.backEnd.main')

@section('content')

    <div class="col-lg-8">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Ada Kesalahan</h5>
                @foreach ($errors->all() as $error)
                    {{ $error }}
                    <br>
                @endforeach

            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                Upload Surat Rekomendasi
            </div>
            <div class="card-body">
                <form action="/prov/surat" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="upload" name="surat">
                        <label class="custom-file-label" for="upload">
                            Upload</label>
                    </div>



            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end mr-3">

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                        Simpan
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <b>{{ auth()->user()->satker->nama_satker }}</b>
                    <button class="btn btn-primary"><i class="fas fa-download"></i></button>
                </div>
            </div>
            <div class="card-body">
                <p>Ini Templete Surat Untuk DiDownload</p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@endsection
