<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\API\Bills\Bill;
use Laravel\Passport\Passport;
use App\Models\API\Bills\Category;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\API\AddTokenToAuthHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Passport::actingAs(User::create([
            'name' => 'laravel',
            'email' => 'laravel@example.com',
            'password' => Hash::make('123456'),
            'timezone' => 'Europe/Lisbon',
        ]));
    }

    public function test_it_can_get_all_bills_with_relationships()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        Bill::factory()->count(3)
            ->has(Category::factory()->count(3))
            ->create(['user_id' => 1]);

        $bills = $this->getJson('/api/bills')->getData()->data;

        $this->assertEquals(3, count($bills));
        $this->assertEquals(3, count($bills[0]->categories));
    }

    public function test_it_can_only_get_bills_with_relationships_it_owns()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        Bill::factory()->count(3)->create(['user_id' => auth()->user()->id]);

        Bill::factory()->count(5)
            ->has(Category::factory()->count(3))
            ->create();

        $bills = $this->get('/api/bills')->getData()->data;

        $this->assertEquals(3, count($bills));
    }

    public function test_it_cannot_get_a_inexistent_bill()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        Bill::factory()
            ->has(Category::factory()->count(2))
            ->create(['user_id' => auth()->user()->id]);

        $this->getJson('/api/bills/6')
            ->assertForbidden();
    }

    public function test_it_can_get_a_bill()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        $bills = Bill::factory()->count(3)
            ->create(['user_id' => auth()->user()->id]);

        $response = $this->getJson('/api/bills/1');
        $response->assertOk();

        $this->assertEquals($bills[0]['name'], $response->getData()->name);
    }

    public function test_it_cannot_get_a_bill_it_do_not_owns()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        Bill::factory()->create(['user_id' => auth()->user()->id]);
        Bill::factory()->create();

        $response = $this->getJson('/api/bills/2');
        $response->assertForbidden();
    }

    public function test_it_can_create_a_new_bill()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        $response = $this->post('/api/bills', Bill::factory()->raw());

        $response->assertStatus(201);
    }

    public function test_it_cannot_create_a_bill_with_invalid_information()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        $response = $this->postJson('/api/bills', [
            'name' => '',
            'description' => '',
            'value' => 'aaa',
        ]);

        $response->assertStatus(422);
    }

    public function test_it_can_delete_a_bill()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        Bill::factory()
            ->has(Category::factory())
            ->create(['user_id' => 1]);

        $this->deleteJson('/api/bills/1')
            ->assertStatus(201);
    }

    public function test_it_cannot_delete_a_bill_it_not_owns()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        Bill::factory()
            ->has(Category::factory())
            ->create();

        $this->deleteJson('/api/bills/1')
            ->assertStatus(403);
    }

    public function test_it_can_update_a_bill()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        Bill::factory()
            ->has(Category::factory())
            ->create(['user_id' => auth()->user()->id]);

        $response = $this->putJson('/api/bills/1', [
            'name' => 'laravel',
            'description' => 'lorem ipsum dolor sit laravel',
            'value' => 33333,
            'issue_date' => now(),
        ]);

        $firstBill = Bill::first();

        $this->assertEquals($firstBill->name, 'laravel');
        $this->assertEquals($firstBill->description, 'lorem ipsum dolor sit laravel');
        $this->assertEquals($firstBill->value, 33333);

        $response->assertStatus(200);
    }

    public function test_it_cannot_update_a_bill_that_it_not_owns()
    {
        $this->withoutMiddleware(AddTokenToAuthHeader::class);

        Bill::factory()
            ->has(Category::factory())
            ->create();

        $response = $this->putJson('/api/bills/1', [
            'name' => 'a',
            'description' => 'lorem ipsum dolor sit laravel',
            'value' => 3,
        ]);

        $response->assertStatus(403);
    }
}
