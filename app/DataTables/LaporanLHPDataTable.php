<?php

namespace App\DataTables;

use App\Exports\LaporanPHPExport;
use App\Models\TindakLanjut;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LaporanLHPDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    // protected $exportClass = LaporanPHPExport::class;


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
                return $model->with(['obrik', 'temuan', 'lhp', 'rekomendasi'])
                    ->select(
                        'tindak_lanjuts.*',
                    )
                    ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id);
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
            ->setTableId('laporanlhp-table')
            ->columns($this->getColumns())
            ->responsive(true)
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([
                Button::make('excel')->action('window.location.href = "' . route("excel_php") . '"')->addClass('text-success text-bold rounded'),
                // Button::make('excel'),
                Button::make('pdf')->action('window.location.href = "' . route("pdf_php") . '"')->addClass('text-danger text-bold rounded'),
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
            Column::make('lhp.no_lhp')->title('Nomor LHP')->responsivePriority(1)->addClass('dataTable-font'),
            Column::make('temuan.ringkasan')->title('Judul Temuan')->addClass('dataTable-font'),
            Column::make('temuan.nilai_temuan')
                ->title('Nilai Temuan')
                ->addClass('dataTable-font')
                ->responsivePriority(2)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('rekomendasi.rekomendasi')->title('Rekomendasi')->addClass('dataTable-font'),
            Column::make('rekomendasi.nilai_rekomendasi')
                ->title('Nilai Rekomendasi')
                ->addClass('dataTable-font')
                ->responsivePriority(4)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('uraian')->title('Uraian TL')->addClass('dataTable-font'),
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
                ->title('Belum Ditindak')
                ->addClass('dataTable-font')
                ->responsivePriority(7)
                ->renderJs('number', '.', ',', '', ' Rp. '),
            Column::make('status_tl')->title('Status')->addClass('dataTable-font'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LaporanPHP_' . date('dmY');
    }

    public function excelCustom()
    {

        $tahun = now()->year;

        $data = [
            [
                'DAFTAR POKOK - POKOK HASIL PEMERIKSAAN'
            ],
            [
                'APARAT PENGAWASAN FUNGSIONAL INSPEKTORAT KABUPATEN BATANG HARI'
            ],
            [
                'TAHUN PEMERIKSAAN ' . $tahun
            ],
            [
                ''
            ],
            [
                '#',
                'Kategori',
                'Nomor LHP',
                'Judul Temuan',
                'Nilai Temuan',
                'Rekomendasi',
                'Nilai Rekomendasi',
                'Uraian',
                'Nilai Selesai',
                'Dalam Proses',
                'Belum Ditindak',
                'Status',
            ],
        ];

        $laporanPHP =
            TindakLanjut::with(['obrik', 'temuan', 'lhp', 'rekomendasi'])
            ->join('obriks', 'tindak_lanjuts.obrik_id', '=', 'obriks.id')
            ->select(
                'tindak_lanjuts.*',
            )
            ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
            ->whereNull('tindak_lanjuts.deleted_at')
            ->orderBy('obriks.jenis', 'desc')
            ->get();

        $userWilayah = auth()->user()->wilayah_id;

        $total = TindakLanjut::join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
            ->join('rekomendasies', 'tindak_lanjuts.rekomendasi_id', '=', 'rekomendasies.id')
            ->where('tindak_lanjuts.wilayah_id', $userWilayah)
            ->whereNull('tindak_lanjuts.deleted_at')
            ->selectRaw('SUM(DISTINCT temuans.nilai_temuan) AS total_nilai_temuan')
            ->selectRaw('SUM(CASE WHEN tindak_lanjuts.rekomendasi_id = rekomendasies.id THEN rekomendasies.nilai_rekomendasi ELSE 0 END) AS total_nilai_rekomen')
            ->selectRaw('SUM(nilai_selesai) AS total_nilai_selesai')
            ->selectRaw('SUM(nilai_dalam_proses) AS total_nilai_dalam_proses')
            ->selectRaw('SUM(nilai_sisa) AS total_nilai_sisa')
            ->first();

        // dd($laporanPHP);
        $displayedObrikIds = [];
        $displayedTemuan = [];

        foreach ($laporanPHP as $index => $laporan) {
            $rpNilaiTemuan = 'Rp ' . number_format($laporan->temuan->nilai_temuan, 0, ',', '.');
            $rpNilairekomen = 'Rp ' . number_format($laporan->rekomendasi->nilai_rekomendasi, 0, ',', '.');
            $rpNilaiselesai = 'Rp ' . number_format($laporan->nilai_selesai, 0, ',', '.');
            $rpNilaidalamProses = 'Rp ' . number_format($laporan->nilai_dalam_proses, 0, ',', '.');
            $rpNilaiSisa = 'Rp ' . number_format($laporan->nilai_sisa, 0, ',', '.');

            $obrikId = $laporan->obrik_id;
            $temuanKey = $laporan->temuan->ringkasan . '|' . $laporan->temuan->nilai_temuan;
            $lhpKey = $laporan->lhp->no_lhp;

            if (!in_array($obrikId, $displayedObrikIds)) {
                // Display full data
                $data[] = [
                    $index + 1,
                    $laporan->obrik->jenis,
                    $laporan->lhp->no_lhp,
                    $laporan->temuan->ringkasan,
                    $rpNilaiTemuan,
                    $laporan->rekomendasi->rekomendasi,
                    $rpNilairekomen,
                    $laporan->uraian,
                    $rpNilaiselesai,
                    $rpNilaidalamProses,
                    $rpNilaiSisa,
                    $laporan->status_tl,
                ];

                $displayedObrikIds[] = $obrikId;
                $displayedTemuan[$obrikId] = [
                    'temuan' => [$temuanKey],
                    'lhp' => [$lhpKey]
                ];
            } else {
                // Display minimal data
                $temuanExists = in_array($temuanKey, $displayedTemuan[$obrikId]['temuan']);
                $lhpExists = in_array($lhpKey, $displayedTemuan[$obrikId]['lhp']);

                $data[] = [
                    $index + 1,
                    '',
                    $lhpExists ? '' : $laporan->lhp->no_lhp,
                    $temuanExists ? '' : $laporan->temuan->ringkasan,
                    $temuanExists ? '' : $rpNilaiTemuan,
                    $laporan->rekomendasi->rekomendasi,
                    $rpNilairekomen,
                    $laporan->uraian,
                    $rpNilaiselesai,
                    $rpNilaidalamProses,
                    $rpNilaiSisa,
                    $laporan->status_tl,
                ];

                if (!$temuanExists) {
                    $displayedTemuan[$obrikId]['temuan'][] = $temuanKey;
                }
                if (!$lhpExists) {
                    $displayedTemuan[$obrikId]['lhp'][] = $lhpKey;
                }
            }
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
                    '',
                    'Rp ' . number_format($total->total_nilai_temuan, 0, ',', '.'),
                    '',
                    'Rp ' . number_format($total->total_nilai_rekomen, 0, ',', '.'),
                    '',
                    'Rp ' . number_format($total->total_nilai_selesai, 0, ',', '.'),
                    'Rp ' . number_format($total->total_nilai_dalam_proses, 0, ',', '.'),
                    'Rp ' . number_format($total->total_nilai_sisa, 0, ',', '.'),
                ],
            ];
        }

        return Excel::download(new LaporanPHPExport($data), 'laporanPHP_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function pdfCustom()
    {
        $tahun = now()->year;
        $wilayah = Wilayah::select('name')->where('id', auth()->user()->wilayah_id)->first();

        $data =
            TindakLanjut::with(['obrik', 'temuan', 'lhp', 'rekomendasi'])
            ->join('obriks', 'tindak_lanjuts.obrik_id', '=', 'obriks.id')
            ->select(
                'tindak_lanjuts.*',
            )
            ->where('tindak_lanjuts.wilayah_id', auth()->user()->wilayah_id)
            ->whereNull('tindak_lanjuts.deleted_at')
            ->orderBy('obriks.jenis', 'desc')
            ->get();

        $userWilayah = auth()->user()->wilayah_id;

        $total = TindakLanjut::join('temuans', 'tindak_lanjuts.temuan_id', '=', 'temuans.id')
            ->join('rekomendasies', 'tindak_lanjuts.rekomendasi_id', '=', 'rekomendasies.id')
            ->where('tindak_lanjuts.wilayah_id', $userWilayah)
            ->whereNull('tindak_lanjuts.deleted_at')
            ->selectRaw('SUM(DISTINCT temuans.nilai_temuan) AS total_nilai_temuan')
            ->selectRaw('SUM(CASE WHEN tindak_lanjuts.rekomendasi_id = rekomendasies.id THEN rekomendasies.nilai_rekomendasi ELSE 0 END) AS total_nilai_rekomen')
            ->selectRaw('SUM(nilai_selesai) AS total_nilai_selesai')
            ->selectRaw('SUM(nilai_dalam_proses) AS total_nilai_dalam_proses')
            ->selectRaw('SUM(nilai_sisa) AS total_nilai_sisa')
            ->first();
        // dd($total);
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
