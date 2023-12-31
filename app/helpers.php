<?php

use App\Models\Message;
use App\OpenAi\Dtos\FunctionCallDto;
use Facades\App\Domains\LlmFunctions\ContentToVoice\ContentToVoice;
use Facades\App\Domains\LlmFunctions\GetContentFromUrl\GetContentFromUrl;
use Facades\App\Domains\LlmFunctions\GetExistingTags\GetExistingTags;
use Facades\App\Domains\LlmFunctions\TagArticle\TagArticle;
use Facades\App\Domains\LlmFunctions\TextToImage\TextToImage;
use Facades\App\Domains\Scheduling\TaskRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

if (! function_exists('llm_functions_scheduling')) {
    function llm_functions_scheduling(
        FunctionCallDto $functionCallDto
    ): message {

        logger('Message coming in ', $functionCallDto->toArray());

        return TaskRepository::handle($functionCallDto);
    }
}

if (! function_exists('text_to_image')) {
    function text_to_image(
        FunctionCallDto $functionCallDto
    ): Message {
        return TextToImage::handle($functionCallDto);
    }
}

if (! function_exists('content_to_voice')) {
    function content_to_voice(
        FunctionCallDto $functionCallDto
    ): Message {
        return ContentToVoice::handle($functionCallDto);
    }
}

if (! function_exists('get_existing_tags')) {
    function get_existing_tags(
        FunctionCallDto $functionCallDto
    ): Message {
        return GetExistingTags::handle($functionCallDto);
    }
}

if (! function_exists('add_tags_to_article')) {
    function add_tags_to_article(
        FunctionCallDto $functionCallDto
    ): Message {
        return TagArticle::handle($functionCallDto);
    }
}

if (! function_exists('get_content_from_url')) {
    function get_content_from_url(
        FunctionCallDto $functionCallDto
    ): Message {
        return GetContentFromUrl::handle($functionCallDto);
    }
}

if (! function_exists('format_text_for_message')) {
    function format_text_for_message(string $content): string
    {

        $width = 80;
        $wrappedText = wordwrap($content, $width, "\n", true);

        return str($wrappedText)->markdown();
    }
}

if (! function_exists('get_fixture_v2')) {
    function get_fixture_v2($file_name, bool $json = true)
    {
        $results = File::get(base_path(sprintf(
            'tests/fixtures/%s',
            $file_name
        )));

        if (! $json) {
            return $results;
        }

        return json_decode($results, true);
    }
}

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
