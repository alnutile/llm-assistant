<?php

namespace App\Domains\LlmFunctions\ContentToVoice;

use Illuminate\Support\Facades\Http;

class ContentToVoiceClient
{
    public function handle(string $longContent): string
    {
        $token = config('services.rapid.api');
        if (! $token) {
            throw new \Exception('Missing token');
        }

        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $token,
            'X-RapidAPI-Host' => 'large-text-to-speech.p.rapidapi.com',
        ])
            ->timeout(60)
            ->retry(3, 1500)
            ->post('https://large-text-to-speech.p.rapidapi.com/tts', [
                'text' => $longContent,
            ]);

        /**
         * Now it has to keep trying till it is done or broke
         * tests/fixtures/content_to_voice_response_first.json
         */
        logger('Results from voice to text', [
            $response->json(),
        ]);

        $status = $response->json()['status'];
        $jobId = $response->json()['id'];
        $url = null;

        while ($status === 'processing') {
            $response = $this->getStatus($jobId);
            $status = $response['status'];
            $jobId = $response['id'];
            $url = data_get($response, 'url', null);
        }

        if ($url === null) {
            throw new \Exception('Could not convert to audio');
        }

        return $url;
    }

    protected function getStatus(string $job_id): array
    {
        logger('Checking status of voice conversion');
        $token = config('services.rapid.api');
        if (! $token) {
            throw new \Exception('Missing token');
        }

        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $token,
            'X-RapidAPI-Host' => 'large-text-to-speech.p.rapidapi.com',
        ])
            ->timeout(60)
            ->retry(3, 1500)
            ->get('https://large-text-to-speech.p.rapidapi.com/tts', [
                'id' => $job_id,
            ]);

        logger('Status '.$response->json()['status']);

        return $response->json();

    }
}
