<?php

namespace Tests\Feature;

use App\Domains\LlmFunctions\Dto\FunctionCallDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_query(): void
    {
        $dto = FunctionCallDto::from([
            'function_name' => ''
        ]);


    }
}
