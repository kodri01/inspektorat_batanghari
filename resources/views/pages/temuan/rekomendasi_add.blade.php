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
                            <a href="{{ route('rekomendasi', $rekomen->id) }}" type="button"
                                class="btn btn-primary daterange-btn icon-left btn-icon">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Tambah Data -->
                            <form action="{{ route('rekomendasi.store', $rekomen->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">

                                    <input type="hidden" name="wilayah" id="" value="{{ $rekomen->wilayah_id }}">
                                    <input type="hidden" name="lhp" id="" value="{{ $rekomen->lhp_id }}">
                                    <input type="hidden" name="obrik" id="" value="{{ $rekomen->obrik_id }}">

                                    <div class="form-group">
                                        <label>Nilai Temuan</label>
                                        <input type="text"
                                            class="form-control @error('rekomendasi') is-invalid @enderror"
                                            name="rekomendasi" autocomplete="off" disabled
                                            value="{{ 'Rp ' . number_format($rekomen->nilai_temuan, 0, ',', '.') }}">
                                        @error('rekomendasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Total Rekomendasi Tercatat</label>
                                        <input type="text"
                                            class="form-control @error('rekomendasi') is-invalid @enderror"
                                            name="rekomendasi" autocomplete="off" disabled
                                            value="{{ 'Rp ' . number_format($nilaiRekom->total_rekomendasi, 0, ',', '.') }}">
                                        @error('rekomendasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Sisa Nilai Rekomendasi</label>
                                        <input type="text"
                                            class="form-control @error('rekomendasi') is-invalid @enderror"
                                            name="rekomendasi" autocomplete="off" disabled
                                            value="{{ 'Rp ' . number_format($sisaRekom, 0, ',', '.') }}">
                                        @error('rekomendasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Rekomendasi</label>
                                        <input type="text"
                                            class="form-control @error('rekomendasi') is-invalid @enderror"
                                            name="rekomendasi" autocomplete="off">
                                        @error('rekomendasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nilai Rekomendasi</label>
                                        <input type="number"
                                            class="form-control @error('nilai_rekomendasi') is-invalid @enderror"
                                            name="nilai_rekomendasi">
                                        @error('nilai_rekomendasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="modal-footer bg-whitesmoke gap-3">
                                        <a href="{{ route('rekomendasi', $rekomen->id) }}" type="button"
                                            class="btn btn-danger">Batal</a>
                                        <button class="btn btn-primary" name="tambahData">Simpan</button>
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
