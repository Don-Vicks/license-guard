<?php

namespace Database\Factories;

use App\Models\LicenseType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LicenseType>
 */
class LicenseTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LicenseType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'amount' => $this->faker->randomFloat(2, 10, 1000), // Generate a random amount between 10 and 1000 with 2 decimal places
            'duration' => $this->faker->randomElement(['monthly', 'quarterly', 'annually']),
            'status' => $this->faker->boolean,
        ];
    }
}

