<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Tutor', 'Staff', 'Admin'];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(['name' => $role]);
        }
    }
}
