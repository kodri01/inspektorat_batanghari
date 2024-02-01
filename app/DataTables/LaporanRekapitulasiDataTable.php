<?php

namespace App\DataTables;

use App\Models\LaporanRekapitulasi;
use App\Models\Temuans;
use App\Models\TindakLanjut;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
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
                $rekom = $data->total_rekomen;
                $selesai = $data->jml_selesai;
                $persen = $selesai / $rekom * 100;
                return $selesai . ' => ' . $persen;
            })

            ->addColumn('dalam', function ($data) {
                $rekom = $data->total_rekomen;
                $dalam = $data->jml_dalam;
                $persen = $dalam / $rekom * 100;
                return $dalam . ' => ' . $persen;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TindakLanjut $model): QueryBuilder
    {
        return $model->with(['obrik', 'temuan', 'lhp'])
            ->select(
                'tindak_lanjuts.lhp_id',
                DB::raw('COUNT(temuans.id) as jml_temuan'),
                DB::raw('SUM(temuans.nilai_temuan) as total_temuan'),
                DB::raw('COUNT(temuans.rekomendasi) as jml_rekomen'),
                DB::raw('COUNT(tindak_lanjuts.rekomendasi) as total_rekomen'),
                DB::raw('COUNT(tindak_lanjuts.status_tl = "Selesai") as jml_selesai'),
                DB::raw('COUNT(tindak_lanjuts.status_tl = "Dalam Proses") as jml_dalam'),
                // DB::raw('SUM(CASE WHEN temuans.jns_temuan = "Kerugian Negara" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_negara'),
                // DB::raw('SUM(CASE WHEN temuans.jns_temuan = "Daerah" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_daerah'),
                // DB::raw('SUM(CASE WHEN temuans.jns_temuan = "Lain-lainnya" THEN tindak_lanjuts.nilai_setor ELSE 0 END) as total_setor_lainnya'),

            )
            ->join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
            ->groupBy('tindak_lanjuts.lhp_id');
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
                Button::make('excel')->action('window.location.href = "' . route("excel_rekap") . '"')->addClass('text-success text-bold rounded'),
                // Button::make('pdf'),
                // Button::make('pdf')->action('window.location.href = "' . route("pdfPHP") . '"')->addClass('text-danger text-bold rounded'),
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
            Column::make('jml_temuan')->title('Jlh Temuan')->responsivePriority(1)->addClass('dataTable-font'),
            Column::make('total_temuan')
                ->title('Nilai Temuan')
                ->addClass('dataTable-font')
                ->responsivePriority(2)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('jml_rekomen')->title('Jlh Rekom')->responsivePriority(1)->addClass('dataTable-font'),
            Column::make('selesai')->title('S => %')->responsivePriority(1)->addClass('dataTable-font'),
            Column::make('dalam')->title('D => %')->responsivePriority(1)->addClass('dataTable-font'),
            // Column::make('belum')->title('B => %')->responsivePriority(1)->addClass('dataTable-font'),
            // Column::make('total_rekomendasi')
            //     ->title('Nilai Rekomendasi')
            //     ->addClass('dataTable-font')
            //     ->responsivePriority(2)
            //     ->renderJs('number', '.', ',', '', ' Rp. '),
            // Column::make('total_setor')
            //     ->title('Disetor')
            //     ->addClass('dataTable-font')
            //     ->responsivePriority(2)
            //     ->renderJs('number', '.', ',', '', ' Rp. '),
            // Column::make('total_proses')
            //     ->title('Dalam Proses')
            //     ->addClass('dataTable-font')
            //     ->responsivePriority(2)
            //     ->renderJs('number', '.', ',', '', ' Rp. '),
            // Column::make('total_sisa')
            //     ->title('Sisa')
            //     ->addClass('dataTable-font')
            //     ->responsivePriority(2)
            //     ->renderJs('number', '.', ',', '', ' Rp. '),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LaporanRekapitulasi_' . date('YmdHis');
    }
}
