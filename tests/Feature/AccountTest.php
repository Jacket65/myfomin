<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Repositories\AccountRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_list_of_accounts()
    {
        $accounts = Account::factory()->count(2)->create();

        $response = $this->getJson('/api/accounts');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => ['id', 'name', 'amount']
        ]);

        $response->assertExactJson([
            [
                'id' => $accounts[0]->id,
                'name' => $accounts[0]->name,
                'amount' => $accounts[0]->amount,
            ],
            [
                'id' => $accounts[1]->id,
                'name' => $accounts[1]->name,
                'amount' => $accounts[1]->amount,
            ],
        ]);
    }

    public function test_store_account_validation_error()
    {
        $payload = [
            'name' => '',
            'amount' => 'abc',
        ];


        $response = $this->postJson('/api/accounts', $payload);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'error',
            'detail'
        ]);

        $response->assertJson([
            'error' => 'Validation failed.',
        ]);
    }




}
