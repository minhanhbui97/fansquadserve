<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codes = [
            "MGMT-6135",
            "INFO-6085",
            "ACAD-6002",
            "DEVL-6033",
            "INFO-6087",
            "INFO-6086",
            "INFO-6088"
        ];
        $names = [
            "Essential Skills for Mgmt Professionals",
            "End User Operations",
            "Graduate Success Strategies",
            "Career Planning",
            "Networking & Security",
            "Operating Systems",
            "Databases"
        ];



        for ($i = 0; $i < count($codes); $i++) {
            Course::create([
                'name' => $names[$i],
                'code' => $codes[$i]
            ]);
        }
    }
}
