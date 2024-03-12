<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['New', 'Confirmed', 'In-progress', 'Resolved', 'On-hold', 'Escalated', 'Closed'];

        foreach ($statuses as $status) {
            \App\Models\TicketStatus::firstOrCreate(['name' => $status]);
        }
    }
}
