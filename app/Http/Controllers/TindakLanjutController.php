<?php

namespace App\Http\Controllers;

use App\DataTables\TindakanDataTable;
use App\Models\Lhp;
use App\Models\Obrik;
use App\Models\Rekomendasies;
use App\Models\Temuans;
use App\Models\TindakLanjut;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class TindakLanjutController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        # code...
        $this->middleware(['permission:tindakan-list|tindakan-create|tindakan-edit|tindakan-delete']);
    }

    public function index(TindakanDataTable $dataTable)
    {
        $title = 'Data Tindak Lanjut';
        $judul = 'Data Tindak Lanjut';
        return $dataTable->render('pages.tindak_lanjut.index', compact('title', 'judul'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Data Tindak Lanjut';
        $judul = 'Tambah Data Tindak Lanjut';
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
        $role = Role::where('id', $modelrole->role_id)->first();

        if ($role->name == 'superadmin') {
            $lhps = Lhp::get();
            $obriks = Obrik::get();
            $temuans = Temuans::get();
            $rekomens = Rekomendasies::get();
            $wilayah = Wilayah::get();
            return view('pages.tindak_lanjut.add', compact('title', 'judul', 'obriks', 'lhps', 'temuans', 'wilayah', 'rekomens'));
        } else {
            $lhps = Lhp::get();
            $obriks = Obrik::where('wilayah_id', Auth::user()->wilayah_id)->get();
            $temuans = Temuans::where('wilayah_id', Auth::user()->wilayah_id)->get();
            $rekomens = Rekomendasies::where('wilayah_id', Auth::user()->wilayah_id)->get();
            return view('pages.tindak_lanjut.add', compact('title', 'judul', 'obriks', 'lhps', 'temuans', 'rekomens'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'lhp' => 'required',
            'obrik'      => 'required',
            'temuan'      => 'required',
            'rekomendasi'      => 'required',
            'uraian'      => 'required',
            'nilai_tl'      => 'required',
            'saran'      => 'required',
            'file'      => 'required',
        ];

        $messages = [
            'lhp.required'  => 'Tahun wajib diisi',
            'obrik.required' => 'Obrik wajib dipilih',
            'temuan.required' => 'Temuan wajib diisi',
            'rekomendasi.required' => 'Rekomendasi wajib diisi',
            'uraian.required' => 'Uraian wajib diisi',
            'nilai_tl.required' => 'Nilai Tindak Lanjut wajib diisi',
            'saran.required' => 'Ringkasan wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $namefile = str_replace(' ', '_', $request->file->getClientOriginalName());
        $filename  = $namefile . '_' . time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $filename);

        $idRek = $request->rekomendasi;
        $rekomen = Rekomendasies::find($idRek);
        $nilaiRekomen = $rekomen->nilai_rekomendasi;

        $statusTl = $request->status;
        $nilaiTindak = $request->nilai_tl;
        $nilaiSelesai = 0;
        $nilaiDalam = 0;

        if ($statusTl == 'Selesai') {
            $nilaiSelesai = $nilaiTindak;
        } else {
            $nilaiDalam = $nilaiTindak;
        }

        $nilaiSisa = $nilaiRekomen - ($nilaiSelesai + $nilaiDalam);
        $nilaiSetor = $nilaiSelesai + $nilaiSisa;

        TindakLanjut::create([
            'wilayah_id' => $request->wilayah,
            'lhp_id' => $request->lhp,
            'obrik_id' => $request->obrik,
            'temuan_id' => $request->temuan,
            'rekomendasi_id' => $request->rekomendasi,
            'uraian' => $request->uraian,
            'status_tl' => $statusTl,
            'nilai_selesai' => $nilaiSelesai,
            'nilai_dalam_proses' => $nilaiDalam,
            'nilai_sisa' => $nilaiSisa,
            'nilai_setor' => $nilaiSetor,
            'saran' => $request->saran,
            'file' => $filename,
            'status' => 0,
        ]);

        return redirect()->route('tindakan')
            ->with('success', 'Tindakan Created Successfully');
    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        $title = 'Proses Data Tindak Lanjut';
        $judul = 'Proses Data Tindak Lanjut';
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
        $role = Role::where('id', $modelrole->role_id)->first();
        $tindakan = TindakLanjut::find($id);

        if ($role->name == 'superadmin') {
            // $tindakan = TindakLanjut::get();
            return view('pages.tindak_lanjut.proses', compact('title', 'judul', 'tindakan'));
        } else {
            $obrik = $tindakan->obrik;
            $temuan = $tindakan->temuan;
            $rekomen = $tindakan->rekomendasi;
            // dd($rekomen);
            return view('pages.tindak_lanjut.proses', compact('title', 'judul',  'tindakan', 'obrik', 'temuan', 'rekomen'));
        }
    }

    public function proses(Request $request, $id)
    {
        $rules = [
            'nilai_tl'      => 'required',
        ];

        $messages = [
            'nilai_tl.required' => 'Nilai Tindak Lanjut wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $statusTl = $request->statusTl;
        $nilaiSisaAwal = $request->nilai_sisa;
        $nilaiRekomen = $request->rekomen;
        $nilaiTindak = $request->nilai_tl;
        $nilaiDalamProses = $request->nilai_dalam_proses;
        $nilaiSelesaiAwal = $request->nilai_selesai;
        $nilaiSelesai = $nilaiSelesaiAwal + $nilaiDalamProses;

        if ($nilaiTindak < $nilaiSisaAwal && $nilaiSelesaiAwal == 0) {
            $finalSelesai = $nilaiSelesai;
            $nilaiDalam = $nilaiTindak;
            $nilaiSisa = $nilaiSisaAwal - $nilaiDalam;
            $nilaiSetor = $finalSelesai + $nilaiSisa;
            $statusTl = "Dalam Proses";
        }

        if ($nilaiTindak == $nilaiSisaAwal && $nilaiSelesaiAwal != 0) {
            $nilaiDalam = 0;
            $finalSelesai = $nilaiSelesai + $nilaiTindak;
            $nilaiSisa = $nilaiSisaAwal - $nilaiTindak;
            $nilaiSetor = $finalSelesai + $nilaiSisa;
            $statusTl = "Selesai";
        }

        if ($nilaiTindak < $nilaiSisaAwal && $nilaiSelesaiAwal != 0) {
            $nilaiDalam = $nilaiTindak;
            $finalSelesai = $nilaiSelesai;
            $nilaiSisa = $nilaiSisaAwal - $nilaiTindak;
            $nilaiSetor = $finalSelesai + $nilaiSisa;
            $statusTl = "Dalam Proses";
        }

        if ($nilaiTindak == $nilaiSisaAwal && $nilaiSelesaiAwal == 0) {
            $nilaiDalam = 0;
            $finalSelesai = $nilaiSelesai + $nilaiTindak;
            $nilaiSisa = $nilaiSisaAwal - $nilaiTindak;
            $nilaiSetor = $finalSelesai + $nilaiSisa;
            $statusTl = "Selesai";
        }

        if ($nilaiSelesai == $nilaiRekomen) {
            $statusTl = "Selesai";
        }

        $tindakan = TindakLanjut::find($id);
        // $tindak = [
        //     'status' => $request->statusTl,
        //     'nilaiSisaAwal' => $request->nilai_sisa,
        //     'nilaiRekomen' => $request->rekomen,
        //     'nilaiTindak' => $request->nilai_tl,
        //     'nilaiDalamProses' => $request->nilai_dalam_proses,
        //     'nilaiSelesaiAwal' => $request->nilai_selesai,
        //     'nilaiSelesai' => $nilaiSelesaiAwal + $nilaiDalamProses,

        //     $nilaiTindak,
        //     'nilai_tindak2' => $nilaiTindak == $nilaiRekomen - $nilaiDalamProses,

        //     'nilaiDalam' => $nilaiDalam,
        //     'finalSelesai' => $finalSelesai,
        //     'nilaiSisa' => $nilaiSisa,
        //     'nilaiSetor' => $nilaiSetor,
        //     'statusTl' => $statusTl,
        // ];

        // dd($tindak);
        $tindakan->update([
            'status_tl' => $statusTl,
            'nilai_selesai' => $finalSelesai,
            'nilai_dalam_proses' => $nilaiDalam,
            'nilai_sisa' => $nilaiSisa,
            'nilai_setor' => $nilaiSetor,
        ]);

        return redirect()->route('tindakan')
            ->with('success', 'Tindakan Process Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
        $role = Role::where('id', $modelrole->role_id)->first();

        $title = 'Edit Data Tindak Lanjut';
        $judul = 'Edit Data Tindak Lanjut';
        $tindakan = TindakLanjut::find($id);
        $lhps = Lhp::get();


        if ($role->name == 'superadmin') {
            $obriks = Obrik::get();
            $temuans = Temuans::get();
            $wilayah = Wilayah::get();
            return view('pages.tindak_lanjut.edit', compact('title', 'judul', 'obriks', 'lhps', 'temuans', 'wilayah', 'tindakan'));
        } else {
            $obriks = Obrik::where('wilayah_id', Auth::user()->wilayah_id)->get();
            $temuans = Temuans::where('wilayah_id', Auth::user()->wilayah_id)->get();
            return view('pages.tindak_lanjut.edit', compact('title', 'judul', 'obriks', 'lhps', 'temuans', 'tindakan'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'lhp' => 'required',
            'obrik'      => 'required',
            'temuan'      => 'required',
            'rekomendasi'      => 'required',
            'uraian'      => 'required',
            'saran'      => 'required',
        ];

        $messages = [
            'lhp.required'  => 'Tahun wajib diisi',
            'obrik.required' => 'Obrik wajib dipilih',
            'temuan.required' => 'Temuan wajib diisi',
            'rekomendasi.required' => 'Rekomendasi wajib diisi',
            'uraian.required' => 'Uraian wajib diisi',
            'saran.required' => 'Ringkasan wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tindak = TindakLanjut::find($id);
        $oldFilename = $tindak->file;

        if ($request->hasFile('file')) {
            // Delete the old file
            File::delete(public_path("uploads/" . $oldFilename));

            // Upload and save the new file
            $newFilename = str_replace(' ', '_', $request->file('file')->getClientOriginalName());
            $newFilename = $newFilename . '_' . time() . '.' . $request->file('file')->extension();
            $request->file('file')->move(public_path('uploads'), $newFilename);

            $tindak->file = $newFilename;
        } else {
            $newFilename = $oldFilename;
        }

        $idTemuan = $request->temuan;
        $temuan = Temuans::find($idTemuan);
        $nilaiTemuan = $temuan->nilai_temuan;

        $tindak->update([
            'lhp_id' => $request->lhp,
            'obrik_id' => $request->obrik,
            'temuan_id' => $request->temuan,
            'nilai_temuan' => $nilaiTemuan,
            'rekomendasi' => $request->rekomendasi,
            'uraian' => $request->uraian,
            'saran' => $request->saran,
            'file' => $newFilename,
            'status' => $request->status,
        ]);

        return redirect()->route('tindakan')
            ->with('success', 'Tindakan Updated Successfully');
    }

    public function status($id)
    {
        $status = TindakLanjut::find($id);
        $status->update([
            'status' => 1,
        ]);
        return redirect()->route('tindakan')
            ->with('success', 'Data Berhasil Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        TindakLanjut::find($id)->delete();
        return redirect()->route('tindakan')
            ->with('error', 'Temuan Deleted Successfully');
    }
}
