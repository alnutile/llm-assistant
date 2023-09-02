<?php

namespace Tests\Feature;

use App\Domains\LlmFunctions\Dto\RoleTypeEnum;
use App\OpenAi\Dtos\MessageDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageDtoTest extends TestCase
{

    public function test_not_null() {
        $dto = MessageDto::from([
           'content' => "foo",
           'role' => RoleTypeEnum::User
        ]);

        $this->assertArrayNotHasKey('function', $dto->toArray());
        $this->assertArrayNotHasKey('name', $dto->toArray());
    }

    public function test_there() {
        $dto = MessageDto::from([
            'content' => "foo",
            'function' => "foo",
            'role' => RoleTypeEnum::User
        ]);

        $this->assertArrayHasKey('function', $dto->toArray());

        $this->assertEquals('user', $dto->toArray()['role']);
    }
}
