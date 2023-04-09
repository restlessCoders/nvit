<?php

namespace Database\Factories;

use App\Models\District;
use App\Models\Division;
use App\Models\Reference;
use App\Models\User;
use App\Models\Student;
use App\Models\Upazila;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'name' => $this->faker->name,
            'contact' => $this->faker->ean8,
            'email' => $this->faker->email,
            'address' => $this->faker->address,
            'division_id' => Division::inRandomOrder()->first()->id,
            'district_id' => District::inRandomOrder()->first()->id,
            'upazila_id' => Upazila::inRandomOrder()->first()->id,
            'executiveId' => User::where('roleId','>',0)->inRandomOrder()->first()->id,
            'refId' => Reference::inRandomOrder()->first()->id,
            'status' => $this->faker->numberBetween(1,3),
            'branchId' => 1,
        ];
    }
}
