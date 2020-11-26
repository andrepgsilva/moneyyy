<?php

namespace Database\Factories\API\Bills;

use App\Models\API\Bills\Place;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    use WithFaker;
    
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Place::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
