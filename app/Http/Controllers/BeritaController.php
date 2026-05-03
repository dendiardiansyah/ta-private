<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class BeritaController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }
            if (auth()->user()->hasRole('petugas')) {
                return redirect()->route('petugas.index');
            }
        }

        $apiKey = env('NEWS_API_KEY');
        $response = Http::get("https://newsapi.org/v2/top-headlines", [
            'country' => 'us',
            'category' => 'health',
            'apiKey' => $apiKey,
        ]);

        // Ambil artikel yang memiliki gambar dan batasi hanya 8 artikel
        $articles = collect($response->json()['articles'] ?? [])
            ->filter(fn($a) => !empty($a['urlToImage']))
            ->take(8)
            ->map(function ($article) {
                $encoded = urlencode($article['urlToImage']);
                $article['urlToImage'] = "https://images.weserv.nl/?url={$encoded}&w=600&h=400&fit=cover";
                return $article;
            })
            ->values();

        return view('dashboard', compact('articles'));
    }


}
