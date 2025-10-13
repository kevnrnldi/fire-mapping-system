<?php

namespace App\Livewire;

use App\Models\GuestReport;
use Livewire\Component;
use App\Models\User;
use App\Models\FireReport;
use App\Models\EducationArticle;

class Dashboard extends Component
{
    public function render(){
          
        $newReportsCount = GuestReport::whereIn('report_status', ['Baru', 'Menunggu Verifikasi'])->count();

        $adminCount = User::count();

        $recentReports = GuestReport::latest()->take(5)->get();

        $recentArticles = EducationArticle::latest()->take(3)->get();

        // $users = User::)

        return view('livewire.dashboard', [
            'newReportsCount' => $newReportsCount,
            'adminCount' => $adminCount,
            'recentReports' => $recentReports,
            'recentArticles' => $recentArticles,
        ])->layout('layout.mainLayout'); // Sesuaikan dengan layout admin Anda
    }
}
