<?php

namespace App\Http\Controllers;

use App\Models\lhp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LhpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Upload LHP';
        $judul = 'Upload LHP';
        return view('pages.lhp.add', compact('title', 'judul'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'tahun' => 'required',
            'no_lhp'     => 'required',
            'tgl_lhp'     => 'required',
            'judul'      => 'required',
        ];

        $messages = [
            'tahun.required'  => 'Tahun wajib diisi',
            'no_lhp.required'       => 'Nomor LHP Wajib diisi',
            'tgl_lhp.required'  => 'Tanggal LHP Wajib diisi',
            'judul.required' => 'Judul LHP wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $namefile = str_replace(' ', '_', $request->file->getClientOriginalName());
        $filename  = $namefile . '_' . time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $filename);

        $awal = 700;
        $input = $request->no_lhp;
        $akhir = 'ITDA';
        $year = date('Y');
        $noLhp = $awal . '/' . $input . '/' . $akhir . '/' . $year;


        Lhp::create([
            'tahun' => $request->tahun,
            'no_lhp' => $noLhp,
            'tgl_lhp' => $request->tgl_lhp,
            'judul' => $request->judul,
            'upload' => $filename,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'LHP Uploaded Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(lhp $lhp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(lhp $lhp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, lhp $lhp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(lhp $lhp)
    {
        //
    }
}
