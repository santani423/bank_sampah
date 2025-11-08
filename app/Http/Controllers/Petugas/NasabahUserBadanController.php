<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NasabahUserBadanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        // dd($nasabahs);
        return view('pages.petugas.nasabah-badan.index');
    }
}
