<?php

namespace App\Console\Commands;

use App\Spiders\GetPageSpider;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use RoachPHP\ItemPipeline\Item;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;

class RoachSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roach:example {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pass url to see it work';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Roach::collectSpider(
            GetPageSpider::class,
            new Overrides(startUrls: [$this->argument('url')])
        );

        $item = Arr::first($items);
        dd($item->get(0));
    }
}
