<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\API\Bills\Bill;
use App\Models\API\Bills\Place;
use App\Models\API\Bills\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillsTest extends TestCase
{
    use RefreshDatabase;

    protected function login()
    {
        User::create([
            'name' => 'laravel',
            'email' => 'laravel@example.com',
            'password' => Hash::make('123456')
        ]);

        $this->postJson('/api/auth/login', [
            'email' => 'laravel@example.com',
            'password' => '123456',
        ]);
    }

    public function test_it_can_get_all_bills_with_relationships()
    {
        $this->login();

        Bill::factory()->count(5)
            ->has(Category::factory()->count(3))
            ->has(Place::factory())
            ->create();
        
        $bills = $this->get('/api/bills')->getData()->data;

        $this->assertEquals(3, count($bills));
        $this->assertEquals(3, count($bills[0]->categories));
        $this->assertEquals(1, count($bills[0]->places));
    }

    public function test_it_cannot_get_a_bill_with_relationships()
    {
        $this->login();

        Bill::factory()
            ->has(Category::factory()->count(2))
            ->has(Place::factory())
            ->create();

        $this->getJson('/api/bills/6')
            ->assertNotFound();
    }

    public function test_it_can_get_a_bill_with_relationships()
    {
        $this->login();

        $bills = Bill::factory()->count(3)
            ->has(Category::factory()->count(3))
            ->has(Place::factory())
            ->create();

        $response = $this->getJson('/api/bills/2');

        $response->assertOk();
        $this->assertEquals($bills[1]['name'], $response->getData()->name);
    }

    public function test_it_can_create_a_new_bill()
    {
        $this->login();

        $response = $this->post('/api/bills', Bill::factory()->raw());

        $response->assertStatus(201);
    }

    public function test_it_cannot_create_a_new_bill()
    {
        $this->login();

        $response = $this->postJson('/api/bills', [
            'name' => '',
            'description' => '',
            'value' => 'aaa',
        ]);

        $response->assertStatus(422);
    }

    public function test_it_can_delete_a_bill()
    {
        $this->login();

        Bill::factory()
            ->has(Category::factory())
            ->has(Place::factory())
            ->create();

        $this->deleteJson('/api/bills/1')
            ->assertStatus(201);
    }

    public function test_it_can_update_a_bill()
    {
        $this->login();

        Bill::factory()
            ->has(Category::factory())
            ->has(Place::factory())
            ->create();

        $response = $this->putJson('/api/bills/1', [
            'name' => 'laravel',
            'description' => 'lorem ipsum dolor sit laravel',
            'value' => 33333,
        ]);

        $firstBill = Bill::first();
        
        $this->assertEquals($firstBill->name, 'laravel');
        $this->assertEquals($firstBill->description, 'lorem ipsum dolor sit laravel');
        $this->assertEquals($firstBill->value, 33333);
        
        $response->assertStatus(200);
    }

    public function test_it_cannot_update_a_bill()
    {
        $this->login();
        
        Bill::factory()
            ->has(Category::factory())
            ->has(Place::factory())
            ->create();

        $response = $this->putJson('/api/bills/1', [
            'name' => '',
            'description' => 'lorem ipsum dolor sit laravel',
            'value' => 'a',
        ]);
        
        $response->assertStatus(422);
    }
}
