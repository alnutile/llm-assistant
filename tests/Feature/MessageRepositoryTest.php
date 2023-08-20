<?php

namespace Tests\Feature;

use App\Models\MetaData;
use App\OpenAi\Dtos\Response;
use Facades\App\OpenAi\ChatClient;
use Facades\App\Domains\Message\MessageRepository;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageRepositoryTest extends TestCase
{

    public function test_makes_request() {
        ChatClient::shouldReceive('chat')->once();
        $message = Message::factory()->create();
        $meta_data1 = MetaData::factory()->create();
        $meta_data2 = MetaData::factory()->create();
        $message->meta_data()->attach([
            $meta_data1->id,
            $meta_data2->id,
        ]);

        $messageChild = Message::factory()->count(2)->create([
            'parent_id' => $message->id
        ]);

        $response = MessageRepository::handle($message);


        $this->assertInstanceOf(Response::class, $response);
    }
}
