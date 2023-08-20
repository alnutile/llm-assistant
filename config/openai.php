<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),
    'mock' => env('OPENAI_MOCK', true),
    'chat_model' => env('OPENAI_CHAT_CLIENT_MODEL', 'gpt-4'),
    'temperature' => 0.5,
    'max_completion_size' => env('OPENAI_MAX_COMPLETION_SIZE', 8000),
    'max_question_size' => env('OPENAI_MAX_QUESTION_SIZE', 5000),
    'max_response_size' => env('OPENAI_MAX_RESPONSE_SIZE', 1000),
];
