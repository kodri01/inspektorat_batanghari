<?php

namespace App\DataTables;

use App\Exports\LaporanRincian as ExportsLaporanRincian;
use App\Models\LaporanRincian;
use App\Models\TindakLanjut;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LaporanRincianDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('number', function () {
                static $count = 0;
                return ++$count;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TindakLanjut $model): QueryBuilder
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();

        // Pastikan $modelrole tidak null sebelum menggunakan first()
        if ($modelrole) {
            $role = Role::where('id', $modelrole->role_id)->first();

            // Pastikan $role tidak null sebelum menggunakan first()
            if ($role && $role->name == 'superadmin') {
                return $model->with(['obrik', 'temuan', 'rekomendasi'])->select('tindak_lanjuts.*');
            } else {
                return $model->with(['obrik', 'temuan', 'rekomendasi'])->select('tindak_lanjuts.*')->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id);
            }
        }

        // Jika $modelrole atau $role null, maka berikan nilai default atau kembalikan query kosong
        return $model->with(['obrik', 'temuan', 'rekomendasi'])->where('tindak_lanjuts.id', null);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {

        return $this->builder()
            ->setTableId('laporanrincian-table')
            ->columns($this->getColumns())
            ->responsive(true)
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([
                Button::make('excel')->action('window.location.href = "' . route("excel_rincian") . '"')->addClass('text-success text-bold rounded'),
                // Button::make('pdf'),
                Button::make('pdf')->action('window.location.href = "' . route("pdf_rincian") . '"')->addClass('text-danger text-bold rounded'),
                Button::make('print')->addClass('text-bold rounded'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();

        // Pastikan $modelrole tidak null sebelum menggunakan first()
        if ($modelrole) {
            $role = Role::where('id', $modelrole->role_id)->first();
            // Pastikan $role tidak null sebelum menggunakan first()
            if ($role && $role->name == 'superadmin') {
                return [
                    Column::computed('number')
                        ->title('#')
                        ->orderable(false)
                        ->searchable(false)
                        ->width(30)
                        ->addClass('text-center dataTable-font'),
                    Column::make('obrik.jenis')->title('Kategori')->responsivePriority(1)->addClass('dataTable-font'),
                    Column::make('obrik.name')->title('Nama Obrik')->responsivePriority(1)->addClass('dataTable-font'),
                    Column::make('temuan.nilai_temuan')
                        ->title('Nilai Temuan')
                        ->addClass('dataTable-font')
                        ->responsivePriority(2)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('rekomendasi.nilai_rekomendasi')
                        ->title('Nilai Rekomendasi')
                        ->addClass('dataTable-font')
                        ->responsivePriority(4)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('nilai_selesai')
                        ->title('Selesai')
                        ->addClass('dataTable-font')
                        ->responsivePriority(5)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('nilai_dalam_proses')
                        ->title('Dalam Proses')
                        ->addClass('dataTable-font')
                        ->responsivePriority(6)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('nilai_sisa')
                        ->title('Belum Ditindaklanjuti')
                        ->addClass('dataTable-font')
                        ->responsivePriority(7)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('nilai_setor')
                        ->title('Nilai Setor')
                        ->addClass('dataTable-font')
                        ->responsivePriority(7)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                ];
            } else {
                return [
                    Column::computed('number')
                        ->title('#')
                        ->orderable(false)
                        ->searchable(false)
                        ->width(30)
                        ->addClass('text-center dataTable-font'),
                    Column::make('obrik.jenis')->title('Kategori')->responsivePriority(1)->addClass('dataTable-font'),
                    Column::make('obrik.name')->title('Nama Obrik')->responsivePriority(1)->addClass('dataTable-font'),
                    Column::make('temuan.nilai_temuan')
                        ->title('Nilai Temuan')
                        ->addClass('dataTable-font')
                        ->responsivePriority(2)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('rekomendasi.nilai_rekomendasi')
                        ->title('Nilai Rekomendasi')
                        ->addClass('dataTable-font')
                        ->responsivePriority(4)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('nilai_selesai')
                        ->title('Selesai')
                        ->addClass('dataTable-font')
                        ->responsivePriority(5)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('nilai_dalam_proses')
                        ->title('Dalam Proses')
                        ->addClass('dataTable-font')
                        ->responsivePriority(6)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('nilai_sisa')
                        ->title('Belum Ditindaklanjuti')
                        ->addClass('dataTable-font')
                        ->responsivePriority(7)
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                ];
            }
        }
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LaporanRincian_' . date('YmdHis');
    }

    public function excelCustom()
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();

        // Pastikan $modelrole tidak null sebelum menggunakan first()
        if ($modelrole) {
            $role = Role::where('id', $modelrole->role_id)->first();
            // Pastikan $role tidak null sebelum menggunakan first()
            if ($role && $role->name == 'superadmin') {
                $tahun = now()->year;

                $data = [
                    [
                        'REKAPITULASI HASIL PEMANTAUAN TINDAK LANJUT HASIL PEMERIKSAAN INSPEKTORAT'
                    ],
                    [
                        'TAHUN ' . $tahun
                    ],
                    [
                        ''
                    ],
                    [
                        '#',
                        'Kategori',
                        'Nama Obrik',
                        'Nilai Temuan',
                        'Nilai Rekomendasi',
                        'Nilai Selesai',
                        'Dalam Proses',
                        'Belum Ditindak',
                        'Nilai Setor',
                    ],
                ];

                $laporanRincian =
                    DB::table('tindak_lanjuts')
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'temuans.obrik_id', '=', 'obriks.id')
                    ->select(
                        'tindak_lanjuts.*',
                        'temuans.nilai_temuan',
                        'temuans.nilai_rekomendasi',
                        'obriks.name',
                        'obriks.jenis',
                    )
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->get();

                $total = DB::table('tindak_lanjuts')
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'temuans.obrik_id', '=', 'obriks.id')
                    ->select(
                        DB::raw('(SELECT SUM(nilai_temuan) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_temuan'),
                        DB::raw('(SELECT SUM(rekomendasi) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_rekomen'),
                        DB::raw('(SELECT SUM(nilai_selesai) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_selesai'),
                        DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_dalam_proses'),
                        DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_sisa'),
                        DB::raw('(SELECT SUM(nilai_setor) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_setor')
                    )
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->first();


                foreach ($laporanRincian as $index => $laporan) {
                    $rpNilaiTemuan = 'Rp ' . number_format($laporan->nilai_temuan, 0, ',', '.');
                    $rpNilairekomen = 'Rp ' . number_format($laporan->nilai_rekomendasi, 0, ',', '.');
                    $rpNilaiselesai = 'Rp ' . number_format($laporan->nilai_selesai, 0, ',', '.');
                    $rpNilaidalamProses = 'Rp ' . number_format($laporan->nilai_dalam_proses, 0, ',', '.');
                    $rpNilaiSisa = 'Rp ' . number_format($laporan->nilai_sisa, 0, ',', '.');
                    $rpNilaiSetor = 'Rp ' . number_format($laporan->nilai_setor, 0, ',', '.');
                    $data[] = [
                        $index + 1,
                        $laporan->jenis,
                        $laporan->name,
                        $rpNilaiTemuan,
                        $rpNilairekomen,
                        $rpNilaiselesai,
                        $rpNilaidalamProses,
                        $rpNilaiSisa,
                        $rpNilaiSetor,
                    ];
                }

                $data[] = [
                    [
                        '',
                        'Jumlah : ',
                        '',
                        'Rp ' . number_format($total->total_nilai_temuan, 0, ',', '.'),
                        'Rp ' . number_format($total->total_nilai_rekomen, 0, ',', '.'),
                        'Rp ' . number_format($total->total_nilai_selesai, 0, ',', '.'),
                        'Rp ' . number_format($total->total_nilai_dalam_proses, 0, ',', '.'),
                        'Rp ' . number_format($total->total_nilai_sisa, 0, ',', '.'),
                        'Rp ' . number_format($total->total_nilai_setor, 0, ',', '.'),
                    ],
                ];
                return Excel::download(new ExportsLaporanRincian($data), 'laporanRincian_' . date('dmY') . '.xlsx');
            } else {

                $tahun = now()->year;
                $wilayah = Wilayah::select('name')->where('id', auth()->user()->wilayah_id)->first();

                $data = [
                    [
                        'REKAPITULASI HASIL PEMANTAUAN TINDAK LANJUT HASIL PEMERIKSAAN INSPEKTORAT'
                    ],

                    [
                        'PADA ' . $wilayah->name
                    ],
                    [
                        'TAHUN ' . $tahun
                    ],
                    [
                        ''
                    ],
                    [
                        '#',
                        'Kategori',
                        'Nama Obrik',
                        'Nilai Temuan',
                        'Nilai Rekomendasi',
                        'Nilai Selesai',
                        'Dalam Proses',
                        'Belum Ditindak',
                    ],
                ];

                $laporanRincian =
                    DB::table('tindak_lanjuts')
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'temuans.obrik_id', '=', 'obriks.id')
                    ->select(
                        'tindak_lanjuts.*',
                        'temuans.nilai_temuan',
                        'temuans.nilai_rekomendasi',
                        'obriks.name',
                        'obriks.jenis',
                    )
                    ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->get();

                $total =
                    DB::table('tindak_lanjuts')
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'temuans.obrik_id', '=', 'obriks.id')
                    ->select(
                        DB::raw('(SELECT SUM(nilai_temuan) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_temuan'),
                        DB::raw('(SELECT SUM(rekomendasi) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_rekomen'),
                        DB::raw('(SELECT SUM(nilai_selesai) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_selesai'),
                        DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_dalam_proses'),
                        DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_sisa'),
                    )
                    ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->first();

                foreach ($laporanRincian as $index => $laporan) {
                    $rpNilaiTemuan = 'Rp ' . number_format($laporan->nilai_temuan, 0, ',', '.');
                    $rpNilairekomen = 'Rp ' . number_format($laporan->nilai_rekomendasi, 0, ',', '.');
                    $rpNilaiselesai = 'Rp ' . number_format($laporan->nilai_selesai, 0, ',', '.');
                    $rpNilaidalamProses = 'Rp ' . number_format($laporan->nilai_dalam_proses, 0, ',', '.');
                    $rpNilaiSisa = 'Rp ' . number_format($laporan->nilai_sisa, 0, ',', '.');
                    $data[] = [
                        $index + 1,
                        $laporan->jenis,
                        $laporan->name,
                        $rpNilaiTemuan,
                        $rpNilairekomen,
                        $rpNilaiselesai,
                        $rpNilaidalamProses,
                        $rpNilaiSisa,
                    ];
                }

                if ($laporan->wilayah_id != auth()->user()->wilayah_id) {
                    $data[] = [
                        ''
                    ];
                } else {
                    $data[] = [
                        [
                            '',
                            'Jumlah : ',
                            '',
                            'Rp ' . number_format($total->total_nilai_temuan, 0, ',', '.'),
                            'Rp ' . number_format($total->total_nilai_rekomen, 0, ',', '.'),
                            'Rp ' . number_format($total->total_nilai_selesai, 0, ',', '.'),
                            'Rp ' . number_format($total->total_nilai_dalam_proses, 0, ',', '.'),
                            'Rp ' . number_format($total->total_nilai_sisa, 0, ',', '.'),
                        ],
                    ];
                }

                return Excel::download(new ExportsLaporanRincian($data), 'laporanRincian_' . date('dmY') . '.xlsx');
            }
        }
    }

    public function pdfCustom()
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();

        // Pastikan $modelrole tidak null sebelum menggunakan first()
        if ($modelrole) {
            $role = Role::where('id', $modelrole->role_id)->first();
            // Pastikan $role tidak null sebelum menggunakan first()
            if ($role && $role->name == 'superadmin') {
                $tahun = now()->year;

                $data =
                    DB::table('tindak_lanjuts')
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'temuans.obrik_id', '=', 'obriks.id')
                    ->select(
                        'tindak_lanjuts.*',
                        'temuans.nilai_temuan',
                        'temuans.nilai_rekomendasi',
                        'obriks.name',
                        'obriks.jenis',
                    )
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->get();

                $total = DB::table('tindak_lanjuts')
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'temuans.obrik_id', '=', 'obriks.id')
                    ->select(
                        DB::raw('(SELECT SUM(nilai_temuan) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_temuan'),
                        DB::raw('(SELECT SUM(rekomendasi) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_rekomen'),
                        DB::raw('(SELECT SUM(nilai_selesai) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_selesai'),
                        DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_dalam_proses'),
                        DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_sisa'),
                        DB::raw('(SELECT SUM(nilai_setor) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_setor')
                    )
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->first();

                $pdf = Pdf::loadView('pages.pdf.laporan_rincian', compact('data', 'total', 'tahun'))
                    ->setPaper('A4', 'landscape');
                return $pdf->download('laporan_rincian_' . date('Y-m-d_H-i-s') . '.pdf');
            } else {

                $tahun = now()->year;
                $wilayah = Wilayah::select('name')->where('id', auth()->user()->wilayah_id)->first();

                $data =
                    DB::table('tindak_lanjuts')
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'temuans.obrik_id', '=', 'obriks.id')
                    ->select(
                        'tindak_lanjuts.*',
                        'temuans.nilai_temuan',
                        'temuans.nilai_rekomendasi',
                        'obriks.name',
                        'obriks.jenis',
                    )
                    ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->get();

                $total =
                    DB::table('tindak_lanjuts')
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'temuans.obrik_id', '=', 'obriks.id')
                    ->select(
                        DB::raw('(SELECT SUM(nilai_temuan) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_temuan'),
                        DB::raw('(SELECT SUM(rekomendasi) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_rekomen'),
                        DB::raw('(SELECT SUM(nilai_selesai) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_selesai'),
                        DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_dalam_proses'),
                        DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts WHERE deleted_at IS NULL) AS total_nilai_sisa'),
                    )
                    ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->first();

                $inspektur =
                    DB::table('inspekturs')
                    ->join('wilayahs', 'inspekturs.wilayah_id', '=', 'wilayahs.id')
                    ->where('inspekturs.wilayah_id', auth()->user()->wilayah_id)
                    ->select(
                        'wilayahs.name as wilayah',
                        'inspekturs.name',
                        'inspekturs.nip',
                        'inspekturs.pangkat_gol',
                    )
                    ->whereNull('inspekturs.deleted_at')
                    ->first();

                $pdf = Pdf::loadView('pages.pdf.laporan_rincian', compact('data', 'total', 'tahun', 'wilayah', 'inspektur'));
                return $pdf->download('laporan_rincian_' . date('Y-m-d_H-i-s') . '.pdf');
            }
        }
    }
}
