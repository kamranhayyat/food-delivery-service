<?php

use App\Mail\DefaultStorePassword;
use App\Models\Address;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;

it('store is registered with empty request', function () {
    $response = $this->postJson('/api/stores');
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'password', 'phone', 'moto']);
});

it('store is registered with valid request', function () {
    Mail::fake();

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
    $response = $this->postJson('/api/stores', $registerStorePayload);
    $response->assertStatus(200);
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.store', fn(AssertableJson $json) => $json->where('moto', $registerStorePayload['moto'])->etc())->etc());
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.storeAddresses', fn(AssertableJson $json) => $json->where('street', $registerStorePayload['street'])->etc())->etc());
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.user', fn(AssertableJson $json) => $json->where('email', $registerStorePayload['email'])->etc())->etc());
    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseCount(Store::class, 1);
    $this->assertDatabaseCount(Address::class, 1);
    $this->assertDatabaseHas(Address::class, ['type' => Store::STORE_ADDRESS_TYPE]);
    Mail::assertSent(DefaultStorePassword::class, function($mail) use ($registerStorePayload) {
        return $mail->hasTo($registerStorePayload['email']);
    });
});

it('store process request with invalid request', function () {
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
