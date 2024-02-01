<?php

namespace App\DataTables;

use App\Models\Temuans;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Termwind\Components\Span;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TemuansDataTable extends DataTable
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
            ->addColumn('action', function ($row) {
                $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
                $role = Role::where('id', $modelrole->role_id)->first();

                if ($role->name == 'superadmin') {
                    $buttons = '<div class="btn-group gap-2">';
                    $buttons .= '<a href="' . route('temuan.edit', ['id' => $row->id]) . '" class="btn btn-sm rounded btn-primary">Edit</a>';
                    $buttons .= '
    <a href="#" class="btn btn-sm btn-danger rounded" onclick="event.preventDefault(); 
        if(confirm(\'Anda yakin akan menghapus data ini?\')) {
            document.getElementById(\'form-delete-' . $row->id . '\').submit(); 
        } else {
            return false;
        }"
    >
        Delete
    </a>
    <form id="form-delete-' . $row->id . '" action="' . route('temuan.delete', ['id' => $row->id]) . '" method="post" class="d-none">
        ' . csrf_field() . '
    </form>';

                    $buttons .= '</div>';
                    return $buttons;
                } else {

                    if ($row->status == 0) {
                        $buttons = '<div class="btn-group gap-2">';
                        $buttons .= '
            <a href="' . route('temuan.status', ['id' => $row->id]) . '" class="btn btn-sm btn-success rounded" onclick="event.preventDefault(); document.getElementById(\'form-temuan-status-' . $row->id . '\').submit();">
                Kirim
            </a>
            <form id="form-temuan-status-' . $row->id . '" action="' . route('temuan.status', ['id' => $row->id]) . '" method="post" class="d-none">
                ' . csrf_field() . '
            </form>';
                        $buttons .= '<a href="' . route('temuan.edit', ['id' => $row->id]) . '" class="btn btn-sm rounded btn-primary">Edit</a>';
                        $buttons .= '</div>';
                        return $buttons;
                    } else {
                        return '<Span class=" badge bg-success">Terkirim</Span>';
                    }
                }
            })
            ->setRowId('id');
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Temuans $model): QueryBuilder
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
        $role = Role::where('id', $modelrole->role_id)->first();
        if ($role->name == 'superadmin') {
            return $model->with(['obrik', 'lhp'])->select('temuans.*');
        } else {
            return $model->with(['obrik', 'lhp'])->select('temuans.*')->where('temuans.wilayah_id', auth()->user()->wilayah_id);
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('temuans-table')
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
            Column::make('lhp.tahun')->title('Tahun')->addClass('dataTable-font'),
            Column::make('lhp.no_lhp')->title('Nomor LHP')->responsivePriority(3)->addClass('dataTable-font'),
            Column::make('ringkasan')->title('Ringkasan Temuan')->addClass('dataTable-font'),
            Column::make('jns_temuan')->title('Jenis Temuan')->addClass('dataTable-font'),
            Column::make('nilai_temuan')
                ->title('Nilai Temuan')
                ->addClass('dataTable-font')
                ->responsivePriority(4)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('nilai_rekomendasi')
                ->title('Nilai Rekomendasi')
                ->addClass('dataTable-font')
                ->responsivePriority(5)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::computed('action')
                ->exportable(true)
                ->printable(true)
                ->width(100)
                ->addClass('text-center dataTable-font')
                ->responsivePriority(2),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Temuans_' . date('YmdHis');
    }
}
