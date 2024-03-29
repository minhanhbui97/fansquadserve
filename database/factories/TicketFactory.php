<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\OperatingSystem;
use App\Models\Priority;
use App\Models\Program;
use App\Models\TypeOfMachine;
use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'reference_number' => random_int(1000000, 9999999),
            'program_level' => fake()->randomElement([1, 2, 3, 4]),
            'description' => fake()->paragraph(),
            'scheduled_start_time' => fake()->dateTime(),
            'scheduled_end_time' => fake()->dateTime(),
            'tutor_note' => fake()->paragraph(),
            'student_id' => Student::factory(),
            'program_id' => Program::all()->random(),
            'type_of_machine_id' => TypeOfMachine::all()->random(),
            'operating_system_id' => OperatingSystem::all()->random(),
            'priority_id' => Priority::all()->random(),
            'course_id' => Course::all()->random(),
            'assigned_tutor_id' => User::factory(),
        ];
    }
}
