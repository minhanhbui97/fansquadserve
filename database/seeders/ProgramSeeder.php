<?php

namespace Database\Seeders;
use App\Models\Program;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codes = [
            "BIA1B",
            "ITI2B"
        ];
        $names = [
            "Business and Information Systems Architecture",
            "Information Technology Infrastructure"
        ];

        for ($i = 0; $i < count($codes); $i++) {
            Program::create([
                'name' => $names[$i],
                'code' => $codes[$i]
            ]);
        }
    }
}
