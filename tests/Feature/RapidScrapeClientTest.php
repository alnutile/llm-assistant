<?php

namespace Tests\Feature;

use Facades\App\Domains\Scraping\RapidScrapeClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RapidScrapeClientTest extends TestCase
{
    public function test_rapid_client()
    {
        $this->markTestSkipped('Just needed to get payload');

        $url = 'https://proxycrawl-scraper.p.rapidapi.com/';

        $results = RapidScrapeClient::handle($url);

        put_fixture('rapid_api_crawl.json', $results);
        dd($results);
    }

    public function test_results()
    {
        //$this->markTestSkipped("Just needed to get payload");
        Http::preventStrayRequests();
        $content = get_fixture_v2('rapid_api_crawl.json');
        Http::fake([
            'proxycrawl-scraper.p.rapidapi.com/*' => Http::response($content),
        ]);
        $url = 'https://proxycrawl-scraper.p.rapidapi.com/';
        $results = RapidScrapeClient::handle($url);
        Http::assertSentCount(1);
        $this->assertStringContainsString('According to Dictionary.com', $results);
    }

    public function test_504()
    {
        //$this->markTestSkipped("Just needed to get payload");
        Http::preventStrayRequests();
        $content = get_fixture_v2('rapid_api_crawl.json');
        Http::fake([
            'proxycrawl-scraper.p.rapidapi.com/*' => Http::sequence()
                ->push([], 504)
                ->push($content, 200),
        ]);
        $url = 'https://proxycrawl-scraper.p.rapidapi.com/';
        $results = RapidScrapeClient::handle($url);
        Http::assertSentCount(2);
        $this->assertStringContainsString('According to Dictionary.com', $results);
    }
}
