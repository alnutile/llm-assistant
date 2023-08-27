<?php

namespace App\Tools;

use Facades\App\Domains\Scraping\RapidScrapeClient;
use App\Spiders\GetPageSpider;
use Illuminate\Support\Arr;
use RoachPHP\ItemPipeline\Item;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;

class GetSiteWrapper
{
    public function handle(string $url): string|null
    {

        return RapidScrapeClient::handle($url);
    }
}
