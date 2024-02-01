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
                            <form action="{{ route('obrik.update', $obrik->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Obrik</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ $obrik->name }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Jenis Obrik</label>
                                        <select id="inputState" class="form-control" name="jenis">
                                            <option selected disabled>- Pilih Jenis Obrik -</option>
                                            <option value="OPD" {{ $obrik->jenis == 'OPD' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">OPD</option>
                                            <option value="DESA"
                                                {{ $obrik->jenis == 'DESA' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">DESA</option>
                                            <option value="BOS" {{ $obrik->jenis == 'BOS' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">BOS</option>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Kecamatan</label>
                                        <select id="inputState" class="form-control" name="kecamatan">
                                            <option selected disabled>- Pilih Kecamatan Obrik -</option>
                                            <option value="Muara Bulian"
                                                {{ $obrik->kecamatan == 'Muara Bulian' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Muara Bulian</option>
                                            <option value="Muara Tembesi"
                                                {{ $obrik->kecamatan == 'Muara Tembesi' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Muara Tembesi</option>
                                            <option value="Bajubang"
                                                {{ $obrik->kecamatan == 'Bajubang' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Bajubang</option>
                                            <option value="Maro Sebo Ilir"
                                                {{ $obrik->kecamatan == 'Maro Sebo Ilir' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Maro Sebo Ilir</option>
                                            <option value="Maro Sebo Ulu"
                                                {{ $obrik->kecamatan == 'Maro Sebo Ulu' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Maro Sebo Ulu</option>
                                            <option value="Pemayung"
                                                {{ $obrik->kecamatan == 'Pemayung' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Pemayung</option>
                                            <option value="Batin XXIV"
                                                {{ $obrik->kecamatan == 'Batin XXIV' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Batin XXIV</option>
                                            <option value="Mersam"
                                                {{ $obrik->kecamatan == 'Mersam' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Mersam</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Wilayah Inspektur</label>
                                        <select id="inputState" class="form-control" name="wilayah">
                                            <option selected disabled>- Pilih Wilayah Inspektur -</option>
                                            @foreach ($wilayah as $w)
                                                <option value="{{ $w->id }}"
                                                    {{ $w->id == $obrik->wilayah_id ? 'selected="selected"' : '' }}>
                                                    {{ $w->name }}</option>
                                            @endforeach
                                        </select>
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
