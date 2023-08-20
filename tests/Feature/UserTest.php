<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user->email);
    }

    public function test_create_team()
    {
        $user = User::factory()->create();
        $this->assertDatabaseCount('teams', 0);
        User::createTeam($user);
        $this->assertDatabaseCount('teams', 1);
    }

    public function test_seeder()
    {
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('teams', 0);
        $this->seed(AdminSeeder::class);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('teams', 1);
    }
}
