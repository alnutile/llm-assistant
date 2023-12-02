<?php

namespace Tests\Feature;

use App\Models\Message;
use App\OpenAi\Dtos\AssistantDto;
use App\OpenAi\Dtos\FileUploadDto;
use Facades\App\OpenAi\Assistant;
use App\OpenAi\Assistant as AssistantRaw;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Files\CreateResponse;
use Tests\TestCase;

class AssistantTest extends TestCase
{

    public function test_upload_file() {

        $data = get_fixture('openai_response_upload_file_response.json');


        OpenAI::fake([
            CreateResponse::fake($data)
        ]);



        $path = base_path('tests/fixtures/openai_file_upload.csv');
        $assistant = Assistant::uploadFile($path);

        $this->assertInstanceOf(FileUploadDto::class, $assistant);
    }

    public function test_gets_assistant() {
        $assistant_id = config("openai.assistants.bullet_journal_assistant_id");
        $assistant = Assistant::retrieve($assistant_id);
        $this->assertInstanceOf(AssistantDto::class, $assistant);
    }

    public function test_gets_existing_assistant_thread() {

        $this->markTestSkipped("@TODO wip ");
        $audienceBuilder = Message::factory()->create();
        $assistantModel = \App\Models\Assistant::factory()->create([
            'assistantable_id' => $audienceBuilder->id
        ]);
        $results = Assistant::bu( $audienceBuilder->refresh());
        $this->assertInstanceOf(AssistantRaw::class, $results);
    }

    public function test_makes_assistant_thread() {

        $this->markTestSkipped("@TODO wip ");
        $audienceBuilder = Message::factory()->create();
        $results = Assistant::newsAssistant( $audienceBuilder->refresh());
        $this->assertInstanceOf(AssistantRaw::class, $results);
    }
}
