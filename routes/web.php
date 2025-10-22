<?php

use App\Http\Controllers\Admin\ReportDetailController;
use App\Http\Controllers\EducationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Livewire\Edukasi;
use App\Livewire\AdminMap;
use App\Livewire\Akun;
use App\Livewire\Dashboard;
use App\Livewire\Laporan;
use App\Livewire\Admin\ViewFireReports;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\ChangePassword;
use App\Livewire\User\Edukasis;
use App\Livewire\User\Berandauser;




Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard',Dashboard::class)->name('admin.dashboard');
    Route::get('/admin/pemetaan', AdminMap::class)->name('admin.map');
    Route::get('/admin/laporan', ViewFireReports::class)->name('admin.laporan');
    Route::get('/admin/pengaturan/akun', Akun::class)->name('admin.akun');
    Route::get('/admin/edukasi', Edukasi::class)->name('admin.edukasi');
    Route::get('/admin/pengaturan/reset-password', ChangePassword::class)->name('admin.change-password');
    Route::get('/laporan/{reportId}', [ReportDetailController::class, 'show'])->name('admin.laporan.show');
    Route::patch('/laporan/{report}/update-fire-status', [ReportDetailController::class, 'updateFireStatus'])->name('admin.laporan.updateFireStatus');
});

Route::redirect('/', '/login');
Route::get('/login', Login::class)->name('login');
Route::get('/laporan', Laporan::class)->name('laporan');
Route::get('/beranda', Berandauser::class)->name('beranda');
Route::get('/edukasi', Edukasis::class)->name('edukasi');
Route::get('/edukasi/{id}', [EducationController::class, 'show'])->name('edukasi.show');



Route::get('/ambil-alamat', function (Request $request) {
    // Ambil latitude dan longitude dari parameter query (?lat=...&lon=...)
    $latitude = $request->query('lat');
    $longitude = $request->query('lon');

    // Validasi sederhana (pastikan ada dan berupa angka)
    if (!$latitude || !$longitude || !is_numeric($latitude) || !is_numeric($longitude)) {
        return response()->json(['error' => 'Koordinat tidak valid'], 400);
    }

    // PENTING: Nominatim mewajibkan header User-Agent yang jelas
    // Ganti 'NamaAplikasiAnda/1.0' dan 'email@anda.com'
    $userAgent = config('app.name') . 'PetaKebakaran/1.0 (' . config('app.url') . '; mailto:mkevinrinaldi@gmail.com)'; // Contoh User-Agent Dinamis

    // Minta data ke Nominatim dari server Laravel
    $response = Http::withHeaders(['User-Agent' => $userAgent])
                    ->get('https://nominatim.openstreetmap.org/reverse', [
                        'format'    => 'json',
                        'lat'       => $latitude,
                        'lon'       => $longitude,
                        'accept-language' => 'id', 
                        'addressdetails' => 1      
                    ]);

    // Cek apakah permintaan ke Nominatim berhasil
    if ($response->successful()) {
        return $response->json(); 
    } else {
        // Jika gagal, catat error dan kirim pesan error ke JavaScript
        \Illuminate\Support\Facades\Log::error('Gagal ambil data Nominatim: ' . $response->body());
        return response()->json(['error' => 'Gagal mengambil alamat dari server peta'], $response->status());
    }
})->name('ambil.alamat');