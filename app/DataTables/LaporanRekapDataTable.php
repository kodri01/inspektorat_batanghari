<?php

namespace App\DataTables;

use App\Exports\LaporanRekap as ExportsLaporanRekap;
use App\Exports\LaporanRincian;
use App\Models\LaporanRekap;
use App\Models\Lhp;
use App\Models\Temuans;
use App\Models\TindakLanjut;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Cache\RateLimiting\Limit;
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

class LaporanRekapDataTable extends DataTable
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
            ->addColumn('jenis_periksa', function ($data) {
                $judul = $data->lhp->judul;
                $tahun = $data->lhp->tahun;
                return $tahun . ' - ' . $judul;
            })
            ->addColumn('kerugian_negara', function ($data) {
                return $data->total_temuan_negara;
            })
            ->addColumn('daerah', function ($data) {
                return $data->total_temuan_daerah;
            })
            ->addColumn('lain-lain', function ($data) {
                return $data->total_temuan_lainnya;
            })

            ->addColumn('tin_kerugian_negara', function ($data) {
                return $data->total_setor_negara;
            })
            ->addColumn('tin_kerugian_daerah', function ($data) {
                return $data->total_setor_daerah;
            })
            ->addColumn('tin_lain-lain', function ($data) {
                return $data->total_setor_lainnya;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TindakLanjut $model): QueryBuilder
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
        $tahun = DB::table('lhps')->orderBy('tahun', 'asc')->pluck('tahun')->toArray();

        // Pastikan $modelrole tidak null sebelum menggunakan first()
        if ($modelrole) {
            $role = Role::where('id', $modelrole->role_id)->first();

            // Pastikan $role tidak null sebelum menggunakan first()
            if ($role && $role->name == 'superadmin') {
                return $model->with(['obrik', 'temuan', 'lhp'])
                    ->select(
                        'temuans.id',
                        'temuans.lhp_id',
                        'obriks.name',
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_lainnya'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_lainnya'),
                    )
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'tindak_lanjuts.obrik_id', '=', 'obriks.id')
                    ->groupBy('temuans.id', 'temuans.lhp_id', 'obriks.name')
                    ->whereNull('tindak_lanjuts.deleted_at');
            } else {
                return $model->with(['obrik', 'temuan', 'lhp'])
                    ->select(
                        'temuans.id',
                        'temuans.lhp_id',
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_lainnya'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_lainnya'),
                    )
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->groupBy('temuans.id', 'temuans.lhp_id');
            }
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('laporanrekap-table')
            ->columns($this->getColumns())
            ->responsive(true)
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([
                Button::make('excel')->action('window.location.href = "' . route("excel_rekap") . '"')->addClass('text-success text-bold rounded'),
                // Button::make('pdf'),
                Button::make('pdf')->action('window.location.href = "' . route("pdf_rekap") . '"')->addClass('text-danger text-bold rounded'),
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
                    Column::make('jenis_periksa')->title('Tahun & Jenis Pemeriksaan')->responsivePriority(1)->addClass('dataTable-font'),
                    Column::make('lhp.no_lhp')->title('No. LHP')->addClass('dataTable-font'),
                    Column::make('lhp.tgl_lhp')->title('Tgl LHP')->addClass('dataTable-font'),
                    Column::make('name')->title('Nama Obrik')->addClass('dataTable-font'),
                    Column::make('kerugian_negara')->addClass('dataTable-font')->title('Temuan (Kerugian Negara)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('daerah')->addClass('dataTable-font')->title('Temuan (Kerugian Daerah)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('lain-lain')->addClass('dataTable-font')->title('Temuan (Lain-lainnya)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('tin_kerugian_negara')->addClass('dataTable-font')->title('Tindak Lanjut (Kerugian Negara)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('tin_kerugian_daerah')->addClass('dataTable-font')->title('Tindak Lanjut (Kerugian Daerah)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('tin_lain-lain')->addClass('dataTable-font')->title('Tindak Lanjut (Lain-lainnya)')
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
                    Column::make('jenis_periksa')->title('Tahun & Jenis Pemeriksaan')->responsivePriority(1)->addClass('dataTable-font'),
                    Column::make('lhp.no_lhp')->title('No. LHP')->responsivePriority(1)->addClass('dataTable-font'),
                    Column::make('lhp.tgl_lhp')->title('Tgl LHP')->responsivePriority(1)->addClass('dataTable-font'),
                    Column::make('kerugian_negara')->addClass('dataTable-font')->title('Temuan (Kerugian Negara)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('daerah')->addClass('dataTable-font')->title('Temuan (Kerugian Daerah)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('lain-lain')->addClass('dataTable-font')->title('Temuan (Lain-lainnya)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('tin_kerugian_negara')->addClass('dataTable-font')->title('Tindak Lanjut (Kerugian Negara)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('tin_kerugian_daerah')->addClass('dataTable-font')->title('Tindak Lanjut (Kerugian Daerah)')
                        ->renderJs('number', '.', ',', '', ' Rp. '),
                    Column::make('tin_lain-lain')->addClass('dataTable-font')->title('Tindak Lanjut (Lain-lainnya)')
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
        return 'LaporanRekap_' . date('YmdHis');
    }

    public function excelCustom()
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();

        // Pastikan $modelrole tidak null sebelum menggunakan first()
        if ($modelrole) {
            $role = Role::where('id', $modelrole->role_id)->first();

            // Pastikan $role tidak null sebelum menggunakan first()
            if ($role && $role->name == 'superadmin') {

                $data = [
                    [
                        'REKAP LHP BPK RI PERWAKILAN PROVINSI JAMBI'
                    ],
                    [
                        ''
                    ],
                    [
                        '#',
                        'Tahun & Jenis Pemeriksaan',
                        'Nomor LHP',
                        'TGL LHP',
                        'Nama Obrik',
                        'Temuan (Kerugian Negara)',
                        'Temuan (Kerugian Daerah)',
                        'Temuan (Lain-lainnya)',
                        'Tindak Lanjut (Kerugian Negara)',
                        'Tindak Lanjut (Kerugian Daerah)',
                        'Tindak Lanjut (Lain-lainnya)',
                    ],
                ];

                $laporanRekap =
                    TindakLanjut::with(['obrik', 'temuan', 'lhp'])
                    ->select(
                        'temuans.id',
                        'temuans.lhp_id',
                        'obriks.name',
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_lainnya'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_lainnya'),
                    )
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'tindak_lanjuts.obrik_id', '=', 'obriks.id')
                    ->groupBy('temuans.id', 'temuans.lhp_id', 'obriks.name')
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->get();

                foreach ($laporanRekap as $index => $laporan) {

                    $rugiNegara = 'Rp ' . number_format($laporan->total_temuan_negara, 0, ',', '.');
                    $rugiDaerah = 'Rp ' . number_format($laporan->total_temuan_daerah, 0, ',', '.');
                    $lainnya = 'Rp ' . number_format($laporan->total_temuan_lainnya, 0, ',', '.');
                    $tin_rugiNegara = 'Rp ' . number_format($laporan->total_setor_negara, 0, ',', '.');
                    $tin_rugiDaerah = 'Rp ' . number_format($laporan->total_setor_daerah, 0, ',', '.');
                    $tin_lainnya = 'Rp ' . number_format($laporan->total_setor_lainnya, 0, ',', '.');

                    $thnJenis = $laporan->lhp->tahun . ' - ' . $laporan->lhp->judul;

                    $data[] = [
                        $index + 1,
                        $thnJenis,
                        $laporan->lhp->no_lhp,
                        $laporan->lhp->tgl_lhp,
                        $laporan->name,
                        $rugiNegara,
                        $rugiDaerah,
                        $lainnya,
                        $tin_rugiNegara,
                        $tin_rugiDaerah,
                        $tin_lainnya,
                    ];
                }
                return Excel::download(new ExportsLaporanRekap($data), 'laporanRekap_' . date('dmY') . '.xlsx');
            } else {
                $data = [
                    [
                        'REKAP LHP BPK RI PERWAKILAN PROVINSI JAMBI'
                    ],
                    [
                        ''
                    ],
                    [
                        '#',
                        'Tahun & Jenis Pemeriksaan',
                        'Nomor LHP',
                        'TGL LHP',
                        'Temuan (Kerugian Negara)',
                        'Temuan (Kerugian Daerah)',
                        'Temuan (Lain-lainnya)',
                        'Tindak Lanjut (Kerugian Negara)',
                        'Tindak Lanjut (Kerugian Daerah)',
                        'Tindak Lanjut (Lain-lainnya)',
                    ],
                ];

                $laporanRekap =
                    TindakLanjut::with(['obrik', 'temuan', 'lhp'])
                    ->select(
                        'temuans.id',
                        'temuans.lhp_id',
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_lainnya'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_lainnya'),
                    )
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
                    ->groupBy('temuans.id', 'temuans.lhp_id')
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->get();

                foreach ($laporanRekap as $index => $laporan) {

                    $rugiNegara = 'Rp ' . number_format($laporan->total_temuan_negara, 0, ',', '.');
                    $rugiDaerah = 'Rp ' . number_format($laporan->total_temuan_daerah, 0, ',', '.');
                    $lainnya = 'Rp ' . number_format($laporan->total_temuan_lainnya, 0, ',', '.');
                    $tin_rugiNegara = 'Rp ' . number_format($laporan->total_setor_negara, 0, ',', '.');
                    $tin_rugiDaerah = 'Rp ' . number_format($laporan->total_setor_daerah, 0, ',', '.');
                    $tin_lainnya = 'Rp ' . number_format($laporan->total_setor_lainnya, 0, ',', '.');

                    $thnJenis = $laporan->lhp->tahun . ' - ' . $laporan->lhp->judul;

                    $data[] = [
                        $index + 1,
                        $thnJenis,
                        $laporan->lhp->no_lhp,
                        $laporan->lhp->tgl_lhp,
                        $rugiNegara,
                        $rugiDaerah,
                        $lainnya,
                        $tin_rugiNegara,
                        $tin_rugiDaerah,
                        $tin_lainnya,
                    ];
                }

                return Excel::download(new ExportsLaporanRekap($data), 'laporanRekap_' . date('dmY') . '.xlsx');
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
                $data =
                    TindakLanjut::with(['obrik', 'temuan', 'lhp'])
                    ->select(
                        'temuans.id',
                        'temuans.lhp_id',
                        'obriks.name',
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_lainnya'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_lainnya'),
                    )
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->join('obriks', 'tindak_lanjuts.obrik_id', '=', 'obriks.id')
                    ->groupBy('temuans.id', 'temuans.lhp_id', 'obriks.name')
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->get();

                $pdf = Pdf::loadView('pages.pdf.laporan_rekap', compact('data'))
                    ->setPaper('A4', 'landscape');
                return $pdf->download('laporan_rekap_lhp_' . date('Y-m-d_H-i-s') . '.pdf');
            } else {
                $data =
                    TindakLanjut::with(['obrik', 'temuan', 'lhp'])
                    ->select(
                        'temuans.id',
                        'temuans.lhp_id',
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN temuans.nilai_temuan ELSE 0 END) as total_temuan_lainnya'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_negara'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Daerah" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_daerah'),
                        DB::raw('SUM(DISTINCT CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_lainnya'),
                    )
                    ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
                    ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
                    ->whereNull('tindak_lanjuts.deleted_at')
                    ->groupBy('temuans.id', 'temuans.lhp_id')
                    ->get();

                $pdf = Pdf::loadView('pages.pdf.laporan_rekap', compact('data'))
                    ->setPaper('A4', 'landscape');
                return $pdf->download('laporan_rekap_lhp_' . date('Y-m-d_H-i-s') . '.pdf');
            }
        }
    }
}