@extends('layouts.main')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1><?= $judul ?></h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- <h4>Basic DataTables</h4> -->
                            <a href="{{ route('tindakan') }}" type="button"
                                class="btn btn-primary daterange-btn icon-left btn-icon">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Tambah Data -->
                            <form action="{{ route('tindakan.proses', $tindakan->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">

                                    <input type="hidden" name="nilai_selesai" value="{{ $tindakan->nilai_selesai }}">
                                    <input type="hidden" name="nilai_sisa" value="{{ $tindakan->nilai_sisa }}">
                                    <input type="hidden" name="nilai_dalam_proses"
                                        value="{{ $tindakan->nilai_dalam_proses }}">
                                    <input type="hidden" name="rekomen" value="{{ $rekomen->nilai_rekomendasi }}">
                                    <input type="hidden" name="statusTl" value="{{ $tindakan->status_tl }}">

                                    <div class="form-group">
                                        <label>Obrik</label>
                                        <input type="text" disabled
                                            class="form-control @error('obrik') is-invalid @enderror" name="obrik"
                                            value="{{ $obrik->name }}" placeholder="Masukan Nilai Tindak Lanjut">
                                        @error('obrik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Temuan</label>
                                        <input type="text" disabled
                                            class="form-control @error('obrik') is-invalid @enderror" name="temuan"
                                            value="{{ $temuan->ringkasan }} - {{ 'Rp ' . number_format($rekomen->nilai_rekomendasi, 0, ',', '.') }}"
                                            placeholder="Masukan Nilai Tindak Lanjut">
                                        @error('obrik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nilai Belum Ditindak</label>
                                        <input type="text" class="form-control @error('nilai_sisa') is-invalid @enderror"
                                            disabled name="nilai_sisa"
                                            value="{{ 'Rp ' . number_format($tindakan->nilai_sisa, 0, ',', '.') }}">

                                        @error('nilai_sisa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nilai Tindak Lanjut</label>
                                        <input type="text" class="form-control @error('nilai_tl') is-invalid @enderror"
                                            name="nilai_tl" value="{{ old('nilai_tl') }}"
                                            placeholder="Masukan Nilai Tindak Lanjut">
                                        @error('nilai_tl')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="modal-footer bg-whitesmoke gap-3">
                                        <a href="{{ route('tindakan') }}" type="button" class="btn btn-danger">Batal</a>
                                        <button class="btn btn-primary" name="tambahData">Proses</button>
                                    </div>
                                </div>
                            </form>
                            <!-- penutup Tambah Data -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
