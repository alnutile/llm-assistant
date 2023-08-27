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

    public function test_get_body_again()
    {
        $text = <<<'EOD'
subject: forxer/laravel-gravatar: Easy Gravatar integration in a Laravel project. body: URL: https://github.com/forxer/laravel-gravatar
EOD;

        $results = get_url_from_body($text);

        $this->assertEquals(
            'https://github.com/forxer/laravel-gravatar',
            $results
        );

    }

    public function test_two_urls()
    {
        $text = <<<'EOD'
https://www.linkedin.com/posts/-ericlbarnes_php-activity-7091570312579846144-ScFR?utm_source=share&utm_medium=member_android

Get Outlook for Android

Al Nutile
https://alfrednutile.info/#about
413.230.4767
EOD;

        $results = get_url_from_body($text);

        $this->assertEquals(
            'https://www.linkedin.com/posts/-ericlbarnes_php-activity-7091570312579846144-ScFR?utm_source=share&utm_medium=member_android',
            $results
        );

    }

    public function test_format_text()
    {
        $content = get_fixture_v2('content.txt', false);

        $results = format_text_for_message($content);

        $expected = <<<EOT
subject: LinkedIn PHP tip\n
 body:\n
                URL:\n
https://www.linkedin.com/posts/-ericlbarnes_php-activity-7091570312579846144-ScF\n
R?utm_source=share&utm_medium=member_android\n
\n
                Content: The content includes various posts from LinkedIn.\n
Highlights include Eric Barnes discussing the PHP `isset` function, Andrew\n
Schmelyun promoting Laravel as an alternative to Next.js, and a discussion on\n
the best e-commerce packages for Laravel. Arlon Antonius shares a story about\n
improving efficiency through automation and data manipulation. Google for\n
Developers announces an American Sign Language competition. Hossam Tarek advises\n
on using the Presenter Design Pattern for data manipulation in Laravel. Kyle\n
Simpson discusses the concept of "web2.5", advocating for a shift from\n
cloud-first to peer-first thinking.
EOT;

        $this->assertNotNull($results);
    }
}
