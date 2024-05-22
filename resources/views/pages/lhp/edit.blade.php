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
                            <form action="{{ route('lhp.update', $lhp->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                            name="tahun" value="{{ $lhp->tahun }}">
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor LHP</label>
                                        <input type="text" class="form-control @error('no_lhp') is-invalid @enderror"
                                            name="no_lhp" value="{{ $lhp->no_lhp }}">
                                        @error('no_lhp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal LHP</label>
                                        <input type="date" class="form-control @error('tgl_lhp') is-invalid @enderror"
                                            name="tgl_lhp"
                                            value="{{ old('tgl_lahir', \Carbon\Carbon::createFromFormat('Y-m-d', $lhp->tgl_lhp)->format('Y-m-d')) }}">
                                        @error('tgl_lhp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="judul">Judul</label>
                                        <input type="text" name="judul" value="{{ $lhp->judul }}"
                                            class="form-control">
                                        @error('judul')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="file">File</label>
                                        <input type="hidden" value="{{ $lhp->upload }}" name="filex">
                                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                                            id="file" name="file" aria-describedby="file">
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="modal-footer bg-whitesmoke gap-3">
                                        <a href="{{ route('lhp') }}" type="button" class="btn btn-danger">Batal</a>
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
