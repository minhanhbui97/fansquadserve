<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
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
            
        ]);

        $roles = Role::all();

        
        $users = \App\Models\User::factory()->count(10)->create();
        
        foreach ($users as $user) {
            $role = $roles->random();
            $user->roles()->attach($role->id);
        }

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
