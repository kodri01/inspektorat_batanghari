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
            margin-top: -50px;
        }

        .text-bold {
            font-weight: bold;
            line-height: 1.5;
            /* Spasi antar baris */
        }
    </style>

</head>

<body>
    {{-- <center>
        <label class="text-bold">REKAPITULASI PERKEMBANGAN TINDAK LANJUT HASIL PEMERIKSAAN</label><br>
        <label class="text-bold"> INSPEKTORAT DAERAH KABUPATEN BATANG HARI </label><br>
    </center> --}}
    <div class="container">
        <div class="logo-container">
            <img src="{{ public_path('assets/logo.png') }}" class="logo" alt="Logo">
            <div class="label text-bold">
                REKAPITULASI PERKEMBANGAN TINDAK LANJUT HASIL PEMERIKSAAN <br>
                INSPEKTORAT DAERAH KABUPATEN BATANG HARI
            </div>
        </div>
    </div>

    <div class="">
        <table class="">
            <thead>
                <tr>
                    <th class="up">#</th>
                    <th class="up">Tahun</th>
                    <th class="up">Jml Temuan</th>
                    <th>Nilai Temuan</th>
                    <th>Jml Rekom</th>
                    <th>S => %</th>
                    <th>D => %</th>
                    <th>B => %</th>
                    <th>Nilai Rekomendasi</th>
                    <th>Disetor</th>
                    <th>Dalam Proses</th>
                    <th>Sisa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->lhp->tahun }}</td>
                        <td>{{ $d->jml_temuan }}</td>
                        <td>{{ 'Rp ' . number_format($d->total_temuan, 0, ',', '.') }}</td>
                        <td>{{ $d->jml_rekomen }}</td>
                        <td>{{ $d->jml_selesai }} =>
                            {{ $d->jml_selesai != 0 ? number_format(($d->jml_selesai / $d->jml_rekomen) * 100, 0) . '%' : '0%' }}
                        </td>
                        <td>{{ $d->jml_dalam }} =>
                            {{ $d->jml_dalam != 0 ? number_format(($d->jml_dalam / $d->jml_rekomen) * 100, 0) . '%' : '0%' }}
                        </td>
                        <td>{{ $d->jml_belum }} =>
                            {{ $d->jml_belum != 0 ? number_format(($d->jml_belum / $d->jml_rekomen) * 100, 0) . '%' : '0%' }}
                        </td>
                        <td>{{ 'Rp ' . number_format($d->total_rekomendasi, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->total_setor, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->total_dalam, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->total_sisa, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            {{-- <tfoot>
                <tr>
                    <td colspan="2" class="end">Jumlah :</td>
                    <td>{{ $total->jml_temuan }}</td>
                    <td>{{ 'Rp ' . number_format($total->total_temuan, 0, ',', '.') }}</td>
                    <td>{{ $total->jml_rekomen }}</td>
                    <td>{{ $total->jml_selesai }} =>
                        {{ $total->jml_selesai != 0 ? number_format(($total->jml_selesai / $total->jml_rekomen) * 100, 0) . '%' : '0%' }}
                    </td>
                    <td>{{ $total->jml_dalam }} =>
                        {{ $total->jml_dalam != 0 ? number_format(($total->jml_dalam / $total->jml_rekomen) * 100, 0) . '%' : '0%' }}
                    </td>
                    <td>{{ $total->jml_belum }} =>
                        {{ $total->jml_belum != 0 ? number_format(($total->jml_belum / $total->jml_rekomen) * 100, 0) . '%' : '0%' }}
                    </td>
                    <td>{{ 'Rp ' . number_format($total->total_rekomendasi, 0, ',', '.') }}</td>
                    <td>{{ 'Rp ' . number_format($total->total_setor, 0, ',', '.') }}</td>
                    <td>{{ 'Rp ' . number_format($total->total_dalam, 0, ',', '.') }}</td>
                    <td>{{ 'Rp ' . number_format($total->total_sisa, 0, ',', '.') }}</td>
                </tr>
            </tfoot> --}}
        </table>

        @role('superadmin')
            <div class="inpektur" style="margin-left: 11rem;">
                <label>Muara Bulian, {{ date('d F Y') }}</label><br>
                <label>INSPEKTUR</label><br><br><br><br>
                <label>Muhammad Rokim, SE.SE,CGCAE</label><br>
                <label>Pembina TK.1</label><br>
                <label>NIP : 197104091995031003</label>
            </div>
        @endrole
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
