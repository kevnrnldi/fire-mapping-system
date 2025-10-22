<?php

namespace App\Http\Controllers;
use App\Models\EducationArticle;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    //
     public function show($id)
    {
        // Cari artikel berdasarkan ID yang dilewatkan dari URL,
        // jika tidak ditemukan, akan menampilkan error 404.
        $article = EducationArticle::findOrFail($id);

        return view('livewire.user.detail',['article' => $article]);
    }
}
