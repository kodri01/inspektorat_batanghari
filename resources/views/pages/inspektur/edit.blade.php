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
                            <a href="{{ route('inspektur') }}" type="button"
                                class="btn btn-primary daterange-btn icon-left btn-icon">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Tambah Data -->
                            <form action="{{ route('inspektur.update', $inspektur->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ $inspektur->name }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>NIP</label>
                                        <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                            name="nip" value="{{ $inspektur->nip }}">
                                        @error('nip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Pangkat/Golongan</label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            name="pangkat_gol" value="{{ $inspektur->pangkat_gol }}">
                                        @error('pangkat_gol')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Wilayah Inspektur</label>
                                        <select id="inputState" class="form-control" name="wilayah">
                                            <option selected disabled>- Pilih Wilayah Inspektur -</option>
                                            @foreach ($wilayah as $w)
                                                <option value="{{ $w->id }}"
                                                    {{ $w->id == $inspektur->wilayah_id ? 'selected="selected"' : '' }}>
                                                    {{ $w->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="modal-footer bg-whitesmoke gap-3">
                                        <a href="{{ route('inspektur') }}" type="button" class="btn btn-danger">Batal</a>
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
