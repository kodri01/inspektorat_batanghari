<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 20px;
            font-size: 12px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #0e0d0d;
            border-top: 1px solid #0e0d0d;
            border-left: 1px solid #0e0d0d;
            border-right: 1px solid #0e0d0d;
        }

        th {
            background-color: #B7B2B2;
            text-align: center;
        }

        th.up {
            vertical-align: middle;
        }

        td.end {
            text-align: end;
        }

        div .inpektur {
            margin-left: 63rem;
        }

        tfoot {
            text-align: center;
            font-weight: 600;
            background-color: #B7B2B2;

        }

        .container {
            display: flex;
            align-items: center;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 20px;
            /* Jarak antara logo dan label */
        }

        .logo {
            width: 60px;
        }

        .label {
            text-align: center;
            margin-top: -70px;
        }

        .text-bold {
            font-weight: bold;
            line-height: 1.5;
            /* Spasi antar baris */
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ public_path('assets/logo.png') }}" class="logo" alt="Logo">
            <div class="label text-bold">
                DAFTAR POKOK-POKOK HASIL PEMERIKSAAN <br>
                APARAT PENGAWASAN FUNGSIONAL INSPEKTORAT KABUPATEN BATANG HARI <br>
                TAHUN PEMERIKSAAN {{ $tahun }}
            </div>
        </div>
    </div>

    <div class="">
        <table class="">
            <thead>
                <tr>
                    <th class="up">#</th>
                    <th class="up">Kategori</th>
                    <th class="up">No LHP</th>
                    <th class="up">Judul Temuan</th>
                    <th>Nilai Temuan</th>
                    <th>Rekomendasi</th>
                    <th>Nilai Rekomendasi</th>
                    <th>Uraian</th>
                    <th>Nilai Selesai</th>
                    <th>Dalam Proses</th>
                    <th>Belum Ditindak</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $displayedJenis = []; @endphp
                @foreach ($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->obrik->jenis }}</td>
                        <td>{{ $d->lhp->no_lhp }}</td>

                        @if (!in_array($d->temuan->ringkasan, $displayedJenis))
                            <td>{{ $d->temuan->ringkasan }}</td>
                            @php $displayedJenis[] = $d->temuan->ringkasan; @endphp
                        @else
                            <td></td>
                        @endif
                        @if (!in_array($d->temuan->nilai_temuan, $displayedJenis))
                            <td>{{ 'Rp ' . number_format($d->temuan->nilai_temuan, 0, ',', '.') }}</td>
                            @php $displayedJenis[] = $d->temuan->nilai_temuan; @endphp
                        @else
                            <td></td>
                        @endif

                        <td>{{ $d->rekomendasi->rekomendasi }}</td>
                        <td>{{ 'Rp ' . number_format($d->rekomendasi->nilai_rekomendasi, 0, ',', '.') }}</td>
                        <td>{{ $d->uraian }}</td>
                        <td>{{ 'Rp ' . number_format($d->nilai_selesai, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->nilai_dalam_proses, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->nilai_sisa, 0, ',', '.') }}</td>
                        <td>{{ $d->status_tl }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="end">Jumlah :</td>
                    <td></td>
                    <td>{{ 'Rp ' . number_format($total->total_nilai_temuan, 0, ',', '.') }}</td>
                    <td></td>
                    <td>{{ 'Rp ' . number_format($total->total_nilai_rekomen, 0, ',', '.') }}</td>
                    <td></td>
                    <td>{{ 'Rp ' . number_format($total->total_nilai_selesai, 0, ',', '.') }}</td>
                    <td>{{ 'Rp ' . number_format($total->total_nilai_dalam_proses, 0, ',', '.') }}</td>
                    <td>{{ 'Rp ' . number_format($total->total_nilai_sisa, 0, ',', '.') }}</td>
                    <td></td>

                </tr>
            </tfoot>
        </table>

        <div class="inpektur">
            @if ($inspektur != null)
                <label>Muara Bulian, {{ date('d F Y') }}</label><br>
                <label>{{ $wilayah->name }}</label><br><br><br><br>
                <label>{{ $inspektur->name }}</label><br>
                <label>{{ $inspektur->pangkat_gol }}</label><br>
                <label>{{ 'NIP :' . $inspektur->nip }}</label>
            @else
                <div class=""></div>
            @endif
        </div>
    </div>
</body>

</html>
