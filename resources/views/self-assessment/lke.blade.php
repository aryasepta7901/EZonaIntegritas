@extends('layouts.backEnd.main')

@section('content')
    @can('pic')
        {{-- Jika status rekapitulasi masih dalam tahap penilaian mandiri maka: --}}
        @if ($rekap->status == 0 || $rekap->status == 2)
            <div class="col-lg-12 mb-3 d-flex justify-content-end">
                <button class="btn btn-primary" data-toggle="modal" data-target="#simpan"><i class="fa fa-save">
                        Kirim LKE</i></button>
            </div>
            <div class="col-lg-12 mb-3 d-flex justify-content-end">
                <button class="btn btn-primary" data-toggle="modal" data-target="#cetak"><i class="fa fa-save">
                        Cetak</i></button>
            </div>
        @elseif($rekap->status == 5)
            {{-- Jika status rekapitulasi dikembalikan dari TPI --}}
            <div class="col-lg-12 mb-3 d-flex justify-content-end">
                <button class="btn btn-primary" data-toggle="modal" data-target="#kirimTPI"><i class="fa fa-save">
                        Kirim LKE</i></button>
            </div>
        @endif
    @endcan
    {{-- Hanya Bisa Diakses Oleh BPS Provinsi --}}
    @can('EvalProv')
        @if ($rekap->status == 1)
            <div class="col-lg-12 mb-3 d-flex justify-content-end">
                <button class="btn btn-success m-2" data-toggle="modal" data-target="#setuju"><i class="fa fa-save">
                        Setuju</i></button>
                <button class="btn btn-warning m-2" data-toggle="modal" data-target="#revisi"><i class="fa fa-save">
                        Revisi</i></button>
                <button class="btn btn-danger m-2" data-toggle="modal" data-target="#tolak"><i class="fa fa-save">
                        Tolak</i></button>
            </div>
        @endif
    @endcan
    <div class="col-lg-4">
        <div class="info-box bg-light">
            <div class="info-box-content">
                <span class="info-box-text text-center text-bold mb-3">{{ $rekap->satker->nama_satker }}</span>
                {{-- Rincian Pengungkit --}}
                @php
                    $nilai_sa = $nilaiPengungkit->sum('nilai_sa');
                    $nilai_dl = $nilaiPengungkit->sum('nilai_dl');
                @endphp
                {{-- Rincian Hasil --}}
                @php
                    $nilai_sa += $nilaiHasil->sum('nilai');
                    $nilai_dl += $nilaiHasil->sum('nilai');
                @endphp
                <div class="row">
                    @if ($rekap->status == 5 || $rekap->status == 6 || $rekap->status == 7)
                        @php
                            $lg = 'col-lg-6';
                        @endphp
                    @else
                        @php
                            $lg = 'col-lg-12';
                        @endphp
                    @endif
                    {{-- Self-Assessment --}}
                    <div class="{{ $lg }}">
                        <span class="info-box-number text-center text-muted mb-3">{{ round($nilai_sa, 2) }}</span>
                        <span class="info-box-text text-center text-muted mb-0">Nilai Zona Integritas</span>
                    </div>
                    @if ($rekap->status == 5 || $rekap->status == 6 || $rekap->status == 7)
                        {{-- Desk-Evaluation --}}
                        <div class="col-lg-6">
                            <span class="info-box-number text-center text-muted mb-3">{{ round($nilai_dl, 2) }}</span>
                            <span class="info-box-text text-center text-muted mb-0">Nilai Desk-Evaluation </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="info-box bg-info">
            <div class="info-box-content">
                <span class="info-box-text text-bold mb-3">LKE Zona Integritas {{ $rekap->tahun }}</span>
                <div class="row">
                    {{-- Self Assessment --}}
                    <div class="{{ $lg }}">
                        <span class="info-box-text">Total Pengungkit Self-Assessment</span>
                        @php
                            $tot_jumlah_soal = $pertanyaan;
                            $tot_soal_terjawab = $selfAssessment;
                            $Totprogress = round(($tot_soal_terjawab * 100) / $tot_jumlah_soal, 2);
                        @endphp
                        <div class="progress ">
                            <div class="progress-bar" style="width: {{ $Totprogress }}%"></div>
                        </div>
                        <span class="info-box-number d-flex justify-content-end ">
                            <b class="h5 text-bold">{{ $Totprogress }}%</b>
                        </span>
                    </div>
                    {{-- Desk Evaluation --}}
                    @if ($rekap->status == 5 || $rekap->status == 6 || $rekap->status == 7)
                        <div class="col-lg-6">
                            <span class="info-box-text">Total Pengungkit Desk-Evaluation </span>
                            @php
                                $tot_soal_terjawab = $DeskEvaluation;
                                $Totprogress = round(($tot_soal_terjawab * 100) / $tot_jumlah_soal, 2);
                            @endphp
                            <div class="progress ">
                                <div class="progress-bar" style="width: {{ $Totprogress }}%"></div>
                            </div>
                            <span class="info-box-number d-flex justify-content-end ">
                                <b class="h5 text-bold">{{ $Totprogress }}%</b>
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    {{-- Rincian Pengungkit --}}
    <b class="mb-3">Rincian Pengungkit</b>
    @foreach ($rincianPengungkit as $value)
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <b>SubRincian {{ $value->subRincian }}</b>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($value->pilar as $value)
                                @php
                                    $jumlah_soal = App\Models\Pertanyaan::where('subpilar_id', 'LIKE', '%' . $value->id . '%')->count();
                                    $soal_terjawab = App\Models\selfAssessment::where('pertanyaan_id', 'LIKE', '%' . $value->id . '%')
                                        ->where('rekapitulasi_id', $rekap->id)
                                        ->count(); //mengambil nilai
                                    $progress = round(($soal_terjawab * 100) / $jumlah_soal, 2);
                                @endphp
                                <div class="col-lg-4">
                                    {{-- Jika Evaluator Prov Koreksi --}}
                                    @can('EvalProv')
                                        @php
                                            $link = '/prov/evaluasi';
                                        @endphp
                                    @endcan
                                    {{-- Jika PIC sedang self --}}
                                    @can('pic')
                                        @php
                                            $link = '/lke';
                                        @endphp
                                    @endcan
                                    <a href="{{ $link }}/{{ $rekap->id }}/{{ $value->id }}">
                                        @php
                                            if ($progress >= 0 && $progress <= 25) {
                                                $warna = 'orange';
                                            } elseif ($progress >= 25 && $progress <= 50) {
                                                $warna = 'warning';
                                            } elseif ($progress >= 50 && $progress <= 75) {
                                                $warna = 'teal';
                                            } elseif ($progress >= 75 && $progress <= 100) {
                                                $warna = 'success';
                                            }
                                        @endphp
                                        <div class="info-box bg-{{ $warna }}">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-bold mb-3 text-center">
                                                    {{ $value->pilar }}
                                                </span>

                                                @php
                                                    // Ambil Nilai Pengungkit
                                                    $nilai = $value->RekapPengungkit->where('rekapitulasi_id', $rekap->id)->first();
                                                @endphp
                                                <div class="row">
                                                    {{-- Self Assessment --}}
                                                    <div class="col-lg-12">
                                                        <span class="info-box-text  text-bold   text-center">
                                                            Self-Assessment
                                                        </span>
                                                        <span class="info-box-number">
                                                            {{-- Jika nilai ada di database --}}
                                                            @if ($nilai !== null)
                                                                {{ round($nilai->nilai_sa, 2) }}
                                                            @else
                                                                0
                                                            @endif /
                                                            {{ $value->bobot }}
                                                        </span>
                                                        <div class="progress ">
                                                            <div class="progress-bar" style="width: {{ $progress }}% ">
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <small>Menjawab {{ $soal_terjawab }} dari {{ $jumlah_soal }}
                                                                Soal
                                                            </small>
                                                            <small class="info-box-number">{{ $progress }}%</small>
                                                        </div>
                                                    </div>
                                                    {{-- Desk-Evaluation --}}
                                                    @if ($rekap->status == 5 || $rekap->status == 6 || $rekap->status == 7)
                                                        <div class="col-lg-12">
                                                            <span class="info-box-text text-bold  text-center">
                                                                Desk-Evaluation
                                                            </span>
                                                            <span class="info-box-number">
                                                                {{-- Jika nilai ada di database --}}
                                                                @if ($nilai !== null)
                                                                    @php
                                                                        $nilai = $nilai->nilai_dl;
                                                                        $soal_terjawab = App\Models\DeskEvaluation::where('id', 'LIKE', '%' . $value->id . '%')
                                                                            ->where('rekapitulasi_id', $rekap->id)
                                                                            ->count('jawaban_dl'); //mengambil nilai
                                                                    @endphp
                                                                    @php
                                                                        $progress = round(($soal_terjawab * 100) / $jumlah_soal, 2);
                                                                        
                                                                    @endphp
                                                                    {{ round($nilai, 2) }}
                                                                @else
                                                                    0
                                                                @endif /
                                                                {{ $value->bobot }}
                                                            </span>
                                                            <div class="progress ">
                                                                <div class="progress-bar"
                                                                    style="width: {{ $progress }}% ">
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <small>Menjawab {{ $soal_terjawab }} dari
                                                                    {{ $jumlah_soal }}
                                                                    Soal
                                                                </small>
                                                                <small class="info-box-number">{{ $progress }}%</small>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    @endforeach
    {{-- Rincian Hasil --}}
    <b class="mb-3">Rincian Hasil</b>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="card-body">
                    <div class="row">
                        @foreach ($rincianHasil as $value)
                            <div class="col-lg-4">
                                <div class="info-box bg-info">
                                    <div class="info-box-content" style="height: 150px">
                                        <span class="info-box-number text-bold mb-3 text-center">
                                            {{ $value->pilar }}
                                        </span>
                                        @php
                                            $nilaiHasil = $value->RekapHasil->where('satker_id', $rekap->satker_id)->first();
                                        @endphp
                                        <span class="info-box-number text-center">
                                            {{-- Jika nilai ada di database --}}
                                            Nilai :
                                            @if ($nilaiHasil != null)
                                                {{ $nilaiHasil->nilai }}
                                            @else
                                                0
                                            @endif /
                                            {{ $value->bobot }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Jika yang akses Tim Evaluator Provinsi --}}
    @can('EvalProv')
        <a href="/prov/evaluasi" class="btn btn-secondary ml-2 mb-3"><i class="fa fa-backward"></i>
            Kembali</a>
    @endcan
    {{-- Jika yang akses  PIC --}}
    @can('pic')
        <a href="/lke" class="btn btn-secondary ml-2 mb-3"><i class="fa fa-backward"></i>
            Kembali</a>
    @endcan
    {{-- PIC Satker --}}
    {{-- Kirim LKE --}}
    <div class="modal fade" id="simpan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apakah Kamu Yakin untuk Mengirim LKE?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" action="/lke/{{ $rekap->id }}">
                    @method('put')
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $rekap->id }}">
                        <input type="hidden" name="status" value="1">
                        <p> <b> Note:</b> <br></p>
                        <p>Harap Periksa Kembali Isian anda , apakah sudah lengkap atau tidak , jika LKE
                            sudah dikirim maka
                            tidak akan bisa diisi kembali. LKE akan dikirim dan di cek oleh <b>Validator Provinsi</b></p>
                        <button class="btn btn-sm btn-info">Total Nilai : {{ $nilai_sa }}</button>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="cetak">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rekapitulasi LKE</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="example1" class="table table-bordered table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Penilaian</th>
                                <th>Bobot</th>
                                <th>Penjelasan</th>
                                <th>Pilihan Jawaban</th>
                                <th>Jawaban</th>
                                <th>Nilai</th>
                                <th>Uraian Bukti Dukung</th>
                                <th>Link Bukti Dukung</th>
                            </tr>
                        </thead>
                        <tbody></tbody>

                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- Kirim LKE ke TPI --}}
    <div class="modal fade" id="kirimTPI">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apakah Kamu Yakin untuk Mengirim LKE?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="/lke/{{ $rekap->id }}">
                    @method('put')
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $rekap->id }}">
                        <input type="hidden" name="status" value="4">

                        <p> <b> Note:</b> <br></p>
                        <p>Harap Periksa Kembali Isian anda , apakah sudah lengkap atau tidak , jika LKE
                            sudah dikirim maka
                            tidak akan bisa diisi kembali. LKE akan dikirim dan di cek oleh <b>Tim Penilai Internal</b></p>


                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- Evaluasi Provinsi --}}
    {{-- Setuju LKE --}}
    <div class="modal fade" id="setuju">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apakah Kamu Yakin untuk Menyetujui LKE?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" action="/prov/evaluasi/{{ $rekap->id }}">
                    @method('put')
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $rekap->id }}">
                        <input type="hidden" name="status" value="4">
                        <p> <b> Note:</b> <br></p>
                        <p>LKE yang telah disetujui akan dikirimkan ke TPI Inspektorat Utama</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- Revisi LKE --}}
    <div class="modal fade" id="revisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apakah Kamu Yakin untuk Mengirimkan Kembali LKE?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" action="/prov/evaluasi/{{ $rekap->id }}">
                    @method('put')
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $rekap->id }}">
                        <input type="hidden" name="status" value="2">
                        <p> <b> Note:</b> <br></p>
                        <p>LKE akan dikembalikan ke {{ $rekap->satker->nama_satker }} untuk diperbaiki kembali</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- Tolak LKE --}}
    <div class="modal fade" id="tolak">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Apakah Kamu Yakin untuk Menolak LKE?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" action="/prov/evaluasi/{{ $rekap->id }}">
                    @method('put')
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $rekap->id }}">
                        <input type="hidden" name="status" value="3">
                        <p> <b> Note:</b> <br></p>
                        <p>LKE : {{ $rekap->satker->nama_satker }} akan ditolak dan dilakukan pembinaan</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
