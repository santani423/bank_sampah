<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
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
}
