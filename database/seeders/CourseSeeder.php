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
            "GENERAL",

            "INFO-6084",
            "MGMT-6135",
            "INFO-6085",
            "ACAD-6002",
            "DEVL-6033",
            "INFO-6087",
            "INFO-6086",
            "INFO-6088",

            "INFO-6089",
            "INFO-6090",
            "INFO-6091",
            "INFO-6092",
            "INFO-6093",
            "MGMT-6148",

            "INFO-6094",
            "INFO-6095",
            "INFO-6096",
            "INFO-6097",
            "INFO-6098",
            "INFO-6099",

            "INFO-6100",
            "INFO-6101",
            "BUSI-6010",
            "INFO-6102",
            "INFO-6103",
            "MGMT-6134"

        ];
        $names = [
            "Lab & Tech Support",

            "Practical Applications of ITIL",
            "Essential Skills for Mgmt Professionals",
            "End User Operations",
            "Graduate Success Strategies",
            "Career Planning",
            "Networking & Security 1",
            "Operating Systems 1",
            "Databases 1",

            "Databases 2 & Programming",
            "Networking & Security 2",
            "Operating Systems 2",
            "Data Management",
            "Cloud Services",
            "Applied Project Management",

            "Programming 2",
            "IT Architecture",
            "Storage Systems",
            "Business Solutions Architecture",
            "Web & Mobile Systems Programming",
            "Software Design & Programming",

            "Software Development & Maintenance",
            "Integrated Systems & Micro Controllers",
            "Business Intelligence & Big Data",
            "Enterprise Resource Planning",
            "Enterprise Application Modelling",
            "Capstone Project"
        ];

        for ($i = 0; $i < count($codes); $i++) {
            Course::create([
                'name' => $names[$i],
                'code' => $codes[$i]
            ]);
        }
    }
}
