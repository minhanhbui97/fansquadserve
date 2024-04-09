<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operating_systems = ['Windows 9', 'Windows 10', 'Windows 11', 'Mac'];

        foreach ($operating_systems as $operating_system) {
            \App\Models\OperatingSystem::firstOrCreate(['name' => $operating_system]);
        }
    }
}
