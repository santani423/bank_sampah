<?php

namespace App\Http\Controllers;

use App\Models\kategoriNews;
use App\Models\Kegiatan;
use App\Models\news;
use Illuminate\Http\Request;

class landingPageController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function kegiatan()
    {   
        $kegiatan   = Kegiatan::all();
        return view('landingPage.kegiatan', compact('kegiatan'));
    }

    public function about()
    {
        return view('landingPage.about');
    }
    public function berita()
    {
        $kategoriNews = kategoriNews::all();
        $news = news::all()->where('is_published', true)->sortByDesc('published_at');
        // dd($kategoriNews);
        return view('landingPage.berita.index', compact('kategoriNews','news'));
    }
}
