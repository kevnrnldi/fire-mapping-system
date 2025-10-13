<?php

namespace App\Livewire\Admin;

use App\Models\GuestReport;
use Livewire\WithPagination;
use Livewire\Component;

class ViewFireReports extends Component
{
    public $filterReportStatus;
    public $filterFireStatus;
    public $search = '';
    public $selectedReport;

    public function updatingSearch() { $this->resetPage(); }
    public function updatedFilterReportStatus() { $this->resetPage(); }
    public function updatedFilterFireStatus() { $this->resetPage(); }

    public function render()
    {
           $reports = GuestReport::query() 
            // Logika untuk pencarian
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('contact', 'like', '%' . $this->search . '%')
                             ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            })
            // Logika untuk filter status
            ->when($this->filterReportStatus, fn($q) => $q->where('report_status', $this->filterReportStatus))
            ->when($this->filterFireStatus, fn($q) => $q->where('fire_status', $this->filterFireStatus))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.view-fire-reports', [
            'reports' => $reports,
        ])->layout('layout.mainLayout');
    }

    public function viewReport($reportId)
    {
        $this->selectedReport = GuestReport::findOrFail($reportId); 
        
        // Kirim event ke browser dengan data koordinat
        $this->dispatch('showReportModal', [
            'latitude' => $this->selectedReport->latitude,
            'longitude' => $this->selectedReport->longitude,
        ]);
    }

    public function closeModal()
    {
        $this->selectedReport = null;
    }

    public function updateReportStatus($reportId, $status)
    {
       $report = GuestReport::find($reportId);
       if($report && in_array($status, [ 'Diterima', 'Ditolak'])) {
           $report->report_status = $status;
           if($status == 'Diterima' && $report -> fire_status == 'Sedang Terjadi') {
               $report->fire_status = 'Dalam Penanganan';
           }
           $report->save();
           session()->flash('message', 'Laporan Diperbaharui');
       }
    }
     public function updateFireStatus($reportId, $status)
    {
        $report = GuestReport::find($reportId);
        if ($report && $report->report_status == 'Diterima' && in_array($status, ['Dalam Penanganan', 'Selesai'])) {
            $report->fire_status = $status;
            $report->save();
            session()->flash('message', 'Status kebakaran telah diperbarui.');
        }
    }

}
