<?php
namespace Database\Factories;

use App\Models\School;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        $school = School::inRandomOrder()->first();
        return [
            'school_id' => $school ? $school->id : 1,
            'classroom_id' => Classroom::inRandomOrder()->first()->id ?? null,
            'name' => $this->faker->name(),
            'nis' => $this->faker->unique()->numerify('2025####'),
            'gender' => $this->faker->randomElement(['L', 'P']),
            'birth_date' => $this->faker->date('Y-m-d', '-10 years'),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
        ];
    }
}
