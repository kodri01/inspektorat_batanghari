<?php

namespace App\Http\Controllers;

use App\DataTables\InspektursDataTable;
use App\Models\Inspektur;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InspekturController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        # code...
        $this->middleware(['permission:users-list|users-create|users-edit|users-delete']);
    }

    public function index(InspektursDataTable $dataTable)
    {
        $title = 'Data Inspektur';
        $judul = 'Data Inspektur';

        return $dataTable->render('pages.inspektur.index', compact('title', 'judul'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Data Inspektur';
        $judul = 'Tambah Data Inspektur';
        $wilayah = Wilayah::get();
        return view('pages.inspektur.add', compact('title', 'judul', 'wilayah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'nip'     => 'required|min:10',
            'pangkat_gol'      => 'required',
        ];

        $messages = [
            'name.required'  => 'Nama Lengkap wajib diisi',
            'name.min'       => 'Nama Lengkap minimal 3 karakter',
            'nip.required'  => 'Nomor NIP Wajib diisi',
            'nip.min'       => 'Nomor NIP minimal 10 karakter',
            'pangkat_gol.required' => 'Pangkat Golongan wajib diisi',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Inspektur::create([
            'wilayah_id' => $request->wilayah,
            'name' => $request->name,
            'nip' => $request->nip,
            'pangkat_gol' => $request->pangkat_gol,
        ]);

        return redirect()->route('inspektur')
            ->with('success', 'Inspektur Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inspektur $inspektur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'Edit Data Inspektur';
        $judul = 'Edit Data Inspektur';
        $inspektur = Inspektur::find($id);
        $wilayah = Wilayah::get();
        return view('pages.inspektur.edit', compact('title', 'judul', 'inspektur', 'wilayah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|min:3',
            'nip'     => 'required|min:10',
            'pangkat_gol'      => 'required',
        ];

        $messages = [
            'name.required'  => 'Nama Lengkap wajib diisi',
            'name.min'       => 'Nama Lengkap minimal 3 karakter',
            'nip.required'  => 'Nomor NIP Wajib diisi',
            'nip.min'       => 'Nomor NIP minimal 10 karakter',
            'pangkat_gol.required' => 'Pangkat Golongan wajib diisi',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $inspektur = Inspektur::find($id);
        $inspektur->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'pangkat_gol' => $request->pangkat_gol,
            'wilayah_id' => $request->wilayah,
        ]);

        return redirect()->route('inspektur')
            ->with('success', 'Inspektur Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Inspektur::find($id)->delete();
        return redirect()->route('inspektur')
            ->with('error', 'Inspektur Deleted Successfully');
    }
}
