<?php

namespace App\Livewire\Admin;

use App\Models\GuestReport;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ViewFireReports extends Component
{
    use WithPagination;

    // Properti untuk filter dan pencarian
    public $search = '';
    public $filterReportStatus = '';
    public $filterFireStatus = '';

    // Properti untuk Modal Konfirmasi Hapus
    public $reportIdToDelete = null;
    public $isConfirmingDelete = false;

    // Reset paginasi saat filter atau pencarian berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatedFilterReportStatus() { $this->resetPage(); }
    public function updatedFilterFireStatus() { $this->resetPage(); }

    /**
     * Merender komponen dan mengambil data laporan.
     */
    public function render()
    {
        // Query untuk mengambil laporan dengan filter dan pencarian
        $reports = GuestReport::query()
            ->with('images') // Eager load relasi untuk performa (opsional jika tak ada thumbnail)
            ->when($this->search, function ($query) {
                // Grupkan kondisi pencarian agar tidak mengganggu filter
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('contact', 'like', '%' . $this->search . '%')
                      ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterReportStatus, function ($query) {
                $query->where('report_status', $this->filterReportStatus);
            })
            ->when($this->filterFireStatus, function ($query) {
                $query->where('fire_status', $this->filterFireStatus);
            })
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10); // Atur jumlah item per halaman

        return view('livewire.admin.view-fire-reports', [
            'reports' => $reports,
        ])->layout('layout.mainLayout'); // Pastikan path layout benar
    }

    /**
     * Mengupdate status verifikasi laporan (Diterima/Ditolak).
     */
    public function updateReportStatus($id, $status)
    {
        $report = GuestReport::findOrFail($id);

        $report->report_status = $status;

        // Jika laporan diterima, otomatis set status kebakaran menjadi "Dalam Penanganan"
        if ($status == 'Diterima') {
            if ($report->fire_status != 'Selesai') {
                $report->fire_status = 'Dalam Penanganan';
            }
        }
        
        $report->save();

        session()->flash('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Membuka modal konfirmasi sebelum menghapus.
     */
    public function confirmDelete($id)
    {
        $this->reportIdToDelete = $id;
        $this->isConfirmingDelete = true;
    }
    
    public function closeConfirmModal()
    {
        $this->isConfirmingDelete = false;
        $this->reportIdToDelete = null;
    }

    public function deleteReport()
    {
        $report = GuestReport::findOrFail($this->reportIdToDelete);

        if ($report->images->isNotEmpty()) {
            foreach ($report->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete(); 
            }
        }

        $report->delete(); 

        session()->flash('error', 'Laporan telah ditolak dan dihapus.');
        
        $this->closeConfirmModal();
    }

    
}