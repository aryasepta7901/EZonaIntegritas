@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">
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
            <!-- /.card-header -->

            <div class="card-header d-flex justify-content-end">

                <button class="btn btn-primary" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus">
                        Tambah</i></button>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Sub Rincian</th>
                            <th>Bobot</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subRincian as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->subRincian }}</td>
                                <td class="text-center"><button class="badge badge-info">{{ $value->bobot }}</button></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" data-toggle="modal"
                                        data-target="#edit{{ $value->id }}"><i class="fa fa-pen"></i></button>
                                    <a type="button" href="/subrincian/{{ $value->id }}" class="btn btn-sm btn-info"><i
                                            class="fa fa-info"></i></a>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#hapus{{ $value->id }}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>


                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">

                <a href="/rincian" class="btn btn-secondary"><i class="fa fa-backward"></i>
                    Kembali</a>
            </div>
        </div>
    </div>

    {{-- Tambah --}}
    <div class="modal fade" id="tambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Sub Rincian</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" action="/subrincian">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="rincian" value="{{ $rincian->id }}">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="subRincian">Sub Rincian</label>
                                    <input type="text" class="form-control @error('subRincian') is-invalid  @enderror"
                                        id="subRincian" name="subRincian" value="{{ old('subRincian') }}"
                                        placeholder="Isi Nama Sub Rincian">
                                    @error('subRincian')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Sub Rincian</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- Edit --}}
    @foreach ($subRincian as $value)
        <div class="modal fade" id="edit{{ $value->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Sub Rincian</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" action="/subrincian/{{ $value->id }}">
                        @method('put')
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="rincian" value="{{ $rincian->id }}">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="subRincian">Sub Rincian</label>
                                        <input type="text"
                                            class="form-control @error('subRincian') is-invalid  @enderror" id="subRincian"
                                            name="subRincian" value="{{ old('subRincian', $value->subRincian) }}"
                                            placeholder="Isi Nama Sub Rincian">
                                        @error('subRincian')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Edit SubRincian</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endforeach


    {{-- Hapus --}}
    @foreach ($subRincian as $value)
        <div class="modal fade" id="hapus{{ $value->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Hapus</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">Apakah Anda Yakin untuk Menghapus SubRincian dengan Nama:</p>
                        <b>{{ $value->subRincian }}
                            ?</b>
                    </div>
                    <form action="/subrincian/{{ $value->id }}" method="POST" class="d-inline">
                        @method('delete')
                        @csrf
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Delete</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endforeach

@endsection
