<?php

namespace Database\Seeders;

use App\Enums\User\RoleEnum;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub = Subject::pluck('id');
        $user = User::factory(1)->create([
            'name' => 'user1',
            'email' => 'user1@test.com',
        ])->first();
        User::factory(1)->create([
            'name' => 'user2',
            'email' => 'user2@test.com',
        ]);
        $s = $sub->random(3, true);

        $user->subjects()->attach($s->map(function ($id) {
            $role = rand((int)RoleEnum::STUDENT->value, (int)RoleEnum::TEACHER->value);
            $count = 0;
            $teacher_id = null;
            if ($role == RoleEnum::TEACHER->value) {
                $count = rand(1, 50);
            } else {
                $teacher_id = 2;

            }
            return [
                'subject_id' => $id,
                'role' => $role,
                'teacher_id' => $teacher_id,
                'count' => $count
            ];
        }));
        User::factory(18)->create();

    }
}
