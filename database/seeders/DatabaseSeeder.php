<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

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
            // Disable TicketSeeder when seeding prod DB
            // TicketSeeder::class,
        ]);

        // Create first account which has role Admin to create other accounts
        $user = User::factory([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'super.admin@fanshaweonline.ca',
            'password' => static::$password ??= Hash::make('password'),
        ])->create();
        $user->roles()->attach(3);
    }
}
