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
                            <form action="{{ route('tindakan.update', $tindakan->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">

                                    @role('superadmin')
                                        <div class="form-group">
                                            <label for="inputState">Status</label>
                                            <select id="inputState" class="form-control" name="status">
                                                <option selected disabled>- Ubah Status -</option>

                                                <option value="1"
                                                    {{ $tindakan->status == 1 ? 'selected="selected"' : '' }}>
                                                    Data Sudah Terkirim</option>
                                                <option value="0"
                                                    {{ $tindakan->status == 0 ? 'selected="selected"' : '' }}>
                                                    Data Masih Tersimpan (Draft)</option>
                                            </select>
                                        </div>
                                    @endrole
                                    @role('Irban')
                                        <input type="hidden" value="{{ $tindakan->status }}" name="status">
                                    @endrole
                                    <div class="form-group">
                                        <label for="inputState">Tahun</label>
                                        <select id="inputState" class="form-control @error('lhp') is-invalid @enderror"
                                            name="lhp">
                                            <option selected disabled>- Pilih Tahun -</option>
                                            @foreach ($lhps as $lhp)
                                                <option value="{{ $lhp->id }}"
                                                    {{ $lhp->id == $tindakan->lhp_id ? 'selected="selected"' : '' }}>
                                                    {{ $lhp->tahun }}</option>
                                            @endforeach
                                        </select>
                                        @error('lhp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Obrik</label>
                                        <select id="inputState" class="form-control @error('obrik') is-invalid @enderror"
                                            name="obrik" onchange="getTemuans(this)">
                                            <option selected disabled>- Pilih Obrik -</option>
                                            @foreach ($obriks as $obrik)
                                                <option value="{{ $obrik->id }}"
                                                    {{ $obrik->id == $tindakan->obrik_id ? 'selected="selected"' : '' }}>
                                                    {{ $obrik->name }}</option>
                                            @endforeach
                                            @error('obrik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Temuan</label>
                                        <select id="inputState" class="form-control @error('temuan') is-invalid @enderror"
                                            name="temuan">
                                            <option selected disabled>- Pilih Temuan -</option>
                                        </select>
                                        @error('temuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Rekomendasi</label>
                                        <select id="inputState"
                                            class="form-control @error('rekomendasi') is-invalid @enderror"
                                            name="rekomendasi">
                                            <option selected disabled>- Pilih Rekomendasi -</option>
                                        </select>
                                        @error('rekomendasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Uraian Tindak Lanjut</label>
                                        <input type="text" class="form-control @error('uraian') is-invalid @enderror"
                                            name="uraian" value="{{ $tindakan->uraian }}">
                                        @error('uraian')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Catatan / Saran</label>
                                        <input type="text" class="form-control @error('saran') is-invalid @enderror"
                                            name="saran" value="{{ $tindakan->saran }}">
                                        @error('saran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>File</label>
                                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                                            name="file">
                                        <input type="hidden" value="{{ $tindakan->file }}" name="file_old">
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if (!empty($tindakan->file))
                                        <div class="form-group col-md-4">
                                            <div class="show-image d-inline-block" id="show-image"
                                                style="width: 150px; height: auto;">
                                                <img src='{{ url("uploads/$tindakan->file") }}'
                                                    class="img-fluid img-thumbnail" />
                                            </div>
                                        </div>
                                    @endif
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
                var tindakan = @json($tindakan); // Menyimpan data temuan ke dalam variabel JavaScript

                var temuanSelect = document.querySelector('select[name="temuan"]');
                temuanSelect.innerHTML = '<option selected disabled>- Pilih Temuan -</option>'; // Mengosongkan pilihan temuan

                var rekomendasiSelect = document.querySelector('select[name="rekomendasi"]');
                rekomendasiSelect.innerHTML = '<option selected disabled>- Pilih Rekomendasi -</option>';


                var temuanExist = false;

                temuans.forEach(function(temuan) {
                    if (temuan.obrik_id == obrikId) {
                        var option = document.createElement('option');
                        option.value = temuan.id;
                        option.text = temuan.ringkasan + ' - Rp ' + formatNumber(temuan.nilai_temuan);

                        if (temuan.id == tindakan.temuan_id ? true : false) {
                            option.setAttribute('selected', 'selected');
                        }
                        temuanSelect.appendChild(option);


                        var option2 = document.createElement('option');
                        option2.value = temuan.nilai_rekomendasi;
                        option2.text = 'Rp ' + formatNumber(temuan.nilai_rekomendasi);

                        if (temuan.nilai_rekomendasi == tindakan.rekomendasi) {
                            option2.setAttribute('selected', 'selected');
                        }

                        rekomendasiSelect.appendChild(option2);

                        temuanExist = true; // Ada temuan yang terkait dengan obrik yang dipilih
                    }
                });

                if (!temuanExist) {
                    var noOption = document.createElement('option');
                    noOption.text = 'Tidak Ada Temuan yang Tersedia Untuk Obrik ini';
                    noOption.disabled = true;
                    temuanSelect.appendChild(noOption);

                    var noOption2 = document.createElement('option');
                    noOption2.text = 'Tidak Ada Rekomendasi yang Tersedia Untuk Obrik ini';
                    noOption2.disabled = true;
                    rekomendasiSelect.appendChild(noOption2);
                }
            }

            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        </script>
    </section>
@endsection
