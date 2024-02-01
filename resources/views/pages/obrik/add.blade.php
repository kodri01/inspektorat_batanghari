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
                            <a href="{{ route('obrik') }}" type="button"
                                class="btn btn-primary daterange-btn icon-left btn-icon">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Tambah Data -->
                            <form action="{{ route('obrik.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Obrik</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" autocomplete="off">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Jenis Obrik</label>
                                        <select id="inputState" class="form-control @error('jenis') is-invalid @enderror"
                                            name="jenis">
                                            <option selected disabled>- Pilih Jenis Obrik -</option>
                                            <option value="OPD" class="text-capitalize">OPD
                                            </option>
                                            <option value="DESA" class="text-capitalize">DESA
                                            </option>
                                            <option value="BOS" class="text-capitalize">BOS
                                            </option>
                                        </select>
                                        @error('jenis')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Kecamatan</label>
                                        <select id="inputState"
                                            class="form-control @error('kecamatan') is-invalid @enderror" name="kecamatan">
                                            <option selected disabled>- Pilih Kecamatan Obrik -</option>
                                            <option value="Muara Bulian" class="text-capitalize">Muara Bulian
                                            </option>
                                            <option value="Muara Tembesi" class="text-capitalize">Muara Tembesi
                                            </option>
                                            <option value="Bajubang" class="text-capitalize">Bajubang
                                            </option>
                                            <option value="Maro Sebo Ilir" class="text-capitalize">Maro Sebo Ilir
                                            </option>
                                            <option value="Maro Sebo Ulu" class="text-capitalize">Maro Sebo Ulu
                                            </option>
                                            <option value="Pemayung" class="text-capitalize">Pemayung
                                            </option>
                                            <option value="Batin XXIV" class="text-capitalize">Batin XXIV
                                            </option>
                                            <option value="Mersam" class="text-capitalize">Mersam
                                            </option>
                                        </select>
                                        @error('kecamatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Wilayah Inspektur</label>
                                        <select id="inputState" class="form-control @error('wilayah') is-invalid @enderror"
                                            name="wilayah">
                                            <option selected disabled>- Pilih Wilayah Inspektur -</option>
                                            @foreach ($wilayah as $list)
                                                <option value="{{ $list->id }}" class="text-capitalize">
                                                    {{ $list->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('wilayah')
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
