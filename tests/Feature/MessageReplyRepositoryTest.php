<?php

namespace Tests\Feature;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\Models\Message;
use App\Models\MetaData;
use Facades\App\Domains\Message\MessageReplyRepository;
use Facades\App\OpenAi\ChatClient;
use Tests\TestCase;

class MessageReplyRepositoryTest extends TestCase
{
    public function test_child()
    {
        $message = Message::factory()->create([
            'role' => RoleTypeEnum::User]);

        $response = Message::factory()->functionResponse()->create([
            'parent_id' => $message->id,
        ]);

        $child = Message::factory()->functionResponse()->create([
            'parent_id' => $message->id,
        ]);

        ChatClient::shouldReceive('setMessage->chat')->once()->andReturn(
            $response
        );

        $meta_data1 = MetaData::factory()->create();
        $meta_data2 = MetaData::factory()->create();
        $message->meta_data()->attach([
            $meta_data1->id,
            $meta_data2->id,
        ]);

        $response = MessageReplyRepository::handle($child);

        $this->assertInstanceOf(Message::class, $response);
    }
}
