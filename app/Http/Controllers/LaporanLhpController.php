<?php

namespace App\Http\Controllers;

use App\DataTables\LaporanLHPDataTable;
use App\Models\Obrik;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanLhpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LaporanLHPDataTable $dataTable)
    {
        $title = 'Laporan PHP';
        $judul = 'Laporan PHP';

        return $dataTable->render('pages.laporan.laporan', compact('title', 'judul'));
    }

    public function excelPHP(LaporanLHPDataTable $dataTable)
    {
        return $dataTable->excelCustom();
    }

    public function pdfPHP(LaporanLHPDataTable $dataTable)
    {
        return $dataTable->pdfCustom();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
