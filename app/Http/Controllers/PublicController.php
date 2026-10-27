<?php

namespace App\Http\Controllers;

use App\Models\Paud;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::all();
        // We can load PAUDs as JSON directly for the map
        $pauds = Paud::with('kecamatan')->get();
        
        return view('public.peta', compact('kecamatan', 'pauds'));
    }
    
    // Optional: detail page
    public function show(Paud $paud)
    {
        return view('public.detail', compact('paud'));
    }
}
