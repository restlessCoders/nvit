<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OtherPayment>
 */
class OtherPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'other_payment_category_id' => \App\Models\OtherPaymentCategory::inRandomorder()->first()->id,
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'paymentDate' => $this->faker->dateTimeThisYear(),
            'payment_mode' => 1,
            "created_at" => \App\Models\User::inRandomOrder()->first()->id,
        ];
    }
}
