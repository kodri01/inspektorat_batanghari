<?php

namespace App\Http\Controllers;

use App\DataTables\LaporanRekapDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanRekapController extends Controller
{
    public function index(LaporanRekapDataTable $dataTable)
    {
        $title = 'Laporan Rekap';
        $judul = 'Laporan Rekap';
        return $dataTable->render('pages.laporan.laporan', compact('title', 'judul'));
    }

    public function excel_rekap(LaporanRekapDataTable $dataTable)
    {
        return $dataTable->excelCustom();
    }

    public function pdf_rekap(LaporanRekapDataTable $dataTable)
    {
        return $dataTable->pdfCustom();
    }
}
