<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $entries = [
            ['url' => url('/'),         'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => url('/products'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => url('/repair'),   'priority' => '0.9', 'changefreq' => 'monthly'],
        ];

        Category::where('is_active', true)->get()->each(function ($c) use (&$entries) {
            $entries[] = [
                'url' => url("/products?category={$c->slug}"),
                'priority' => '0.7',
                'changefreq' => 'weekly',
            ];
        });

        Product::published()->get(['slug', 'updated_at'])->each(function ($p) use (&$entries) {
            $entries[] = [
                'url' => url("/products/{$p->slug}"),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $p->updated_at->toAtomString(),
            ];
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        foreach ($entries as $e) {
            $xml .= '  <url>'.PHP_EOL;
            $xml .= '    <loc>'.htmlspecialchars($e['url']).'</loc>'.PHP_EOL;
            if (isset($e['lastmod'])) $xml .= '    <lastmod>'.$e['lastmod'].'</lastmod>'.PHP_EOL;
            $xml .= '    <changefreq>'.$e['changefreq'].'</changefreq>'.PHP_EOL;
            $xml .= '    <priority>'.$e['priority'].'</priority>'.PHP_EOL;
            $xml .= '  </url>'.PHP_EOL;
        }
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
