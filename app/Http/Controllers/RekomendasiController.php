<?php

namespace App\Http\Controllers;

use App\DataTables\RekomendasiesDataTable;
use App\Models\Rekomendasies;
use App\Models\Temuans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RekomendasiController extends Controller
{
    // public function index(RekomendasiesDataTable $dataTable, $id)
    // {
    //     $title = 'Rekomendasi Temuan';
    //     $judul = 'Rekomendasi Temuan';
    //     // $rekomen = Temuans::find($id)->first();
    //     return $dataTable->with('id', $id)->render('pages.temuan.rekomendasi', compact('title', 'judul'));
    // }

    public function index($id)
    {
        $rekomendasi = Rekomendasies::with(['obrik', 'lhp', 'temuan', 'penanggung'])
            ->whereHas('temuan', function ($query) use ($id) {
                $query->where('temuan_id', $id);
            })
            ->get();
        $nilaiRekom = Rekomendasies::select(
            DB::raw('(SELECT SUM(CASE WHEN rekomendasies.temuan_id = temuans.id THEN rekomendasies.nilai_rekomendasi ELSE 0 END)) as total_rekomendasi'),

        )
            ->where('rekomendasies.temuan_id', $id)
            ->join('temuans', 'rekomendasies.temuan_id', '=', 'temuans.id')
            ->first();
        $rekomen = Temuans::find($id);
        $title = 'Rekomendasi Temuan';
        $judul = 'Rekomendasi Temuan';
        return view('pages.temuan.rekomendasi', compact('title', 'judul', 'rekomendasi', 'rekomen', 'nilaiRekom'));
    }

    public function create($id)
    {
        $title = 'Tambah Rekomendasi Temuan';
        $judul = 'Tambah Rekomendasi Temuan';
        $rekomen = Temuans::find($id);
        $nilaiRekom = Rekomendasies::select(
            DB::raw('(SELECT SUM(CASE WHEN rekomendasies.temuan_id = temuans.id THEN rekomendasies.nilai_rekomendasi ELSE 0 END)) as total_rekomendasi'),

        )
            ->where('rekomendasies.temuan_id', $id)
            ->join('temuans', 'rekomendasies.temuan_id', '=', 'temuans.id')
            ->first();

        $sisaRekom = $rekomen->nilai_temuan - $nilaiRekom->total_rekomendasi;
        return view('pages.temuan.rekomendasi_add', compact('title', 'judul', 'rekomen', 'sisaRekom', 'nilaiRekom'));
    }

    public function store(Request $request, $id)
    {
        $rules = [
            'rekomendasi'      => 'required',
            'nilai_rekomendasi'      => 'required',
        ];

        $messages = [
            'rekomendasi.required' => 'Rekomendasi wajib diisi',
            'nilai_rekomendasi.required' => 'Nilai Rekomendasi wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Rekomendasies::create([
            'wilayah_id' => $request->wilayah,
            'temuan_id' => $id,
            'obrik_id' => $request->obrik,
            'lhp_id' => $request->lhp,
            'rekomendasi' => $request->rekomendasi,
            'nilai_rekomendasi' => $request->nilai_rekomendasi,
        ]);

        return redirect()->route('rekomendasi', $id)
            ->with('success', 'Rekomendasi Created Successfully');
    }
}
