<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BeritaController extends Controller
{
    public function index()
    {
        $apiKey = env('NEWS_API_KEY');
        $response = Http::get("https://newsapi.org/v2/top-headlines", [
            'country' => 'us',
            'category' => 'health',
            'apiKey' => $apiKey,
        ]);

        // Ambil artikel yang memiliki gambar dan batasi hanya 8 artikel
        $articles = collect($response->json()['articles'] ?? [])
            ->filter(function ($article) {
                return !empty($article['urlToImage']);
            })
            ->take(8) // Ambil hanya 8 artikel (2 baris x 4 artikel per baris)
            ->values(); // Reindex array setelah filter

        return view('dashboard', compact('articles'));
    }
}
