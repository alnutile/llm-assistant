<?php

namespace Tests\Feature;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_get_body()
    {
        $text = <<<'EOD'
https://www.linkedin.com/posts/-ericlbarnes_php-activity-7091570312579846144-ScFR?utm_source=share&utm_medium=member_android

Get Outlook for Android
EOD;

        $results = get_url_from_body($text);

        $this->assertEquals(
            'https://www.linkedin.com/posts/-ericlbarnes_php-activity-7091570312579846144-ScFR?utm_source=share&utm_medium=member_android',
            $results
        );

    }
}
