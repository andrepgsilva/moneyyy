<?php

namespace Database\Factories\API\Bills;

use App\Models\API\Bills\Bill;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    use WithFaker;
    
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'value' => $this->faker->numberBetween(10000, 90000),
        ];
    }
}
