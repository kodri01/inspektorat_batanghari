<?php

namespace App\Http\Controllers;

use App\DataTables\ObriksDataTable;
use App\Models\Obrik;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ObrikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ObriksDataTable $dataTable)
    {
        $title = 'Data Obrik';
        $judul = 'Data Obrik';
        return $dataTable->render('pages.obrik.index', compact('title', 'judul'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Data Obrik';
        $judul = 'Tambah Data Obrik';
        $wilayah = Wilayah::get();
        return view('pages.obrik.add', compact('title', 'judul', 'wilayah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'jenis'     => 'required',
            'kecamatan'      => 'required',
            'wilayah'      => 'required',
        ];

        $messages = [
            'name.required'  => 'Nama Lengkap wajib diisi',
            'name.min'       => 'Nama Lengkap minimal 3 karakter',
            'jenis.required'  => 'Jenis Obrik Wajib diisi',
            'kecamatan.required' => 'Kecamatan Obrik wajib diisi',
            'wilayah.required' => 'Wilayah Obrik wajib diisi',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Obrik::create([
            'wilayah_id' => $request->wilayah,
            'name' => $request->name,
            'jenis' => $request->jenis,
            'kecamatan' => $request->kecamatan,
        ]);

        return redirect()->route('obrik')
            ->with('success', 'Obrik Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Obrik $obrik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'Edit Data Obrik';
        $judul = 'Edit Data Obrik';
        $obrik = Obrik::find($id);
        $obriks = Obrik::get();
        $wilayah = Wilayah::get();
        return view('pages.obrik.edit', compact('title', 'judul', 'obriks', 'obrik', 'wilayah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $rules = [
            'name' => 'required|min:3',
            'jenis'     => 'required',
            'kecamatan'      => 'required',
            'wilayah'      => 'required',
        ];

        $messages = [
            'name.required'  => 'Nama Lengkap wajib diisi',
            'name.min'       => 'Nama Lengkap minimal 3 karakter',
            'jenis.required'  => 'Jenis Obrik Wajib diisi',
            'wilayah.required' => 'Wilayah Inspektur wajib diisi',
            'kecamatan.required' => 'Kecamatan wajib diisi',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $obrik = Obrik::find($id);
        $obrik->update([
            'wilayah_id' => $request->wilayah,
            'name' => $request->name,
            'jenis' => $request->jenis,
            'kecamatan' => $request->kecamatan,
        ]);

        return redirect()->route('obrik')
            ->with('success', 'Obrik Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Obrik::find($id)->delete();
        return redirect()->route('obrik')
            ->with('error', 'Obrik Deleted Successfully');
    }
}
