<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::factory()->count(10)->create();

        $roles = Role::all();
        $users = User::all();

        foreach ($users as $user) {
            $role = $roles->random();
            $user->roles()->attach($role->id);
        }

        $users = User::whereHas('roles', function ($q) {
            $q->where('roles.id', 1); // Add course for user with role_id = 1 only (Tutor)
        })->get();

        $courses = Course::all();

        foreach ($courses as $course) {
            $course->users()->attach($users->random());
        }
    }
}
