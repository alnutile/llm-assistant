<?php

namespace App\OpenAi;

use App\Models\Assistant as AssistantModel;
use App\OpenAi\Dtos\AssistantDto;
use App\OpenAi\Dtos\FileUploadDto;
use OpenAI\Laravel\Facades\OpenAI;

/**
 * Make an assistant in Playground
 * https://platform.openai.com/playground
 * Then take that asst_ID and use it in the
 * config file to start making threads.
 */
class Assistant
{
    protected AssistantModel $assistant;

    /**
     * @NOTE
     * This means we have the thread created already
     * and file uploaded etc
     *
     * @param AssistantModel $assistant
     * @return void
     */
    public function converse(AssistantModel $assistant)
    {
    }

    public function newsAssistant(Assistant $assistant): self
    {

        $assistant_id = config('openai.assistants.news_assistant_id');

        if (empty($assistant_id)) {
            throw new \Exception("Not registered an assistant id");
        }


        /**
         *
         * @TODO
         * If not we need to
         * 1) get the export file
         * 2) upload it and get that id for later
         * 3) create thread with file_ids for that assistant
         * 4) register that thread_id with the assistant model
         * 5) then start the conversation using that model
         *
         */

        return $this;
    }

    public function uploadFile(string $full_path_to_file): FileUploadDto
    {

        if (config('openai.mock') && ! app()->environment('testing')) {
            logger('Mocking');
            $data = get_fixture('openai_response_upload_file_response.json');

            return FileUploadDto::from($data);
        }

        $response = OpenAI::files()->upload(
            [
                'purpose' => 'assistants',
                'file' => fopen($full_path_to_file, 'r'),
            ],
        );

        return FileUploadDto::from($response->toArray());
    }


    /**
     * @TODO
     * This is not done
     * @return void
     */
    public function threadCreateAndRun(AssistantModel $assistant)
    {

        /**
         * @TODO did not finish been working up the chain of features
         */
        $response = OpenAI::threads()->createAndRun(
            [
                'assistant_id' => $assistant->external_assistant_id,
                'thread' => [
                    'messages' =>
                        [
                            [
                                'role' => 'user',
                                'content' => 'Can you list the top three states',
                            ],
                        ],
                ],
            ],
        );

        put_fixture("open_ai_assistant_thread.json", $response);
    }

    protected function createThread(string $assistant_id)
    {
    }

    public function retrieve(string $assistant_id): AssistantDto
    {
        /**
         * @NOTE
         * this build int mock was not working so
         *
         */
        if (config('openai.mock') || app()->environment('testing')) {
            logger('Mocking');
            $data = get_fixture('assistant_response.json');

            return AssistantDto::from($data);
        }

        $response = OpenAI::assistants()->retrieve($assistant_id);

        return AssistantDto::from($response->toArray());
    }
}
