<?php

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

it('user entered invalid address fields', function () {
    $user = User::factory()->create(['password' => Hash::make('admin123')]);
    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'admin123'
    ]);

    $response = $this->postJson('/api/addresses');
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['street', 'city', 'country', 'line address 1']);
});

it('user can create address', function () {
    $user = User::factory()->create(['password' => Hash::make('admin123')]);
    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'admin123'
    ]);

    $addressPayload = [
        'street' => 'street no 07',
        'city' => 'RWP',
        'country' => 'PK',
        'line address 1' => 'street no 07 faizabad, rawalpindi'
    ];

    $response = $this->postJson('/api/addresses', $addressPayload)->dump();
    $response->assertOk();
    $response->assertJson(fn(AssertableJson $json) =>
        $json->has('message')->where('message', 'Address created successfully')
            ->has('data', fn(AssertableJson $json) =>
                $json->has('address', fn(AssertableJson $json) =>
                    $json->where('type', Address::STORE_ADDRESS)->etc())
            )
    );
    $this->assertDatabaseHas(Address::class, ['type' => Address::STORE_ADDRESS]);
});
