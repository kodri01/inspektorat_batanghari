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
        <label class="text-bold">REKAP LHP BPK RI PERWAKILAN PROVINSI JAMBI</label>
    </center> --}}

    <div class="container">
        <div class="logo-container">
            <img src="{{ public_path('assets/logo.png') }}" class="logo" alt="Logo">
            <div class="label text-bold">
                REKAP LHP BPK RI PERWAKILAN PROVINSI JAMBI
            </div>
        </div>
    </div>

    <div class="">
        <table class="">
            <thead>
                <tr>
                    <th class="up">#</th>
                    <th>Tahun & Jenis Pemeriksaan</th>
                    <th class="up">No LHP</th>
                    <th class="up">Tgl LHP</th>
                    @role('superadmin')
                        <th class="up">Nama Obrik</th>
                    @endrole
                    <th>Temuan (Kerugian Negara)</th>
                    <th>Temuan (Kerugian Daerah)</th>
                    <th>Temuan (Lain-lainnya)</th>
                    <th>Tindak Lanjut (Kerugian Negara)</th>
                    <th>Tindak Lanjut (Kerugian Daerah)</th>
                    <th>Tindak Lanjut (Lain-lainnya)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->lhp->tahun }} - {{ $d->lhp->judul }}</td>
                        <td>{{ $d->lhp->no_lhp }}</td>
                        <td>{{ $d->lhp->tgl_lhp }}</td>
                        @role('superadmin')
                            <td>{{ $d->name }}</td>
                        @endrole
                        <td>{{ 'Rp ' . number_format($d->total_temuan_negara, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->total_temuan_daerah, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->total_temuan_lainnya, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->total_setor_negara, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->total_setor_daerah, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($d->total_setor_lainnya, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @role('superadmin')
            <div style="margin-left: 45rem">
                <label>Muara Bulian, {{ date('d F Y') }}</label><br>
                <label>INSPEKTUR</label><br><br><br><br>
                <label>Muhammad Rokim, SE.SE,CGCAE</label><br>
                <label>Pembina TK.1</label><br>
                <label>NIP : 197104091995031003</label>
            </div>
        @endrole
    </div>
</body>

</html>
