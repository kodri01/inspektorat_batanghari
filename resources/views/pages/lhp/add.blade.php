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

                        <div class="card-body">
                            <!-- Tambah Data -->
                            <form action="{{ route('lhp.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                            name="tahun">
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor LHP</label>
                                        <input type="number" class="form-control @error('no_lhp') is-invalid @enderror"
                                            name="no_lhp">
                                        @error('no_lhp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal LHP</label>
                                        <input type="date" class="form-control @error('tgl_lhp') is-invalid @enderror"
                                            name="tgl_lhp">
                                        @error('tgl_lhp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="judul">Judul</label>
                                        <textarea class="form-control @error('judul') is-invalid @enderror" id="judul" cols="30" rows="10"
                                            name="judul"></textarea>
                                        @error('judul')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="file">File</label>
                                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                                            id="file" name="file" aria-describedby="file">
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="modal-footer bg-whitesmoke gap-3">
                                        <a href="{{ route('obrik') }}" type="button" class="btn btn-danger">Batal</a>
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
