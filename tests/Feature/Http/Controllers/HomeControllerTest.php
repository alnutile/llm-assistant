<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Message;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('dashboard'))
            ->assertStatus(200);
    }

    public function test_tag_filter()
    {
        $user = User::factory()->create();
        $message = Message::factory()->create([
            'user_id' => $user->id,
        ]);
        $tag = Tag::factory()->create();
        $message->tags()->attach($tag->id);

        $this->actingAs($user)->get(route('dashboard', [
            'tags[]' => $tag->id,
        ]))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->has('messages.data', 1));

        $this->actingAs($user)->get(route('dashboard', [
            'tags[]' => $tag->id + 1,
        ]))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->has('messages.data', 0));
    }
}
