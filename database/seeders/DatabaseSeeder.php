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

        $otherCategory = Category::create([
            'name' => 'Other',
            'slug' => Str::of('Other')->slug('-'),
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
            'place' => 'Fnac',
            'issue_date' => now(),
        ]);

        $thirdBill->categories()->attach($educationCategory->id);

        $fourthBill = Bill::create([
            'user_id' => 1,
            'name' => 'Clean Architecture',
            'description' => 'Tech book',
            'value' => 60000,
            'place' => 'Bertrand',
            'issue_date' => now()->addDays(4),
        ]);

        $fourthBill->categories()->attach($educationCategory->id);

        $fifthBill = Bill::create([
            'user_id' => 1,
            'name' => 'Trofa Hospital',
            'description' => 'A skin dicease.',
            'value' => 80000,
            'place' => 'Trofa Hospital',
            'issue_date' => now()->addDays(4),
        ]);

        $fifthBill->categories()->attach($healthCategory->id);

        $sixthBill = Bill::create([
            'user_id' => 1,
            'name' => 'Life University',
            'description' => 'A design course.',
            'value' => 100000,
            'place' => 'Life University',
            'issue_date' => now()->addDays(4),
        ]);

        $sixthBill->categories()->attach($educationCategory->id);

        $seventhBill = Bill::create([
            'user_id' => 1,
            'name' => 'Nintendo eShop',
            'description' => 'A design course.',
            'value' => 70000,
            'place' => 'Nintendo eShop',
            'issue_date' => now()->addDays(4),
        ]);

        $seventhBill->categories()->attach($entertainmentCategory->id);

        //Bill::factory()->count(100)->has(Category::factory(['name' => 'Food'])->count(1))->create(['user_id' => 1]);
    }
}
