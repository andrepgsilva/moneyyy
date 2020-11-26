<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\API\Bills\Bill;
use App\Models\API\Bills\Place;
use Illuminate\Database\Seeder;
use App\Models\API\Bills\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* Categories */
        $entertainmentCategory = Category::create([
            'name' => 'Entertainment',
            'slug' => Str::of('Entertainment')->slug('-'),
        ]);

        $foodCategory = Category::create([
            'name' => 'Food',
            'slug' => Str::of('Food')->slug('-'),
        ]);

        $educationCategory = Category::create([
            'name' => 'Education',
            'slug' => Str::of('Education')->slug('-'),
        ]);

        $healthCategory = Category::create([
            'name' => 'Health',
            'slug' => Str::of('Health')->slug('-'),
        ]);


        /* Places */
        $websitePlace = Place::create([
            'name' => 'Website',
        ]);

        $macDonalds = Place::create([
            'name' => 'MacDonalds',
        ]);

        /* Bills */
        
        $firstBill = Bill::create([
            'name' => 'Spotify',
            'description' => 'I need to listen some music...',
            'value' => 7500,
        ]);

        $firstBill->categories()->attach($entertainmentCategory->id);
        $firstBill->places()->attach($websitePlace->id);

        $secondBill = Bill::create([
            'name' => 'Big Mac',
            'description' => 'Mac Donalds Sandwich',
            'value' => 5500,
        ]);

        $secondBill->categories()->attach($foodCategory->id);
        $secondBill->places()->attach($macDonalds->id);

        $thirdBill = Bill::create([
            'name' => 'Clean Code',
            'description' => 'Tech book',
            'value' => 45000,
        ]);

        $thirdBill->categories()->attach($educationCategory->id);
        $thirdBill->places()->attach($websitePlace->id);
    }
}
