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
                            <form action="{{ route('temuan.store') }}" method="POST">
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
                                        <label for="inputState">Nomor LHP & Tahun</label>
                                        <select id="inputState" class="form-control" name="lhp">
                                            <option selected disabled>- Pilih Nomor & Tahun LHP -</option>
                                            @foreach ($lhps as $lhp)
                                                <option value="{{ $lhp->id }}">{{ $lhp->no_lhp }} -
                                                    {{ $lhp->tahun }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Pemeriksaan</label>
                                        <select id="inputState" class="form-control" name="jns_pemeriksaan">
                                            <option selected disabled>- Pilih Jenis Pemeriksaan -</option>
                                            @foreach ($lhps as $lhp)
                                                <option value="{{ $lhp->judul }}">{{ $lhp->judul }}</option>
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
                                            @role('Irban')
                                                @foreach ($obriks as $obrik)
                                                    <option value="{{ $obrik->id }}">{{ $obrik->name }}</option>
                                                @endforeach
                                            @endrole
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Ringkasan Temuan</label>
                                        <input type="text" class="form-control @error('ringkasan') is-invalid @enderror"
                                            name="ringkasan" autocomplete="off">
                                        @error('ringkasan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nilai Temuan</label>
                                        <input type="number"
                                            class="form-control @error('nilai_temuan') is-invalid @enderror"
                                            name="nilai_temuan">
                                        @error('nilai_temuan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Jenis Temuan</label>
                                        <select id="inputState" class="form-control" name="jns_temuan">
                                            <option selected disabled>- Pilih Jenis Temuan -</option>
                                            <option value="Kerugian Negara" class="text-capitalize">Kerugian Negara
                                            </option>
                                            <option value="Daerah" class="text-capitalize">Daerah
                                            </option>
                                            <option value="Lain-lainnya" class="text-capitalize">Lain-lainnya
                                            </option>
                                        </select>
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
                                    <div class="form-group">
                                        <label for="inputState">Penanggung Jawab</label>
                                        <select id="penanggung_jawab" class="form-control" name="penanggung_jawab">
                                            <option selected disabled>- Pilih Penanggung Jawab -</option>
                                            <option value="Obrik" class="text-capitalize">Obrik
                                            </option>
                                            <option value="Personil" class="text-capitalize">Personil
                                            </option>
                                        </select>
                                    </div>
                                    <div id="obrik_form" style="display: none;">
                                        <div class="form-group">
                                            <label>Nilai Obrik</label>
                                            <input type="number" disabled
                                                class="form-control @error('nilai_obrik') is-invalid @enderror"
                                                name="nilai_obrik">
                                            @error('nilai_obrik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <input type="hidden" name="nilai_obrik">
                                        </div>
                                    </div>
                                    <div id="personil_form" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Jumlah Personil</label>
                                                    <input type="number"
                                                        class="form-control @error('jml_personil') is-invalid @enderror"
                                                        name="jml_personil" id="tambah_personil">
                                                    @error('jml_personil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <a id="tambah-personil" class="mt-4 btn btn-success btn-sm"><i
                                                        class="fas fa-plus"></i></a>
                                            </div>
                                        </div>


                                        <!-- Form Rombongan (jika ada) -->
                                        <div id="personil-form" class="mt-2">
                                            <!-- Form rombongan akan di-generate melalui JavaScript -->
                                        </div>
                                    </div>

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

            document.addEventListener("DOMContentLoaded", function() {
                const penanggungJawab = document.getElementById("penanggung_jawab");
                const obrikForm = document.getElementById("obrik_form");
                const personilForm = document.getElementById("personil_form");

                penanggungJawab.addEventListener("change", function() {
                    const selectedStatus = penanggungJawab.value;

                    // Sembunyikan semua form terlebih dahulu
                    obrikForm.style.display = "none";
                    personilForm.style.display = "none";

                    // Tampilkan form yang sesuai dengan status siswa yang dipilih
                    if (selectedStatus === "Obrik") {
                        obrikForm.style.display = "block";
                    } else if (selectedStatus === "Personil") {
                        personilForm.style.display = "block";
                    }
                });

                // Fungsi untuk mengatur nilai obrik dan nilai personil
                function setNilaiObrikDanPersonil(nilai) {
                    var inputNilaiObrik = document.querySelector('input[name="nilai_obrik"]');
                    var inputNilaiPersonil = document.querySelectorAll('.nilai-personil');

                    inputNilaiObrik.value = nilai;

                    inputNilaiPersonil.forEach(function(input) {
                        input.value = nilai;
                    });
                }

                // Menambahkan event listener pada input nilai rekomendasi
                var inputNilaiRekomendasi = document.querySelector('input[name="nilai_rekomendasi"]');
                inputNilaiRekomendasi.addEventListener('input', function(event) {
                    var nilaiRekomendasi = parseFloat(event.target.value);
                    setNilaiObrikDanPersonil(nilaiRekomendasi);
                });

                // Memanggil fungsi untuk menyamakan nilai personil saat halaman dimuat
                var nilaiAwal = parseFloat(inputNilaiRekomendasi.value);
                setNilaiObrikDanPersonil(nilaiAwal);
            });

            document.getElementById('tambah-personil').addEventListener('click', function() {
                var jumlahPersonil = document.getElementById('tambah_personil').value;
                var formPersonil = document.getElementById('personil-form');
                formPersonil.innerHTML = '';

                for (var i = 0; i < jumlahPersonil; i++) {
                    var personilFormHTML = `
            <div class="form-group">
                <label>Nama Personil ${i + 1}</label>
                <input type="text" class="form-control" name="name${i + 1}" autocomplete="off">
            </div>
            <div class="form-group">
                <label>NIP ${i + 1}</label>
                <input type="number" class="form-control" name="nip${i + 1}" >
            </div>
            <div class="form-group">
                <label>Nilai ${i + 1}</label>
                <input type="number" class="form-control nilai-personil" disabled name="nilai${i + 1}">
                <span class="text-danger mx-2" >* Lakukan penulisan ulang pada Nilai Rekomendasi di atas untuk mengisi nilai setiap personil</span>
                <input type="hidden" class="form-control nilai-personil"  name="nilai${i + 1}">
            </div>
        `;
                    formPersonil.insertAdjacentHTML('beforeend', personilFormHTML);
                }
            });
        </script>
    </section>
@endsection
