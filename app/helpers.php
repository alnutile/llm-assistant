<?php

if (! function_exists('test_helper')) {
    function test_helper()
    {
        return 'foo';
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
