<?php

namespace App\Http\Controllers;

use App\Models\Obrik;
use App\Models\Temuans;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // public function __construct()
    // {
    //     # code...
    //     $this->middleware(['permission:dashboards']);
    // }

    public function index()
    {
        $title = 'Dashboard LHP Batanghari';
        $judul = 'Dashboard LHP Batanghari';
        $modelrole = DB::table('model_has_roles')->where('model_id', auth()->user()->id)->first();
        $role = Role::where('id', $modelrole->role_id)->first();

        if ($role->name == 'superadmin') {
            $obrik = Obrik::count();
            $temuan = Temuans::count();
            $tindakan = TindakLanjut::count();
            $selesai = TindakLanjut::where('status_tl', 'Selesai')->count();
            $dalamProses = TindakLanjut::where('status_tl', 'Dalam Proses')->count();
            $belum = TindakLanjut::select(
                DB::raw('(SELECT COUNT(id) FROM rekomendasies WHERE rekomendasies.id NOT IN (SELECT rekomendasi_id FROM tindak_lanjuts)) as belum'),
            )->first();
            return view('pages.dashboard.index', compact('title', 'judul', 'obrik', 'temuan', 'tindakan', 'selesai', 'dalamProses', 'belum'));
        } else {
            $obrik = Obrik::where('wilayah_id', Auth::user()->wilayah_id)->count();
            $temuan = Temuans::where('wilayah_id', Auth::user()->wilayah_id)->count();
            $tindakan = TindakLanjut::where('wilayah_id', Auth::user()->wilayah_id)->count();
            $selesai = TindakLanjut::where('wilayah_id', Auth::user()->wilayah_id)->where('status_tl', 'Selesai')->count();
            $dalamProses = TindakLanjut::where('wilayah_id', Auth::user()->wilayah_id)->where('status_tl', 'Dalam Proses')->count();
            $belum = TindakLanjut::select(
                DB::raw('(SELECT COUNT(id) FROM rekomendasies WHERE rekomendasies.id NOT IN (SELECT rekomendasi_id FROM tindak_lanjuts)) as belum'),
            )->where('wilayah_id', Auth::user()->wilayah_id)->first();
            return view('pages.dashboard.index', compact('title', 'judul', 'obrik', 'temuan', 'tindakan', 'selesai', 'dalamProses', 'belum'));
        }
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