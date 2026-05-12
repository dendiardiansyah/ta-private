<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\Product;
use App\Models\Setting;

class KatalogController extends Controller
{
    public function index()
    {
        $jenisSampahs = JenisSampah::all();

        $products = Product::query()
            ->where('is_active', true)
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        $pointRate = Setting::pointRateRupiahPerPoint();

        return view('user.katalog', compact('jenisSampahs', 'products', 'pointRate'));
    }
}
