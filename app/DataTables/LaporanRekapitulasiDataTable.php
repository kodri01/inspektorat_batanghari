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
                if ($rekom != 0) {
                    $selesai = $data->jml_selesai;
                    $persen = $selesai / $rekom * 100;
                    $formatted_persen = number_format($persen, 0) . '%';
                } else {
                    $formatted_persen = 'N/A';
                }
                return $selesai . ' => ' . $formatted_persen;
            })

            ->addColumn('dalam', function ($data) {
                $rekom = $data->jml_rekomen;
                if ($rekom != 0) {
                    $dalam = $data->jml_dalam;
                    $persen = $dalam / $rekom * 100;
                    $formatted_persen = number_format($persen, 0) . '%';
                } else {
                    $formatted_persen = 'N/A';
                }
                return $dalam . ' => ' . $formatted_persen;
            })
            ->addColumn('belum', function ($data) {
                $rekom = $data->jml_rekomen;
                if ($rekom != 0) {
                    $belum = $data->jml_belum;
                    $persen = $belum / $rekom * 100;
                    $formatted_persen = number_format($persen, 0) . '%';
                } else {
                    $formatted_persen = 'N/A';
                }
                return $belum . ' => ' . $formatted_persen;
            })
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
                DB::raw('COUNT(CASE WHEN THEN temuans.id ) as jml_temuan'),
                DB::raw('SUM(temuans.nilai_temuan) as total_temuan'),
                DB::raw('SUM(CASE WHEN rekomendasies.temuan_id = temuans.id THEN 1 ELSE 0 END) as jml_rekomen'),
                DB::raw('SUM(CASE WHEN rekomendasies.temuan_id = temuans.id THEN rekomendasies.nilai_rekomendasi ELSE 0 END) as total_rekomendasi'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_selesai = (SELECT MAX(nilai_rekomendasi) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id) THEN 1 ELSE 0 END) as jml_selesai'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_dalam_proses > 0 THEN 1 ELSE 0 END) as jml_dalam'),
                DB::raw('SUM(CASE WHEN rekomendasies.temuan_id = temuans.id AND rekomendasies.id NOT IN (SELECT rekomendasi_id FROM tindak_lanjuts) THEN 1 ELSE 0 END) as jml_belum'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id THEN tindak_lanjuts.nilai_dalam_proses ELSE 0 END) as total_dalam'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id THEN tindak_lanjuts.nilai_sisa ELSE 0 END) as total_sisa')
            )
            ->leftjoin('lhps', 'temuans.lhp_id', '=', 'lhps.id')
            ->leftjoin('rekomendasies', 'rekomendasies.temuan_id', '=', 'temuans.id')
            ->leftjoin('tindak_lanjuts', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
            ->groupBy('temuans.lhp_id');
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
            Column::make('belum')->title('B => %')->addClass('dataTable-font'),
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
                'B => %',
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
                DB::raw('COUNT(temuans.id) as jml_temuan'),
                DB::raw('SUM(temuans.nilai_temuan) as total_temuan'),
                DB::raw('SUM(CASE WHEN rekomendasies.temuan_id = temuans.id THEN 1 ELSE 0 END) as jml_rekomen'),
                DB::raw('SUM(CASE WHEN rekomendasies.temuan_id = temuans.id THEN rekomendasies.nilai_rekomendasi ELSE 0 END) as total_rekomendasi'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_selesai = (SELECT MAX(nilai_rekomendasi) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id) THEN 1 ELSE 0 END) as jml_selesai'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_dalam_proses > 0 THEN 1 ELSE 0 END) as jml_dalam'),
                DB::raw('SUM(CASE WHEN rekomendasies.temuan_id = temuans.id AND rekomendasies.id NOT IN (SELECT rekomendasi_id FROM tindak_lanjuts) THEN 1 ELSE 0 END) as jml_belum'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id THEN tindak_lanjuts.nilai_dalam_proses ELSE 0 END) as total_dalam'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id THEN tindak_lanjuts.nilai_sisa ELSE 0 END) as total_sisa')
            )
            ->leftjoin('lhps', 'temuans.lhp_id', '=', 'lhps.id')
            ->leftjoin('rekomendasies', 'rekomendasies.temuan_id', '=', 'temuans.id')
            ->leftjoin('tindak_lanjuts', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
            ->groupBy('temuans.lhp_id')
            ->get();

        foreach ($laporanRekapitualasi as $index => $laporan) {
            $rpNilaiTemuan = 'Rp ' . number_format($laporan->total_temuan, 0, ',', '.');
            $rpNilairekomen = 'Rp ' . number_format($laporan->total_rekomendasi, 0, ',', '.');
            $rpNilaisetor = 'Rp ' . number_format($laporan->total_setor, 0, ',', '.');
            $rpNilaidalamProses = 'Rp ' . number_format($laporan->total_dalam, 0, ',', '.');
            $rpNilaiSisa = 'Rp ' . number_format($laporan->total_sisa, 0, ',', '.');

            $rekom = $laporan->jml_rekomen;
            $formatted_persen_selesai = 'N/A';
            $formatted_persen_dalam = 'N/A';
            $formatted_persen_belum = 'N/A';

            if ($rekom != 0) {
                $jml_selesai = $laporan->jml_selesai;
                $persenSelesai = $jml_selesai / $rekom * 100;
                $formatted_persen_selesai = number_format($persenSelesai, 0) . '%';

                $jml_dalam = $laporan->jml_dalam;
                $persenDalam = $jml_dalam / $rekom * 100;
                $formatted_persen_dalam = number_format($persenDalam, 0) . '%';

                $jml_belum = $laporan->jml_belum;
                $persenBelum = $jml_belum / $rekom * 100;
                $formatted_persen_belum = number_format($persenBelum, 0) . '%';
            }

            $selesai = $jml_selesai . ' => ' . $formatted_persen_selesai;
            $dalam = $jml_dalam . ' => ' . $formatted_persen_dalam;
            $belum = $jml_belum . ' => ' . $formatted_persen_belum;


            $data[] = [
                $index + 1,
                $laporan->lhp->tahun,
                $laporan->jml_temuan,
                $rpNilaiTemuan,
                $laporan->jml_rekomen,
                $selesai,
                $dalam,
                $belum,
                $rpNilairekomen,
                $rpNilaisetor,
                $rpNilaidalamProses,
                $rpNilaiSisa,

            ];
        }

        // $total =
        //     Temuans::with(['obrik', 'tindakan', 'lhp', 'rekomendasi'])
        //     ->select(
        //         'temuans.lhp_id',
        //         'temuans.id',
        //         DB::raw('(SELECT COUNT(id) FROM temuans )as jml_temuan'),
        //         DB::raw('(SELECT SUM(nilai_temuan) FROM temuans) as total_temuan'),
        //         DB::raw('(SELECT COUNT(id) FROM rekomendasies) as jml_rekomen'),
        //         DB::raw('(SELECT SUM(nilai_rekomendasi) FROM rekomendasies) as total_rekomendasi'),
        //         DB::raw('(SELECT SUM(nilai_setor) FROM tindak_lanjuts ) as total_setor'),
        //         DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts) as total_dalam'),
        //         DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts ) as total_sisa'),
        //         DB::raw('(SELECT COUNT(nilai_selesai) FROM tindak_lanjuts WHERE nilai_selesai = (SELECT MAX(nilai_rekomendasi) FROM rekomendasies )) as jml_selesai'),
        //         DB::raw('(SELECT COUNT(nilai_dalam_proses) FROM tindak_lanjuts WHERE nilai_dalam_proses != 0 ) as jml_dalam'),
        //         DB::raw('(SELECT COUNT(id) FROM rekomendasies WHERE rekomendasies.id NOT IN (SELECT rekomendasi_id FROM tindak_lanjuts)) as jml_belum'),
        //     )
        //     ->leftjoin('lhps', 'temuans.lhp_id', '=', 'lhps.id')
        //     ->groupBy('temuans.lhp_id', 'temuans.id')
        //     ->first();

        // $rekom = $total->jml_rekomen;
        // $formatted_persen_selesai = 'N/A';
        // $formatted_persen_dalam = 'N/A';
        // $formatted_persen_belum = 'N/A';

        // if ($rekom != 0) {
        //     $jml_selesai = $total->jml_selesai;
        //     $persenSelesai = $jml_selesai / $rekom * 100;
        //     $formatted_persen_selesai = number_format($persenSelesai, 0) . '%';

        //     $jml_dalam = $total->jml_dalam;
        //     $persenDalam = $jml_dalam / $rekom * 100;
        //     $formatted_persen_dalam = number_format($persenDalam, 0) . '%';

        //     $jml_belum = $total->jml_belum;
        //     $persenBelum = $jml_belum / $rekom * 100;
        //     $formatted_persen_belum = number_format($persenBelum, 0) . '%';
        // }

        // $selesai = $jml_selesai . ' => ' . $formatted_persen_selesai;
        // $dalam = $jml_dalam . ' => ' . $formatted_persen_dalam;
        // $belum = $jml_belum . ' => ' . $formatted_persen_belum;

        // $data[] = [
        //     [
        //         '',
        //         'Jumlah : ',
        //         $total->jml_temuan,
        //         'Rp ' . number_format($total->total_temuan, 0, ',', '.'),
        //         $total->jml_rekomen,
        //         $selesai,
        //         $dalam,
        //         $belum,
        //         'Rp ' . number_format($total->total_rekomendasi, 0, ',', '.'),
        //         'Rp ' . number_format($total->total_setor, 0, ',', '.'),
        //         'Rp ' . number_format($total->total_dalam, 0, ',', '.'),
        //         'Rp ' . number_format($total->total_sisa, 0, ',', '.'),
        //     ],
        // ];

        return Excel::download(new ExportsLaporanRekapitulasi($data), 'laporan_rekapitulasi_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function pdfCustom()
    {
        $wilayah = Wilayah::select('name')->where('id', auth()->user()->wilayah_id)->first();

        $data =
            Temuans::with(['obrik', 'tindakan', 'lhp', 'rekomendasi'])
            ->select(
                'temuans.lhp_id',
                DB::raw('COUNT(temuans.id) as jml_temuan'),
                DB::raw('SUM(temuans.nilai_temuan) as total_temuan'),
                DB::raw('SUM(CASE WHEN rekomendasies.temuan_id = temuans.id THEN 1 ELSE 0 END) as jml_rekomen'),
                DB::raw('SUM(CASE WHEN rekomendasies.temuan_id = temuans.id THEN rekomendasies.nilai_rekomendasi ELSE 0 END) as total_rekomendasi'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_selesai = (SELECT MAX(nilai_rekomendasi) FROM rekomendasies WHERE rekomendasies.temuan_id = temuans.id) THEN 1 ELSE 0 END) as jml_selesai'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id AND tindak_lanjuts.nilai_dalam_proses > 0 THEN 1 ELSE 0 END) as jml_dalam'),
                DB::raw('SUM(CASE WHEN rekomendasies.temuan_id = temuans.id AND rekomendasies.id NOT IN (SELECT rekomendasi_id FROM tindak_lanjuts) THEN 1 ELSE 0 END) as jml_belum'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id THEN tindak_lanjuts.nilai_dalam_proses ELSE 0 END) as total_dalam'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.temuan_id = temuans.id THEN tindak_lanjuts.nilai_sisa ELSE 0 END) as total_sisa')
            )
            ->leftjoin('lhps', 'temuans.lhp_id', '=', 'lhps.id')
            ->leftjoin('rekomendasies', 'rekomendasies.temuan_id', '=', 'temuans.id')
            ->leftjoin('tindak_lanjuts', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
            ->groupBy('temuans.lhp_id')
            ->get();

        // $total =
        //     Temuans::with(['obrik', 'tindakan', 'lhp', 'rekomendasi'])
        //     ->select(
        //         'temuans.lhp_id',
        //         'temuans.id',
        //         DB::raw('(SELECT COUNT(id) FROM temuans )as jml_temuan'),
        //         DB::raw('(SELECT SUM(nilai_temuan) FROM temuans) as total_temuan'),
        //         DB::raw('(SELECT COUNT(id) FROM rekomendasies) as jml_rekomen'),
        //         DB::raw('(SELECT SUM(nilai_rekomendasi) FROM rekomendasies) as total_rekomendasi'),
        //         DB::raw('(SELECT SUM(nilai_setor) FROM tindak_lanjuts ) as total_setor'),
        //         DB::raw('(SELECT SUM(nilai_dalam_proses) FROM tindak_lanjuts) as total_dalam'),
        //         DB::raw('(SELECT SUM(nilai_sisa) FROM tindak_lanjuts ) as total_sisa'),
        //         DB::raw('(SELECT COUNT(nilai_selesai) FROM tindak_lanjuts WHERE nilai_selesai = (SELECT MAX(nilai_rekomendasi) FROM rekomendasies )) as jml_selesai'),
        //         DB::raw('(SELECT COUNT(nilai_dalam_proses) FROM tindak_lanjuts WHERE nilai_dalam_proses != 0 ) as jml_dalam'),
        //         DB::raw('(SELECT COUNT(id) FROM rekomendasies WHERE rekomendasies.id NOT IN (SELECT rekomendasi_id FROM tindak_lanjuts)) as jml_belum'),
        //     )
        //     ->leftjoin('lhps', 'temuans.lhp_id', '=', 'lhps.id')
        //     ->groupBy('temuans.lhp_id', 'temuans.id')
        //     ->first();

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

        $pdf = Pdf::loadView('pages.pdf.laporan_rekapitulasi', compact('data', 'inspektur', 'wilayah'))->setPaper('legal', 'landscape');
        return $pdf->download('laporan_rekapitulasi_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}