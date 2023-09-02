<?php

namespace App\Domains\Scraping;

use Illuminate\Support\Facades\Http;

class RapidScrapeClient
{
    public function handle(string $url): string
    {

        if(config('services.rapid.mock')) {
            return "Perfection is Achieved Not When There Is Nothing More to Add, But When There Is Nothing Left to Take Away - Antoine de Saint-Exuper";
        }

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
