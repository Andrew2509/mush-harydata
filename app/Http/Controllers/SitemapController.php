<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $categories = Kategori::where('status', 'active')->get();
        
        $urls = [
            [
                'loc' => url('/id'),
                'priority' => '1.00',
                'lastmod' => now()->startOfDay()->toAtomString(),
            ],
            [
                'loc' => url('/id/leaderboard'),
                'priority' => '0.80',
                'lastmod' => now()->startOfDay()->toAtomString(),
            ],
            [
                'loc' => url('/id/calculator/winrate'),
                'priority' => '0.80',
                'lastmod' => now()->startOfDay()->toAtomString(),
            ],
            [
                'loc' => url('/id/calculator/magic-wheel'),
                'priority' => '0.80',
                'lastmod' => now()->startOfDay()->toAtomString(),
            ],
            [
                'loc' => url('/id/calculator/zodiac'),
                'priority' => '0.80',
                'lastmod' => now()->startOfDay()->toAtomString(),
            ],
            [
                'loc' => url('/id/reviews'),
                'priority' => '0.64',
                'lastmod' => now()->startOfDay()->toAtomString(),
            ]
        ];

        foreach ($categories as $category) {
            $urls[] = [
                'loc' => url('/id/' . $category->kode),
                'priority' => '0.80',
                'lastmod' => $category->updated_at ? $category->updated_at->toAtomString() : now()->startOfDay()->toAtomString(),
            ];
        }

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
