<?php

namespace App\Tools;

use Facades\App\Domains\Scraping\RapidScrapeClient;

class GetSiteWrapper
{
    public function handle(string $url): string|null
    {


        return RapidScrapeClient::handle($url);
    }
}
