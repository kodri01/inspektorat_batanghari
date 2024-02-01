<?php

namespace App\Http\Controllers;

use App\DataTables\TemuansDataTable;
use App\Models\Lhp;
use App\Models\Obrik;
use App\Models\PenanggungJawabs;
use App\Models\Rekomendasies;
use App\Models\Temuans;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class TemuansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        # code...
        $this->middleware(['permission:temuan-list|temuan-create|temuan-edit|temuan-delete']);
    }

    public function index(TemuansDataTable $dataTable)
    {
        $title = 'Data Temuan';
        $judul = 'Data Temuan';
        return $dataTable->render('pages.temuan.index', compact('title', 'judul'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Data Temuan';
        $judul = 'Tambah Data Temuan';

        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
        $role = Role::where('id', $modelrole->role_id)->first();

        if ($role->name == 'superadmin') {
            $lhps = Lhp::get();
            $obriks = Obrik::get();
            $wilayah = Wilayah::get();
            return view('pages.temuan.add', compact('title', 'judul', 'obriks', 'lhps', 'wilayah'));
        } else {
            $lhps = Lhp::get();
            $obriks = Obrik::where('wilayah_id', Auth::user()->wilayah_id)->get();
            return view('pages.temuan.add', compact('title', 'judul', 'obriks', 'lhps'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'lhp'     => 'required',
            'jns_pemeriksaan'     => 'required',
            'obrik'      => 'required',
            'ringkasan'      => 'required',
            'nilai_temuan'      => 'required',
            'jns_temuan'      => 'required',
            'rekomendasi'      => 'required',
            'nilai_rekomendasi'      => 'required',
        ];

        $messages = [
            'lhp.required'       => 'Nomor & Tahun LHP Wajib diisi',
            'jns_pemeriksaan.required'  => 'Jenis Pemeriksaan Wajib diisi',
            'obrik.required' => 'Obrik wajib dipilih',
            'ringkasan.required' => 'Ringkasan wajib diisi',
            'nilai_temuan.required' => 'Nilai Temuan wajib diisi',
            'jns_temuan.required' => 'Jenis Temuan wajib diisi',
            'rekomendasi.required' => 'Rekomendasi wajib diisi',
            'nilai_rekomendasi.required' => 'Nilai Rekomendasi wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $temuan = Temuans::create([
            'wilayah_id' => $request->wilayah,
            'obrik_id' => $request->obrik,
            'lhp_id' => $request->lhp,
            'jns_pemeriksaan' => $request->jns_pemeriksaan,
            'ringkasan' => $request->ringkasan,
            'nilai_temuan' => $request->nilai_temuan,
            'jns_temuan' => $request->jns_temuan,
        ]);

        $obrikId = $request->obrik;
        $nilaiObrik = $request->nilai_rekomendasi;
        $jmlPersonil = $request->jml_personil;

        if ($jmlPersonil !== null) {
            $personilData = [];

            for ($i = 1; $i <= $jmlPersonil; $i++) {
                $personilData[] = [
                    'temuan_id' => $temuan->id,
                    'name' => $request->input("name{$i}"),
                    'nip' => $request->input("nip{$i}"),
                    'nilai' => $request->input("nilai{$i}"),
                    'created_at' =>  now($i),
                    'updated_at' =>  now($i),
                ];
            }

            // Simpan data personil ke dalam tabel PenanggungJawabs
            PenanggungJawabs::insert($personilData);
        } else {
            // Jika tidak ada personil, tetap simpan data dengan nilai obrik
            PenanggungJawabs::create([
                'temuan_id' => $temuan->id,
                'obrik_id' => $obrikId,
                'nilai_obrik' => $nilaiObrik,
            ]);
        }

        Rekomendasies::create([
            'wilayah_id' => $request->wilayah,
            'temuan_id' => $temuan->id,
            'obrik_id' => $request->obrik,
            'lhp_id' => $request->lhp,
            'rekomendasi' => $request->rekomendasi,
            'nilai_rekomendasi' => $request->nilai_rekomendasi,
        ]);

        return redirect()->route('temuan')
            ->with('success', 'Temuan Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Temuans $temuans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
        $role = Role::where('id', $modelrole->role_id)->first();

        $title = 'Edit Data Temuan';
        $judul = 'Edit Data Temuan';
        $temuan = Temuans::find($id);
        $personil = $temuan->penanggungJawabs;
        $lhps = Lhp::get();


        if ($role->name == 'superadmin') {
            $obriks = Obrik::get();
            return view('pages.temuan.edit', compact('title', 'judul', 'personil', 'lhps', 'obriks', 'temuan'));
        } else {
            $obriks = Obrik::where('wilayah_id', Auth::user()->wilayah_id)->get();
            return view('pages.temuan.edit', compact('title', 'judul', 'personil', 'lhps', 'obriks', 'temuan'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'lhp' => 'required',
            'jns_pemeriksaan'     => 'required',
            'obrik'      => 'required',
            'ringkasan'      => 'required',
            'nilai_temuan'      => 'required',
            'jns_temuan'      => 'required',
            'rekomendasi'      => 'required',
            'nilai_rekomendasi'      => 'required',
        ];

        $messages = [
            'lhp.required'  => 'Nomor & Tahun wajib diisi',
            'jns_pemeriksaan.required'  => 'Jenis Pemeriksaan Wajib diisi',
            'obrik.required' => 'Obrik wajib dipilih',
            'ringkasan.required' => 'Ringkasan wajib diisi',
            'nilai_temuan.required' => 'Nilai Temuan wajib diisi',
            'jns_temuan.required' => 'Jenis Temuan wajib diisi',
            'rekomendasi.required' => 'Rekomendasi wajib diisi',
            'nilai_rekomendasi.required' => 'Nilai Rekomendasi wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $temuan = Temuans::find($id);
        $temuan->update([
            'obrik_id' => $request->obrik,
            'lhp_id' => $request->lhp,
            'jns_pemeriksaan' => $request->jns_pemeriksaan,
            'ringkasan' => $request->ringkasan,
            'nilai_temuan' => $request->nilai_temuan,
            'jns_temuan' => $request->jns_temuan,
            'rekomendasi' => $request->rekomendasi,
            'nilai_rekomendasi' => $request->nilai_rekomendasi,
            'status' => $request->status,
        ]);

        $tanggung = PenanggungJawabs::where('temuan_id', $temuan->id)->first();

        if ($tanggung->nilai_obrik != null) {

            PenanggungJawabs::updateOrCreate(
                ['temuan_id' => $temuan->id],
                [
                    'obrik_id' => $request->obrik,
                    'nilai_obrik' => $request->nilai_obrik,
                ]
            );
        } else {
            $personil = $request->input('name');

            foreach ($personil as $key => $name) {
                $nilai = $request->input('nilai.' . $key);
                PenanggungJawabs::updateOrCreate(
                    ['temuan_id' => $temuan->id, 'name' => $name],
                    ['nilai' => $nilai]
                );
            }
        }

        return redirect()->route('temuan')
            ->with('success', 'Temuan Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function status($id)
    {
        $status = Temuans::find($id);
        $status->update([
            'status' => 1,
        ]);
        return redirect()->route('temuan')
            ->with('success', 'Data Berhasil Disimpan');
    }

    public function destroy($id)
    {
        PenanggungJawabs::where('temuan_id', $id)->delete();
        Temuans::find($id)->delete();
        return redirect()->route('temuan')
            ->with('error', 'Temuan Deleted Successfully');
    }
}
