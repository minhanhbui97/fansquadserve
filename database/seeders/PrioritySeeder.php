<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = ['Low', 'Medium', 'High'];

        foreach ($priorities as $priority) {
            \App\Models\Priority::firstOrCreate(['name' => $priority]);
        }    }
}
