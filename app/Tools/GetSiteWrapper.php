<?php

namespace App\Tools;

use App\Spiders\GetPageSpider;
use Illuminate\Support\Arr;
use RoachPHP\ItemPipeline\Item;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;

class GetSiteWrapper
{
    public function handle(string $url): string|null
    {

        /** @var Item[] $items */
        $items = Roach::collectSpider(
            GetPageSpider::class,
            new Overrides(startUrls: [$url])
        );

        $item = Arr::first($items);

        if ($item) {
            return $item->get(0);
        }

        return null;
    }
}
