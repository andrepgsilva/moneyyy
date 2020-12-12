<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\API\Bills\Bill;
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
            'password' => Hash::make('123456'),
            'timezone' => 'Europe/Lisbon',
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


        /* Bills */        
        $firstBill = Bill::create([
            'user_id' => 1,
            'name' => 'Spotify',
            'description' => 'I need to listen some music...',
            'value' => 7500,
            'issue_date' => now(),
        ]);

        $firstBill->categories()->attach($entertainmentCategory->id);

        $secondBill = Bill::create([
            'user_id' => 1,
            'name' => 'Big Mac',
            'description' => 'Mac Donalds Sandwich',
            'value' => 5500,
            'issue_date' => now(),
        ]);

        $secondBill->categories()->attach($foodCategory->id);

        $thirdBill = Bill::create([
            'user_id' => 1,
            'name' => 'Clean Code',
            'description' => 'Tech book',
            'value' => 45000,
            'issue_date' => now(),
        ]);

        $thirdBill->categories()->attach($educationCategory->id);
    }
}
