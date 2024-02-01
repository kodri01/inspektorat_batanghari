<?php

namespace App\DataTables;

use App\Models\Inspektur;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class InspektursDataTable extends DataTable
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
                $buttons = '<div class="btn-group gap-2">';
                // $buttons .= '<a href="' . route('dashboard', ['id' => $row->id]) . '" class="btn btn-sm btn-success">Role</a>';
                $buttons .= '<a href="' . route('inspektur.edit', ['id' => $row->id]) . '" class="btn btn-sm btn-primary">Edit</a>';
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
    <form id="form-delete-' . $row->id . '" action="' . route('inspektur.delete', ['id' => $row->id]) . '" method="post" class="d-none">
        ' . csrf_field() . '
    </form>';
                $buttons .= '</div>';

                return $buttons;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Inspektur $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('inspekturs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->responsive(true);
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
                ->addClass('text-center dataTable-font')
                ->responsivePriority(1),
            Column::make('name')->title('Nama Lengkap')->responsivePriority(3)->addClass('dataTable-font'),
            Column::make('nip')->title('NIP')->responsivePriority(4)->addClass('dataTable-font'),
            Column::make('pangkat_gol')->title('Pangkal/Golongan')->responsivePriority(5)->addClass('dataTable-font'),
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
        return 'Inspekturs_' . date('YmdHis');
    }
}
