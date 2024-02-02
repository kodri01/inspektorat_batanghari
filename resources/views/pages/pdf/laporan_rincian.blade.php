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

        th .up {
            vertical-align: middle;
        }

        td .end {
            text-align: end;
        }

        div .inpektur {
            margin-left: 30rem;
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
        <label class="text-bold">REKAPITULASI HASIL PEMANTAUAN TINDAK LANJUT HASIL PEMERIKSAAN INSPEKTORAT <br>
            @role('Irban')
                PADA {{ $wilayah->name }} <br>
            @endrole
            TAHUN {{ $tahun }}
        </label>
    </center>

    <div>
        <table>
            <thead>
                <tr>
                    <th class="up">#</th>
                    <th class="up">Kategori</th>
                    <th class="up">Nama Obrik</th>
                    <th>Nilai Temuan</th>
                    <th colspan="2">Nilai Rekomendasi</th>
                    <th colspan="2">Nilai Selesai</th>
                    <th colspan="2">Dalam Proses</th>
                    <th colspan="2">Belum Ditindak</th>
                    @role('superadmin')
                        <th colspan="2">Nilai Setor</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @php $displayedJenis = []; @endphp
                @foreach ($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @if (!in_array($d->obrik->jenis, $displayedJenis))
                            <td>{{ $d->obrik->jenis }}</td>
                            @php $displayedJenis[] = $d->obrik->jenis; @endphp
                        @else
                            <td></td>
                        @endif
                        @if (!in_array($d->obrik->name, $displayedJenis))
                            <td>{{ $d->obrik->name }}</td>
                            @php $displayedJenis[] = $d->obrik->name; @endphp
                        @else
                            <td></td>
                        @endif
                        @if (!in_array($d->temuan->nilai_temuan, $displayedJenis))
                            <td>{{ 'Rp ' . number_format($d->temuan->nilai_temuan, 0, ',', '.') }}</td>
                            @php $displayedJenis[] = $d->temuan->nilai_temuan; @endphp
                        @else
                            <td></td>
                        @endif
                        <td colspan="2">{{ 'Rp ' . number_format($d->rekomendasi->nilai_rekomendasi, 0, ',', '.') }}
                        </td>
                        <td colspan="2">{{ 'Rp ' . number_format($d->nilai_selesai, 0, ',', '.') }}</td>
                        <td colspan="2">{{ 'Rp ' . number_format($d->nilai_dalam_proses, 0, ',', '.') }}</td>
                        <td colspan="2">{{ 'Rp ' . number_format($d->nilai_sisa, 0, ',', '.') }}</td>
                        @role('superadmin')
                            <td colspan="2">{{ 'Rp ' . number_format($d->nilai_setor, 0, ',', '.') }}</td>
                        @endrole
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="end">Jumlah :</td>

                    <td>{{ 'Rp ' . number_format($total->total_nilai_temuan, 0, ',', '.') }}</td>
                    <td colspan="2">{{ 'Rp ' . number_format($total->total_nilai_rekomen, 0, ',', '.') }}</td>
                    <td colspan="2">{{ 'Rp ' . number_format($total->total_nilai_selesai, 0, ',', '.') }}</td>
                    <td colspan="2">{{ 'Rp ' . number_format($total->total_nilai_dalam_proses, 0, ',', '.') }}</td>
                    <td colspan="2">{{ 'Rp ' . number_format($total->total_nilai_sisa, 0, ',', '.') }}</td>
                    @role('superadmin')
                        <td colspan="2">{{ 'Rp ' . number_format($total->total_setor, 0, ',', '.') }}</td>
                    @endrole
                </tr>
            </tfoot>
        </table>

        @role('Irban')
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
        @endrole
    </div>
</body>

</html>
