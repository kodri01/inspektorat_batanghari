<?php

namespace App\Http\Controllers;

use App\DataTables\LaporanRekapitulasiDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanRekapitulasiController extends Controller
{
    public function index(LaporanRekapitulasiDataTable $dataTable)
    {

        $title = 'Laporan Rekapitulasi';
        $judul = 'Laporan Rekapitulasi';
        return $dataTable->render('pages.laporan.laporan', compact('title', 'judul'));
    }

    public function excel_rekapitulasi(LaporanRekapitulasiDataTable $dataTable)
    {
        return $dataTable->excelCustom();
    }

    public function pdf_rekapitulasi(LaporanRekapitulasiDataTable $dataTable)
    {
        return $dataTable->pdfCustom();
    }
}
