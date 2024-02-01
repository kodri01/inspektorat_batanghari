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
                            <form action="{{ route('tindakan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    @role('Irban')
                                        <input type="hidden" name="wilayah" id=""
                                            value="{{ auth()->user()->wilayah_id }}">
                                    @endrole
                                    @role('superadmin')
                                        <div class="form-group">
                                            <label for="wilayah">Wilayah Inspektur</label>
                                            <select id="wilayah" class="form-control" name="wilayah"
                                                onchange="getWilayah(this)">
                                                <option selected disabled>- Pilih Wilayah Inspektur -</option>
                                                @foreach ($wilayah as $list)
                                                    <option value="{{ $list->id }}" class="text-capitalize">
                                                        {{ $list->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endrole
                                    <div class="form-group">
                                        <label for="inputState">Tahun</label>
                                        <select id="inputState" class="form-control" name="lhp">
                                            <option selected disabled>- Pilih Tahun -</option>
                                            @foreach ($lhps as $lhp)
                                                <option value="{{ $lhp->id }}">{{ $lhp->tahun }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Obrik</label>
                                        <select id="inputState" class="form-control" name="obrik"
                                            onchange="getTemuans(this)">
                                            <option selected disabled>- Pilih Obrik -</option>
                                            @role('Irban')
                                                @foreach ($obriks as $obrik)
                                                    <option value="{{ $obrik->id }}">{{ $obrik->name }}</option>
                                                @endforeach
                                            @endrole
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Temuan</label>
                                        <select id="inputState" onchange="getRekomen(this)" class="form-control"
                                            name="temuan">
                                            <option selected disabled>- Pilih Temuan -</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Rekomendasi</label>
                                        <select id="inputState" class="form-control" name="rekomendasi">
                                            <option selected disabled>- Pilih Rekomendasi -</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Uraian Tindak Lanjut</label>
                                        <input type="text" class="form-control @error('uraian') is-invalid @enderror"
                                            name="uraian" autocomplete="off">
                                        @error('uraian')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nilai Tindak Lanjut</label>
                                        <input type="number" class="form-control @error('nilai_tl') is-invalid @enderror"
                                            name="nilai_tl" autocomplete="off">
                                        @error('nilai_tl')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Status Tindak Lanjut</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option selected disabled>- Pilih Status -</option>
                                            <option value="Selesai">Selesai</option>
                                            <option value="Dalam Proses">Dalam Proses</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Catatan / Saran</label>
                                        <input type="text" class="form-control @error('saran') is-invalid @enderror"
                                            name="saran" autocomplete="off">
                                        @error('saran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>File</label>
                                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                                            name="file">
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="modal-footer bg-whitesmoke gap-3">
                                        <a href="{{ route('tindakan') }}" type="button" class="btn btn-danger">Batal</a>
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
            function getTemuans(select) {
                var obrikId = select.value; // Mengambil nilai ID obrik yang dipilih
                var temuans = @json($temuans); // Menyimpan data temuan ke dalam variabel JavaScript

                var temuanSelect = document.querySelector('select[name="temuan"]');
                temuanSelect.innerHTML = '<option selected disabled>- Pilih Temuan -</option>';


                var temuanExist = false; // Variabel penanda untuk menunjukkan keberadaan temuan

                temuans.forEach(function(temuan) {
                    if (temuan.obrik_id == obrikId) {
                        var option = document.createElement('option');
                        option.value = temuan.id;
                        option.text = temuan.ringkasan + ' - Rp ' + formatNumber(temuan.nilai_temuan);
                        temuanSelect.appendChild(option);

                        temuanExist = true; // Ada temuan yang terkait dengan obrik yang dipilih
                    }
                });

                if (!temuanExist) {
                    var noOption = document.createElement('option');
                    noOption.text = 'Tidak Ada Temuan yang Tersedia Untuk Obrik ini';
                    noOption.disabled = true;
                    temuanSelect.appendChild(noOption);
                }

            }

            function getRekomen(select) {
                var temuanId = select.value; // Mengambil nilai ID obrik yang dipilih
                var rekomens = @json($rekomens); // Menyimpan data temuan ke dalam variabel JavaScript

                var rekomendasiSelect = document.querySelector('select[name="rekomendasi"]');
                rekomendasiSelect.innerHTML = '<option selected disabled>- Pilih Rekomendasi -</option>';

                var rekomenExist = false; // Variabel penanda untuk menunjukkan keberadaan temuan

                rekomens.forEach(function(rekomen) {
                    if (rekomen.temuan_id == temuanId) {
                        var option = document.createElement('option');
                        option.value = rekomen.id;
                        option.text = rekomen.rekomendasi + ' - Rp ' + formatNumber(rekomen.nilai_rekomendasi);
                        rekomendasiSelect.appendChild(option);

                        rekomenExist = true; // Ada temuan yang terkait dengan obrik yang dipilih
                    }
                });

                if (!rekomenExist) {
                    var noOption = document.createElement('option');
                    noOption.text = 'Tidak Ada Rekomendasi yang Tersedia Untuk Obrik ini';
                    noOption.disabled = true;
                    rekomendasiSelect.appendChild(noOption);
                }
            }

            function getWilayah(select) {
                var wilayahId = select.value; // Mengambil nilai ID obrik yang dipilih
                var obriks = @json($obriks); // Menyimpan data temuan ke dalam variabel JavaScript

                var obrikSelect = document.querySelector('select[name="obrik"]');
                obrikSelect.innerHTML = '<option selected disabled>- Pilih Obrik -</option>'; // Mengosongkan pilihan temuan

                var obrikExist = false; // Variabel penanda untuk menunjukkan keberadaan temuan

                obriks.forEach(function(obrik) {
                    if (obrik.wilayah_id == wilayahId) {
                        var option = document.createElement('option');
                        option.value = obrik.id;
                        option.text = obrik.name;
                        obrikSelect.appendChild(option);

                        obrikExist = true; // Ada temuan yang terkait dengan obrik yang dipilih
                    }
                });

                if (!obrikExist) {
                    var noOption = document.createElement('option');
                    noOption.text = 'Tidak Ada Obrik yang Tersedia Untuk Wilayah ini';
                    noOption.disabled = true;
                    obrikSelect.appendChild(noOption);
                }
            }

            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        </script>
    </section>
@endsection
