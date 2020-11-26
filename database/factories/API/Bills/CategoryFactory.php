<?php

namespace Database\Factories\API\Bills;

use Illuminate\Support\Str;
use App\Models\API\Bills\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    use WithFaker;
    
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categoryName = $this->faker->name();
        
        return [
            'name' => $categoryName,
            'slug' => Str::of($categoryName)->slug('-'),
        ];
    }
}
