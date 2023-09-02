<?php

namespace Tests\Feature;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Models\Message;
use App\Models\MetaData;
use Facades\App\Domains\Message\MessageRepository;
use Facades\App\OpenAi\ChatClient;
use Tests\TestCase;

class MessageRepositoryTest extends TestCase
{
    public function test_makes_request()
    {

        $message = Message::factory()->create([
            'role' => RoleTypeEnum::User]);
        ChatClient::shouldReceive('setMessage->chat')->once()->andReturn(
            $message
        );

        $meta_data1 = MetaData::factory()->create();
        $meta_data2 = MetaData::factory()->create();
        $message->meta_data()->attach([
            $meta_data1->id,
            $meta_data2->id,
        ]);

        $messageChild = Message::factory()->count(2)->create([
            'parent_id' => $message->id,
        ]);

        $response = MessageRepository::handle($message);

        $this->assertInstanceOf(Message::class, $response);
    }
}
