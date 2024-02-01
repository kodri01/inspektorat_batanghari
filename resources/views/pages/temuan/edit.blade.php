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
                            <a href="{{ route('temuan') }}" type="button"
                                class="btn btn-primary daterange-btn icon-left btn-icon">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Tambah Data -->
                            <form action="{{ route('temuan.update', $temuan->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">

                                    @role('superadmin')
                                        <div class="form-group">
                                            <label for="inputState">Status</label>
                                            <select id="inputState" class="form-control" name="status">
                                                <option selected disabled>- Ubah Status -</option>

                                                <option value="1" {{ $temuan->status == 1 ? 'selected="selected"' : '' }}>
                                                    Data Sudah Terkirim</option>
                                                <option value="0" {{ $temuan->status == 0 ? 'selected="selected"' : '' }}>
                                                    Data Masih Tersimpan (Draft)</option>

                                            </select>
                                        </div>
                                    @endrole
                                    @role('Irban')
                                        <input type="hidden" value="{{ $temuan->status }}" name="status">
                                    @endrole
                                    <div class="form-group">
                                        <label for="inputState">Nomor LHP & Tahun</label>
                                        <select id="inputState" class="form-control" name="lhp">
                                            <option selected disabled>- Pilih Nomor & Tahun LHP -</option>
                                            @foreach ($lhps as $lhp)
                                                <option value="{{ $lhp->id }}"
                                                    {{ $lhp->id == $temuan->lhp_id ? 'selected="selected"' : '' }}>
                                                    {{ $lhp->no_lhp }} -
                                                    {{ $lhp->tahun }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Pemeriksaan</label>
                                        <select id="inputState" class="form-control" name="jns_pemeriksaan">
                                            <option selected disabled>- Pilih Jenis Pemeriksaan -</option>
                                            @foreach ($lhps as $lhp)
                                                <option value="{{ $lhp->judul }}"
                                                    {{ $lhp->judul == $temuan->jns_pemeriksaan ? 'selected="selected"' : '' }}>
                                                    {{ $lhp->judul }}</option>
                                            @endforeach
                                        </select>
                                        @error('jns_pemeriksaan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Obrik</label>
                                        <select id="inputState" class="form-control" name="obrik">
                                            <option selected disabled>- Pilih Obrik -</option>
                                            @foreach ($obriks as $obrik)
                                                <option value="{{ $obrik->id }}"
                                                    {{ $obrik->id == $temuan->obrik_id ? 'selected="selected"' : '' }}>
                                                    {{ $obrik->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Ringkasan Temuan</label>
                                        <input type="text" class="form-control @error('ringkasan') is-invalid @enderror"
                                            name="ringkasan" value="{{ $temuan->ringkasan }}">
                                        @error('ringkasan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nilai Temuan</label>
                                        <input type="number"
                                            class="form-control @error('nilai_temuan') is-invalid @enderror"
                                            name="nilai_temuan" value="{{ $temuan->nilai_temuan }}">
                                        @error('nilai_temuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Jenis Temuan</label>
                                        <select id="inputState" class="form-control" name="jns_temuan">
                                            <option selected disabled>- Pilih Jenis Temuan -</option>
                                            <option value="Kerugian Negara"
                                                {{ $temuan->jns_temuan == 'Kerugian Negara' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Kerugian Negara
                                            </option>
                                            <option value="Daerah"
                                                {{ $temuan->jns_temuan == 'Daerah' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Daerah
                                            </option>
                                            <option value="Lain-lainnya"
                                                {{ $temuan->jns_temuan == 'Lain-lainnya' ? 'selected="selected"' : '' }}
                                                class="text-capitalize">Lain-lainnya
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Rekomendasi</label>
                                        <input type="text"
                                            class="form-control @error('rekomendasi') is-invalid @enderror"
                                            name="rekomendasi" value="{{ $temuan->rekomendasi }}">
                                        @error('rekomendasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nilai Rekomendasi</label>
                                        <input type="number"
                                            class="form-control nilai_rekomendasi @error('nilai_rekomendasi') is-invalid @enderror"
                                            name="nilai_rekomendasi" id="nilai_rekomendasi"
                                            value="{{ $temuan->nilai_rekomendasi }}">
                                        @error('nilai_rekomendasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    @foreach ($personil as $key => $p)
                                        @if ($p->temuan_id == $temuan->id)
                                            @if ($p->obrik_id != null)
                                                {{-- <div class="form-group">
                                                    <label>Nilai Obrik</label>
                                                    <input type="number"
                                                        class="form-control @error('nilai_obrik') is-invalid @enderror"
                                                        name="nilai_obrik" disabled value="{{ $p->nilai_obrik }}"> --}}
                                                <input type="hidden" name="nilai_obrik" value="{{ $p->nilai_obrik }}">
                                                {{-- @error('nilai_obrik')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror --}}

                                                {{-- </div> --}}
                                            @else
                                                {{-- <div class="form-group">
                                                    <label>Nama Personil</label> --}}
                                                <input type="hidden"
                                                    class="form-control @error('name.' . $key) is-invalid @enderror"
                                                    name="name[{{ $key }}]" value="{{ $p->name }}">
                                                {{-- @error('name.' . $key)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>NIP</label> --}}
                                                <input type="hidden"
                                                    class="form-control @error('nip.' . $key) is-invalid @enderror"
                                                    name="nip[{{ $key }}]" value="{{ $p->nip }}">
                                                {{-- @error('nip')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Nilai Personil</label>
                                                    <input type="number"
                                                        class="form-control @error('nilai.' . $key) is-invalid @enderror nilai_personil"
                                                        name="nilai[{{ $key }}]" disabled
                                                        value="{{ $p->nilai }}"> --}}
                                                <input type="hidden" name="nilai[{{ $key }}]"
                                                    class="nilai_personil" value="{{ $p->nilai }}">
                                                {{-- @error('nilai.' . $key)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div> --}}
                                            @endif
                                        @endif
                                    @endforeach

                                    <div class="modal-footer bg-whitesmoke gap-3">
                                        <a href="{{ route('temuan') }}" type="button" class="btn btn-danger">Batal</a>
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

        <script>
            function updateNilaiPersonil(nilai) {
                var inputNilaiPersonil = document.querySelectorAll('.nilai_personil');

                inputNilaiPersonil.forEach(function(input) {
                    input.value = nilai;
                });
            }

            function updateNilaiObrik(nilai) {
                var inputNilaiObrik = document.querySelector('input[name="nilai_obrik"]');


                inputNilaiObrik.value = nilai;
            }

            var inputNilaiRekomendasi = document.getElementById('nilai_rekomendasi');
            inputNilaiRekomendasi.addEventListener('input', function(event) {
                var nilaiRekomendasi = parseFloat(event.target.value);
                updateNilaiPersonil(nilaiRekomendasi);
            });

            var inputNilaiRekomendasi = document.getElementById('nilai_rekomendasi');
            inputNilaiRekomendasi.addEventListener('input', function(event) {
                var nilaiRekomendasi = parseFloat(event.target.value);
                updateNilaiObrik(nilaiRekomendasi);
            });

            var nilaiAwal = parseFloat(inputNilaiRekomendasi.value);
            updateNilaiObrik(nilaiAwal);
            updateNilaiPersonil(nilaiAwal);
        </script>


    </section>
@endsection
