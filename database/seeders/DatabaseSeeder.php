<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\OperatingSystem;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([
            RoleSeeder::class,
            CourseSeeder::class,
            TicketStatusSeeder::class,
            TypeOfMachineSeeder::class,
            OperatingSystemSeeder::class,
            PrioritySeeder::class,
            ProgramSeeder::class,
            TicketSeeder::class,

        ]);




        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
