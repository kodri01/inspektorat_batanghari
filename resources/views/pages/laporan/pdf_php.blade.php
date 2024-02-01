<h1>DAFTAR POKOK-POKOK HASIL PEMERIKSAAN</h1>
<h2>APARAT PENGAWASAN FUNGSIONAL INSPEKTORAT KABUPATEN BATANG HARI</h2>
<h2>TAHUN PEMERIKSAAN 2022</h2>

<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Nomor LHP</th>
            <th>Judul Temuan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->no_lhp }}</td>
                <td>{{ $item->ringkasan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
