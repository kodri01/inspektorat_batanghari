<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InspekturController;
use App\Http\Controllers\LaporanLhpController;
use App\Http\Controllers\LaporanRekapController;
use App\Http\Controllers\LaporanRekapitulasiController;
use App\Http\Controllers\LaporanRincianController;
use App\Http\Controllers\LhpController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ObrikController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\TemuansController;
use App\Http\Controllers\TindakLanjutController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('tologin');



Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::group(['middleware' => ['permission:dashboards']], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::group(['middleware' => 'role:superadmin'], function () {
        Route::group(['middleware' => ['permission:users-list|users-create|users-edit|users-delete']], function () {
            Route::get('users', [UsersController::class, 'index'])->name('users');
            Route::get('users/add', [UsersController::class, 'create'])->name('users.add');
            Route::post('users', [UsersController::class, 'store'])->name('users.store');
            Route::get('users/edit/{id}', [UsersController::class, 'edit'])->name('users.edit');
            Route::post('users/update/{id}', [UsersController::class, 'update'])->name('users.update');
            Route::post('users/delete/{id}', [UsersController::class, 'destroy'])->name('users.delete');

            Route::get('inspektur', [InspekturController::class, 'index'])->name('inspektur');
            Route::get('inspektur/add', [InspekturController::class, 'create'])->name('inspektur.add');
            Route::post('inspektur', [InspekturController::class, 'store'])->name('inspektur.store');
            Route::get('inspektur/edit/{id}', [InspekturController::class, 'edit'])->name('inspektur.edit');
            Route::post('inspektur/update/{id}', [InspekturController::class, 'update'])->name('inspektur.update');
            Route::post('inspektur/delete/{id}', [InspekturController::class, 'destroy'])->name('inspektur.delete');

            Route::get('obrik', [ObrikController::class, 'index'])->name('obrik');
            Route::get('obrik/add', [ObrikController::class, 'create'])->name('obrik.add');
            Route::post('obrik', [ObrikController::class, 'store'])->name('obrik.store');
            Route::get('obrik/edit/{id}', [ObrikController::class, 'edit'])->name('obrik.edit');
            Route::post('obrik/update/{id}', [ObrikController::class, 'update'])->name('obrik.update');
            Route::post('obrik/delete/{id}', [ObrikController::class, 'destroy'])->name('obrik.delete');
        });
    });

    Route::group(['middleware' => 'role:Irban'], function () {
        Route::get('lhp', [LhpController::class, 'create'])->name('lhp.create');
        Route::post('lhp', [LhpController::class, 'store'])->name('lhp.store');
    });

    Route::group(['middleware' => ['permission:temuan-list|temuan-create|temuan-edit|temuan-delete']], function () {
        Route::get('temuan', [TemuansController::class, 'index'])->name('temuan');
        Route::get('temuan/add', [TemuansController::class, 'create'])->name('temuan.add');
        Route::post('temuan', [TemuansController::class, 'store'])->name('temuan.store');
        Route::get('temuan/edit/{id}', [TemuansController::class, 'edit'])->name('temuan.edit');
        Route::post('temuan/update/{id}', [TemuansController::class, 'update'])->name('temuan.update');
        Route::post('temuan/delete/{id}', [TemuansController::class, 'destroy'])->name('temuan.delete');
        Route::post('temuan/status/{id}', [TemuansController::class, 'status'])->name('temuan.status');

        Route::get('rekomendasi/{id}', [RekomendasiController::class, 'index'])->name('rekomendasi');
        Route::get('rekomendasi/add/{id}', [RekomendasiController::class, 'create'])->name('rekomendasi.add');
        Route::post('rekomendasi{id}', [RekomendasiController::class, 'store'])->name('rekomendasi.store');
    });

    Route::group(['middleware' => ['permission:tindakan-list|tindakan-create|tindakan-edit|tindakan-delete']], function () {
        Route::get('tindakan', [TindakLanjutController::class, 'index'])->name('tindakan');
        Route::get('tindakan/add', [TindakLanjutController::class, 'create'])->name('tindakan.add');
        Route::post('tindakan', [TindakLanjutController::class, 'store'])->name('tindakan.store');
        Route::get('tindakan/edit/{id}', [TindakLanjutController::class, 'edit'])->name('tindakan.edit');
        Route::get('tindakan/proses/{id}', [TindakLanjutController::class, 'show'])->name('tindakan.show');
        Route::post('tindakan/delete/{id}', [TindakLanjutController::class, 'destroy'])->name('tindakan.delete');
        Route::post('tindakan/proses/{id}', [TindakLanjutController::class, 'proses'])->name('tindakan.proses');
        Route::post('tindakan/update/{id}', [TindakLanjutController::class, 'update'])->name('tindakan.update');
        Route::post('tindakan/status/{id}', [TindakLanjutController::class, 'status'])->name('tindakan.status');
    });

    Route::group(['middleware' => ['permission:laporan-list|laporan-create|laporan-edit|laporan-delete']], function () {
        //laporan PHP
        Route::get('laporanPHP', [LaporanLhpController::class, 'index'])->name('laporanPHP');
        Route::get('/laporanPHP/excel', [LaporanLhpController::class, 'excelPHP'])->name('excel_php');
        Route::get('/laporanPHP/pdf', [LaporanLhpController::class, 'pdfPHP'])->name('pdf_php');

        //Laporan Rincian
        Route::get('rincian', [LaporanRincianController::class, 'index'])->name('laporan_rincian');
        Route::get('/rincian/excel', [LaporanRincianController::class, 'excelRincian'])->name('excel_rincian');
        Route::get('/rincian/pdf', [LaporanRincianController::class, 'pdfRincian'])->name('pdf_rincian');

        //Laporan Rekap
        Route::get('rekap', [LaporanRekapController::class, 'index'])->name('laporan_rekap');
        Route::get('/rekap/excel', [LaporanRekapController::class, 'excel_rekap'])->name('excel_rekap');
        Route::get('/rekap/pdf', [LaporanRekapController::class, 'pdf_rekap'])->name('pdf_rekap');

        //Laporan Rekapitulasi
        Route::get('rekapitulasi', [LaporanRekapitulasiController::class, 'index'])->name('laporan_rekapitulasi');
        Route::get('/rekapitulasi/excel', [LaporanRekapitulasiController::class, 'excel_rekapitulasi'])->name('excel_rekapitulasi');
        Route::get('/rekapitulasi/pdf', [LaporanRekapitulasiController::class, 'pdf_rekapitulasi'])->name('pdf_rekapitulasi');
    });
});
