<?php

namespace App\DataTables;

use App\Models\Tindakan;
use App\Models\TindakLanjut;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TindakanDataTable extends DataTable
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
            ->addColumn('belum_tindak', function ($data) {
                $tahun = $data->temuan->tahun;

                return $tahun;
            })
            ->addColumn('action', function ($row) {
                $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
                $role = Role::where('id', $modelrole->role_id)->first();

                if ($role->name == 'superadmin') {
                    $buttons = '<div class="btn-group gap-1">';
                    $buttons .= '<a href="' . route('tindakan.edit', ['id' => $row->id]) . '" class="btn btn-sm btn-primary">Edit</a>';
                    if ($row->status_tl == "Selesai") {
                        $buttons .= '';
                    } else {
                        $buttons .= '<a href="' . route('tindakan.proses', ['id' => $row->id]) . '"  class="btn btn-sm rounded btn-info">Proses</a>';
                    }
                    $buttons .= '
<a href="#" class="btn btn-sm btn-danger" onclick="event.preventDefault(); 
    if(confirm(\'Anda yakin akan menghapus data ini?\')) {
        document.getElementById(\'form-delete-' . $row->id . '\').submit(); 
    } else {
        return false;
    }"
>
    Delete
</a>
<form id="form-delete-' . $row->id . '" action="' . route('tindakan.delete', ['id' => $row->id]) . '" method="post" class="d-none">
    ' . csrf_field() . '
</form>';

                    $buttons .= '</div>';
                    return $buttons;
                } else {

                    if ($row->status == 0) {
                        $buttons = '<div class="btn-group gap-2">';
                        $buttons .= '<a href="' . route('tindakan.edit', ['id' => $row->id]) . '" class="btn btn-sm rounded btn-primary">Edit</a>';
                        $buttons .= '
        <a href="' . route('tindakan.status', ['id' => $row->id]) . '" class="btn btn-sm btn-success rounded" onclick="event.preventDefault(); document.getElementById(\'form-tindakan-status-' . $row->id . '\').submit();">
            Kirim
        </a>
        <form id="form-tindakan-status-' . $row->id . '" action="' . route('tindakan.status', ['id' => $row->id]) . '" method="post" class="d-none">
            ' . csrf_field() . '
        </form>';
                        if ($row->status_tl == "Selesai") {
                            $buttons .= '';
                        } else {
                            $buttons .= '<a href="' . route('tindakan.proses', ['id' => $row->id]) . '"  class="btn btn-sm rounded btn-info">Proses</a>';
                        }
                        $buttons .= '</div>';
                        return $buttons;
                    } else {
                        return '<Span class=" badge bg-success w-100">Data Terkirim</Span>';
                    }
                }
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TindakLanjut $model): QueryBuilder
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
        $role = Role::where('id', $modelrole->role_id)->first();
        if ($role->name == 'superadmin') {
            return $model->with(['obrik', 'temuan', 'lhp'])->select('tindak_lanjuts.*');
        } else {
            return $model->with(['obrik', 'temuan', 'lhp', 'rekomendasi'])->select('tindak_lanjuts.*')->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id);
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tindakan-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle();
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
            Column::make('obrik.name')->title('Nama Obrik')->responsivePriority(1)->addClass('dataTable-font'),
            Column::make('temuan.ringkasan')->title('Temuan')->addClass('dataTable-font'),
            Column::make('lhp.tahun')->title('Tahun')->addClass('dataTable-font'),
            Column::make('rekomendasi.nilai_rekomendasi')
                ->title('Rekomendasi')
                ->addClass('dataTable-font')
                ->responsivePriority(2)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('uraian')->title('Uraian')->addClass('dataTable-font'),
            Column::make('nilai_selesai')
                ->title('Selesai')
                ->addClass('dataTable-font')
                ->responsivePriority(4)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('nilai_dalam_proses')
                ->title('Dalam Proses')
                ->addClass('dataTable-font')
                ->responsivePriority(5)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('nilai_sisa')
                ->title('Belum Ditindak')
                ->addClass('dataTable-font')
                ->responsivePriority(5)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('status_tl')->title('Status Tindak Lanjut')->addClass('dataTable-font'),
            // Column::make('saran')->title('Saran')->addClass('dataTable-font'),
            Column::computed('action')
                ->exportable(true)
                ->printable(true)
                ->width(50)
                ->addClass('dataTable-font')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Tindakan_' . date('YmdHis');
    }
}
