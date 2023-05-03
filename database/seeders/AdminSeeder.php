<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin1 = User::whereEmail(env('ADMIN_ONE'))->firstOr(
            function () {
                $user = new User();
                $user->name = env('ADMIN_ONE');
                $user->email = env('ADMIN_ONE');
                $user->is_admin = 1;
                $user->password = bcrypt(env('ADMIN_ONE_PASSWORD'));
                $user->save();

                return $user;
            }
        );

        $adminTeam = Team::whereName('Admin Team')->firstOr(
            function () use ($admin1) {
                $adminTeam = new Team();
                $adminTeam->name = 'Admin Team';
                $adminTeam->personal_team = false;
                $adminTeam->user_id = $admin1->id;
                $adminTeam->save();

                $admin1->current_team_id = $adminTeam->id;
                $admin1->save();
                $adminTeam->users()->attach($admin1->id, ['role' => 'admin']);

                return $adminTeam;
            }
        );

        User::whereEmail(env('ADMIN_TWO'))->firstOr(
            function () use ($adminTeam) {
                $user = new User();
                $user->name = env('ADMIN_TWO');
                $user->email = env('ADMIN_TWO');
                $user->is_admin = 1;
                $user->password = bcrypt(env('ADMIN_TWO_PASSWORD'));
                $user->save();
                $user->current_team_id = $adminTeam->id;
                $user->save();
                $adminTeam->users()->attach($user->id, ['role' => 'admin']);

                return $user;
            }
        );

    }
}
