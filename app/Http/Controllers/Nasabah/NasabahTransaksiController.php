<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NasabahTransaksiController extends Controller
{
    public function index()
    {
        $saldo = 100;
        // Logic for displaying the transactions
        return view('pages.nasabah.transaksi.index', compact('saldo'));
    }

    public function create()
    {
        // Logic for showing the form to create a new transaction
        return view('pages.nasabah.transaksi.create');
    }
}
