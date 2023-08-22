<?php

use Illuminate\Support\Arr;

if (! function_exists('test_helper')) {
    function test_helper()
    {
        return 'foo';
    }
}

if (! function_exists('get_url_from_body')) {
    function get_url_from_body(string $body): string|null
    {
        $pattern = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';
        if (preg_match($pattern, $body, $match)) {
            return Arr::first($match);
        }

        return null;
    }
}

if (! function_exists('get_current_weather')) {
    function get_current_weather($location, $unit = 'fahrenheit'): string
    {
        logger('Params', [
            $location,
            $unit,
        ]);

        return '65 degrees and cloudy';
    }
}
