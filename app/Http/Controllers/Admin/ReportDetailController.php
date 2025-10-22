<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuestReport;
use Illuminate\Support\Facades\Log;

class ReportDetailController extends Controller
{
    public $reportJson;

    public function mount()
    {
        // Ambil semua laporan yang memiliki latitude DAN longitude
        $reports = GuestReport::whereNotNull('latitude')
                            ->whereNotNull('longitude')
                            ->get([
                                'latitude', 
                                'longitude', 
                                'location', // Untuk popup
                                'status'    // Untuk popup
                            ]);
        
        // Ubah koleksi data menjadi string JSON
        $this->reportsJson = $reports->toJson();
    }




    public function show ( $reportId) {
        $report = GuestReport::with('images')->find($reportId); 

    if (!$report) {
        abort(404); // Tampilkan halaman Not Found jika ID tidak ada
    }

    Log::info('Report Data:', $report->toArray()); 
    // dd($report->toArray()); 
    return view('livewire.admin.show', compact('report'));
    }

    public function updateFireStatus(Request $request, GuestReport $report){
        if ($report->report_status !== 'Diterima') {
            return back()->with('error', 'Status kebakaran hanya bisa diubah jika laporan sudah diterima.');
        }

        // Validasi input status (opsional tapi bagus)
        $request->validate([
            'fire_status' => 'required|in:Dalam Penanganan,Selesai', 
        ]);

        $report->fire_status = $request->input('fire_status');
        $report->save();

        return back()->with('success', 'Status kebakaran berhasil diperbarui menjadi ' . $report->fire_status);
    }
}
