<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::createUserAndTeam(
            env('ADMIN_EMAIL'),
            env('ADMIN_PASSWORD'),
            true
        );

    }
}
