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
    </style>

</head>

<body>
    <center>
        <label class="text-bold">DAFTAR POKOK-POKOK HASIL PEMERIKSAAN</label><br>
        <label class="text-bold"> APARAT PENGAWASAN FUNGSIONAL INSPEKTORAT KABUPATEN BATANG HARI </label><br>
        <label class="text-bold"> TAHUN PEMERIKSAAN {{ $tahun }} </label>
    </center>

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
                @foreach ($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->obrik->jenis }}</td>
                        <td>{{ $d->lhp->no_lhp }}</td>
                        <td>{{ $d->temuan->ringkasan }}</td>
                        <td>{{ 'Rp ' . number_format($d->temuan->nilai_temuan, 0, ',', '.') }}</td>
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
