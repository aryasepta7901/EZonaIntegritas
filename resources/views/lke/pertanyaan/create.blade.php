@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <form method="post" action="/pertanyaan">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="subpilar_id" value="{{ $subPilar->id }}">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="pertanyaan">Pertanyaan</label>
                                <input type="text" class="form-control @error('pertanyaan') is-invalid  @enderror"
                                    id="pertanyaan" name="pertanyaan" value="{{ old('pertanyaan') }}"
                                    placeholder="Isi  pertanyaan" required>
                                @error('pertanyaan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label for="info">Informasi</label>
                            <textarea id="summernote" name="info" required>{{ old('info') }} </textarea>

                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="bobot">Bobot</label>
                                <input type="number" class="form-control @error('bobot') is-invalid  @enderror"
                                    id="bobot" name="bobot" value="{{ old('bobot') }}" placeholder="Isi Nilai Bobot"
                                    min="0" step=".01" required>
                                @error('bobot')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select id="type" class="form-control opsi" name="type">
                                    <option value="">Pilih Salah Satu</option>
                                    <option value="checkbox1">Ya/Tidak</option>
                                    <option value="checkbox2">A/B/C</option>
                                    <option value="checkbox3">A/B/C/D</option>
                                    <option value="checkbox4">A/B/C/D/E</option>
                                    <option value="input">Input Value</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12" id="inputContainer"></div>

                        <div id="input" class="col-lg-12">
                            <div class="form-group">
                                <label for="opsi">Opsi</label>
                                <button id="row" type="button" class="btn btn-dark">
                                    <span class="fa fa-plus">
                                    </span>
                                </button>
                                <hr>
                                <input type="hidden" name="bobot1" value="1">
                                <input type="text" class="form-control" id="opsi" name="rincian[]"
                                    placeholder="Isi  opsi">
                            </div>
                        </div>
                        <div id="new" class="col-lg-12"></div>




                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="dokumen">Dokumen</label>
                            <button id="rowAdder" type="button" class="btn btn-dark">
                                <span class="fa fa-plus">
                                </span>
                            </button>
                        </div>
                        <div class="col-lg-12">
                            <hr>
                            <div class="form-group">
                                <input type="text" class="form-control" id="dokumen" name="dokumen[]" required
                                    placeholder="Isi  Nama Dokumen">
                            </div>
                        </div>
                        <div id="newinput" class="col-lg-12"></div>
                    </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer justify-content-between">
                @php
                    $kembali = $subPilar->id;
                @endphp
                <a href="/subpilar/{{ $kembali }}" class="btn btn-secondary"><i class="fa fa-backward"></i>
                    Kembali</a>
                <button type="submit" class="btn btn-primary">Create Pertanyaan</button>
                </form>

            </div>
        </div>
    </div>
@endsection
