<?php

namespace App\Domains\Scraping;

use Illuminate\Support\Facades\Http;

class RapidScrapeClient
{
    public function handle(string $url): string
    {
        $token = config('services.rapid.api');
        if (! $token) {
            throw new \Exception('Missing token');
        }

        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $token,
            'X-RapidAPI-Host' => 'proxycrawl-scraper.p.rapidapi.com',
        ])
            ->timeout(60)
            ->retry(3, 1500)
            ->get('https://proxycrawl-scraper.p.rapidapi.com/', [
                'url' => $url,
            ]);

        $body = $response->json();
        $content = data_get($body, 'body.content');

        return $content;
    }
}
