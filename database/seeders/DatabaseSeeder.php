<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\API\Bills\Bill;
use App\Models\API\Bills\Place;
use Illuminate\Database\Seeder;
use App\Models\API\Bills\Category;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Laravel',
            'email' => 'laravel@example.com',
            'password' => Hash::make('123456')
        ]);

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
            'user_id' => 1,
            'name' => 'Spotify',
            'description' => 'I need to listen some music...',
            'value' => 7500,
        ]);

        $firstBill->categories()->attach($entertainmentCategory->id);
        $firstBill->places()->attach($websitePlace->id);

        $secondBill = Bill::create([
            'user_id' => 1,
            'name' => 'Big Mac',
            'description' => 'Mac Donalds Sandwich',
            'value' => 5500,
        ]);

        $secondBill->categories()->attach($foodCategory->id);
        $secondBill->places()->attach($macDonalds->id);

        $thirdBill = Bill::create([
            'user_id' => 1,
            'name' => 'Clean Code',
            'description' => 'Tech book',
            'value' => 45000,
        ]);

        $thirdBill->categories()->attach($educationCategory->id);
        $thirdBill->places()->attach($websitePlace->id);
    }
}
