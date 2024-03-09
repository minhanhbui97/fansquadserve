<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
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
            'program' => fake()->randomElement(['BIA1B', 'ITI2B']),
            'program_level' => fake()->randomElement([1, 2, 3, 4]),
            'description' => fake()->paragraph(),
            'appointment_time' => fake()->dateTime(),
            'assigned_tutor_id' => User::factory(),
            'student_id' => Student::factory(),
        ];
    }
}
