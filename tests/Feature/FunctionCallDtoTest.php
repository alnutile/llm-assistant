<?php

namespace Tests\Feature;

use App\Domains\LlmFunctions\Dto\FunctionCallDto;
use Tests\TestCase;

class FunctionCallDtoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_string(): void
    {
        $results = FunctionCallDto::from([
            'name' => 'some_function',
            'content' => [
                'foo' => 'bar',
            ],
        ]);

        $this->assertIsString($results->toJson());
    }
}
