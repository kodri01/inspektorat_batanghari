<?php

namespace App\Http\Controllers;

use App\DataTables\LaporanRincianDataTable;
use App\Models\Obrik;
use App\Models\TindakLanjut;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanRincianController extends Controller
{

    public function index(LaporanRincianDataTable $dataTable)
    {
        $title = 'Laporan Rincian';
        $judul = 'Laporan Rincian';

        return $dataTable->render('pages.laporan.laporan', compact('title', 'judul'));
    }

    public function excelRincian(LaporanRincianDataTable $dataTable)
    {
        return $dataTable->excelCustom();
    }

    public function pdfRincian(LaporanRincianDataTable $dataTable)
    {
        return $dataTable->pdfCustom();
    }
}
