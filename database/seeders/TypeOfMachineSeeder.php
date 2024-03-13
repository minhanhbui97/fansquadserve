<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeOfMachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types_of_machine = ['Windows', 'Mac'];

        foreach ($types_of_machine as $types_of_machine) {
            \App\Models\TypeOfMachine::firstOrCreate(['name' => $types_of_machine]);
        }
    }
}
