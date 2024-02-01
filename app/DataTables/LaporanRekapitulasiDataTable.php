<?php

namespace App\DataTables;

use App\Exports\LaporanRekapitulasi as ExportsLaporanRekapitulasi;
use App\Models\Temuans;
use App\Models\TindakLanjut;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LaporanRekapitulasiDataTable extends DataTable
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
            ->addColumn('selesai', function ($data) {
                $rekom = $data->jml_rekomen;
                $selesai = $data->jml_selesai;
                $persen = $selesai / $rekom * 100;
                return $selesai . ' => ' . $persen . '%';
            })

            ->addColumn('dalam', function ($data) {
                $rekom = $data->jml_rekomen;
                $dalam = $data->jml_dalam;
                $persen = $dalam / $rekom * 100;
                return $dalam . ' => ' . $persen . '%';
            })
            // ->addColumn('belum', function ($data) {
            //     $rekom = $data->jml_rekomen;
            //     $belum = $data->jml_belum;
            //     $persen = $belum / $rekom * 100;
            //     return $data . ' => ' . $persen . '%';
            // })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Temuans $model): QueryBuilder
    {
        return $model->with(['obrik', 'tindakan', 'lhp', 'rekomendasi'])
            ->select(
                'temuans.lhp_id',
                'temuans.id',
                DB::raw('COUNT(temuans.id) as jml_temuan'),
                DB::raw('SUM(temuans.nilai_temuan) as total_temuan'),
                DB::raw('(SELECT COUNT(*) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id) as jml_rekomen'),
                DB::raw('(SELECT SUM(nilai_rekomendasi) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id) as total_rekomendasi'),
                DB::raw('(SELECT COUNT(*) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_selesai = (SELECT MAX(nilai_rekomendasi) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id)) as jml_selesai'),
                DB::raw('(SELECT COUNT(*) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_dalam_proses < (SELECT MAX(nilai_rekomendasi) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id)) as jml_dalam'),
                DB::raw('(SELECT SUM(nilai_setor) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id) as total_setor'),
                DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id) as total_dalam'),
                DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id) as total_sisa'),
            )
            ->leftjoin('lhps', 'temuans.lhp_id', '=', 'lhps.id')
            ->groupBy('temuans.lhp_id', 'temuans.id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('laporanrekapitulasi-table')
            ->columns($this->getColumns())
            ->responsive(true)
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([
                Button::make('excel')->action('window.location.href = "' . route("excel_rekapitulasi") . '"')->addClass('text-success text-bold rounded'),
                // Button::make('pdf'),
                Button::make('pdf')->action('window.location.href = "' . route("pdf_rekapitulasi") . '"')->addClass('text-danger text-bold rounded'),
                Button::make('print')->addClass('text-bold rounded'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('number')
                ->title('#')
                ->orderable(false)
                ->searchable(false)
                ->width(30)
                ->addClass('text-center dataTable-font'),
            Column::make('lhp.tahun')->title('Tahun')->responsivePriority(1)->addClass('dataTable-font'),
            Column::make('jml_temuan')->title('Jlh Temuan')->addClass('dataTable-font'),
            Column::make('total_temuan')
                ->title('Nilai Temuan')
                ->addClass('dataTable-font')
                ->responsivePriority(2)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('jml_rekomen')->title('Jlh Rekom')->addClass('dataTable-font'),
            Column::make('selesai')->title('S => %')->addClass('dataTable-font'),
            Column::make('dalam')->title('D => %')->addClass('dataTable-font'),
            // Column::make('belum')->title('B => %')->addClass('dataTable-font'),
            Column::make('total_rekomendasi')
                ->title('Nilai Rekomendasi')
                ->addClass('dataTable-font')
                ->responsivePriority(2)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('total_setor')
                ->title('Disetor')
                ->addClass('dataTable-font')
                ->responsivePriority(2)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('total_dalam')
                ->title('Dalam Proses')
                ->addClass('dataTable-font')
                ->responsivePriority(2)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('total_sisa')
                ->title('Sisa')
                ->addClass('dataTable-font')
                ->responsivePriority(2)
                ->renderJs('number', '.', ',', '', ' Rp. '),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LaporanRekapitulasi_' . date('YmdHis');
    }

    public function excelCustom()
    {

        $tahun = now()->year;

        $data = [
            [
                'REKAPITULASI PERKEMBANGAN TINDAK LANJUT HASIL PEMERIKSAAN'
            ],
            [
                'INSPEKTORAT DAERAH KABUPATEN BATANG HARI'
            ],
            [
                ''
            ],
            [
                '#',
                'Tahun',
                'Jml Temuan',
                'Nilai Temuan',
                'Jml Rekom',
                'S => %',
                'D => %',
                'Nilai Rekomendasi',
                'Disetor',
                'Dalam Proses',
                'Sisa',
            ],


        ];

        $laporanRekapitualasi =
            Temuans::with(['obrik', 'tindakan', 'lhp', 'rekomendasi'])
            ->select(
                'temuans.lhp_id',
                'temuans.id',
                DB::raw('COUNT(temuans.id) as jml_temuan'),
                DB::raw('SUM(temuans.nilai_temuan) as total_temuan'),
                DB::raw('(SELECT COUNT(*) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id) as jml_rekomen'),
                DB::raw('(SELECT SUM(nilai_rekomendasi) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id) as total_rekomendasi'),
                DB::raw('(SELECT COUNT(*) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_selesai = (SELECT MAX(nilai_rekomendasi) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id)) as jml_selesai'),
                DB::raw('(SELECT COUNT(*) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_dalam_proses < (SELECT MAX(nilai_rekomendasi) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id)) as jml_dalam'),
                DB::raw('(SELECT SUM(nilai_setor) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id) as total_setor'),
                DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id) as total_dalam'),
                DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts WHERE tindak_lanjuts.temuan_id = temuans.id) as total_sisa'),
            )
            ->leftjoin('lhps', 'temuans.lhp_id', '=', 'lhps.id')
            ->groupBy('temuans.lhp_id', 'temuans.id')
            ->get();

        foreach ($laporanRekapitualasi as $index => $laporan) {
            $rpNilaiTemuan = 'Rp ' . number_format($laporan->total_temuan, 0, ',', '.');
            $rpNilairekomen = 'Rp ' . number_format($laporan->total_rekomendasi, 0, ',', '.');
            $rpNilaisetor = 'Rp ' . number_format($laporan->total_setor, 0, ',', '.');
            $rpNilaidalamProses = 'Rp ' . number_format($laporan->total_dalam, 0, ',', '.');
            $rpNilaiSisa = 'Rp ' . number_format($laporan->total_sisa, 0, ',', '.');

            $rekom = $laporan->jml_rekomen;
            $jml_selesai = $laporan->jml_selesai;
            $persenSelesai = $jml_selesai / $rekom * 100;

            $jml_dalam = $laporan->jml_selesai;
            $persenDalam = $jml_dalam / $rekom * 100;

            $selesai = $jml_selesai . ' => ' . $persenSelesai . '%';
            $dalam = $jml_dalam . ' => ' . $persenDalam . '%';

            $data[] = [
                $index + 1,
                $laporan->lhp->tahun,
                $laporan->jml_temuan,
                $rpNilaiTemuan,
                $laporan->jml_rekomen,
                $selesai,
                $dalam,
                $rpNilairekomen,
                $rpNilaisetor,
                $rpNilaidalamProses,
                $rpNilaiSisa,

            ];
        }

        // $total =
        //     TindakLanjut::select(
        //         DB::raw('(SELECT SUM(nilai_temuan) FROM temuans ) AS total_nilai_temuan'),
        //         DB::raw('(SELECT SUM(CASE WHEN tindak_lanjuts.rekomendasi_id = rekomendasies.id THEN rekomendasies.nilai_rekomendasi ELSE 0 END)) as total_nilai_rekomen'),
        //         DB::raw('(SELECT SUM(nilai_selesai) FROM tindak_lanjuts ) AS total_nilai_selesai'),
        //         DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts ) AS total_nilai_dalam_proses'),
        //         DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts ) AS total_nilai_sisa'),
        //     )
        //     ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
        //     ->join('rekomendasies', 'tindak_lanjuts.rekomendasi_id', '=', 'rekomendasies.id')
        //     ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
        //     ->whereNull('tindak_lanjuts.deleted_at')
        //     ->first();

        // if ($laporan->wilayah_id != auth()->user()->wilayah_id) {
        //     $data[] = [
        //         ''
        //     ];
        // } else {
        //     $data[] = [
        //         [
        //             '',
        //             'Jumlah : ',
        //             '',
        //             'Rp ' . number_format($total->total_nilai_temuan, 0, ',', '.'),
        //             '',
        //             'Rp ' . number_format($total->total_nilai_rekomen, 0, ',', '.'),
        //             '',
        //             'Rp ' . number_format($total->total_nilai_selesai, 0, ',', '.'),
        //             'Rp ' . number_format($total->total_nilai_dalam_proses, 0, ',', '.'),
        //             'Rp ' . number_format($total->total_nilai_sisa, 0, ',', '.'),
        //         ],
        //     ];
        // }

        return Excel::download(new ExportsLaporanRekapitulasi($data), 'laporan_rekapitulasi_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function pdfCustom()
    {
        $tahun = now()->year;
        $wilayah = Wilayah::select('name')->where('id', auth()->user()->wilayah_id)->first();

        $data =
            TindakLanjut::with(['obrik', 'temuan', 'lhp', 'rekomendasi'])
            ->select(
                'tindak_lanjuts.*',
            )
            ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
            ->whereNull('tindak_lanjuts.deleted_at')
            ->get();

        $total =
            TindakLanjut::select(
                DB::raw('(SELECT SUM(nilai_temuan) FROM temuans ) AS total_nilai_temuan'),
                DB::raw('(SELECT SUM(CASE WHEN tindak_lanjuts.rekomendasi_id = rekomendasies.id THEN rekomendasies.nilai_rekomendasi ELSE 0 END)) as total_nilai_rekomen'),
                DB::raw('(SELECT SUM(nilai_selesai) FROM tindak_lanjuts ) AS total_nilai_selesai'),
                DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts ) AS total_nilai_dalam_proses'),
                DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts ) AS total_nilai_sisa'),
            )
            ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
            ->join('rekomendasies', 'tindak_lanjuts.rekomendasi_id', '=', 'rekomendasies.id')
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

        $pdf = Pdf::loadView('pages.pdf.laporan_php', compact('data', 'total', 'tahun', 'inspektur', 'wilayah'))->setPaper('legal', 'landscape');
        return $pdf->download('laporan_php_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
