<?php

namespace Tests\Feature;

use Facades\App\Domains\LlmFunctions\ContentToVoice\ContentToVoiceClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContentToVoiceClientTest extends TestCase
{

    public function test_results()
    {
        Http::preventStrayRequests();
        $content = get_fixture_v2('content_to_voice_response_first.json');
        $url = get_fixture_v2('content_to_voice_complete.json');
        Http::fake([
            'large-text-to-speech.p.rapidapi.com/*' =>
            Http::sequence()
                ->push($content)
                ->push($url)
        ]);
        $url = 'Some long amount of text here';
        $results = ContentToVoiceClient::handle($url);
        Http::assertSentCount(2);
        $this->assertEquals('https://s3.eu-central-1.amazonaws.com/tts-download/0964685040735b7f5890626d1082a918.wav?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAZ3CYNLHHVKA7D7Z4%2F20230902%2Feu-central-1%2Fs3%2Faws4_request&X-Amz-Date=20230902T130513Z&X-Amz-Expires=86400&X-Amz-SignedHeaders=host&X-Amz-Signature=280d75663ebd371d9a48e85551613d0d40e14661fb84152806c5287d5199fd9a',  $results);
    }
}
