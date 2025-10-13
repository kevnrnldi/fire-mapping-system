<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Edukasi;
use App\Livewire\AdminMap;
use App\Livewire\Akun;
use App\Livewire\Dashboard;
use App\Livewire\Laporan;
use App\Livewire\Admin\ViewFireReports;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\ChangePassword;
use App\Livewire\User\Berandauser;




Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard',Dashboard::class)->name('admin.dashboard');
    Route::get('/admin/pemetaan', AdminMap::class)->name('admin.map');
    Route::get('/admin/laporan', ViewFireReports::class)->name('admin.laporan');
    Route::get('/admin/pengaturan/akun', Akun::class)->name('admin.akun');
    Route::get('/admin/edukasi', Edukasi::class)->name('admin.edukasi');
    Route::get('/admin/pengaturan/reset-password', ChangePassword::class)->name('admin.change-password');
});

Route::redirect('/', '/login');
Route::get('/login', Login::class)->name('login');
Route::get('/laporan', Laporan::class)->name('laporan');
Route::get('/beranda', Berandauser::class)->name('beranda');