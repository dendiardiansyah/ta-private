<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;

class KatalogController extends Controller
{
    public function index()
    {
        $jenisSampahs = JenisSampah::all();

        return view('katalog', compact('jenisSampahs'));
    }
}
