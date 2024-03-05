<?php

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

function loginUser($context)
{
    $user = User::factory()->create(['password' => Hash::make('admin123')]);
    $response = $context->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'admin123'
    ]);

    return $user;
}

it('store is registered with empty request', function () {
    loginUser($this);

    $response = $this->postJson('/api/stores');
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['moto']);
});

it('store is registered with valid request', function () {
    loginUser($this);

    $registerStorePayload = [
        'moto' => 'We wont stop until we have fed all of you lot!'
    ];
    $response = $this->postJson('/api/stores', $registerStorePayload)->dump();
    $response->assertStatus(200);
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.store', fn(AssertableJson $json) => $json->where('moto', $registerStorePayload['moto'])->etc())->etc());
    $this->assertDatabaseCount(Store::class, 1);
});

it('store process request with invalid request', function () {
    loginUser($this);

    $response = $this->postJson('/api/stores/process');
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['storeId', 'status']);
});

it('store process request with valid request - disapproved store', function () {
    $registerStorePayload = [
        'name' => 'test store name',
        'email' => 'user@test.com',
        'phone' => '03135028148',
        'moto' => 'We wont stop until we have fed all of you lot!',
        'street' => 'street no 07',
        'city' => 'RWP',
        'country' => 'PK',
        'line address 1' => 'street no 07 faizabad, rawalpindi'
    ];
    $storeRegisterResponse = $this->postJson('/api/stores', $registerStorePayload);
    $storeRegisterResponse->assertStatus(200);

    $storeProcessPayload = [
        'storeId' => $storeRegisterResponse['data']['store']['id'],
        'status' => Store::DISAPPROVED_STORE
    ];

    $storeProcessResponse = $this->postJson('/api/stores/process', $storeProcessPayload)->dump();
    $storeProcessResponse->assertOk();
    $storeProcessResponse->assertJson(
        fn(AssertableJson $json) => $json->has('data',
            fn(AssertableJson $json) => $json->has('store',
                fn(AssertableJson $json) => $json->where('status', $storeProcessPayload['status'])->etc()
            )
        )->etc()
    );
});
