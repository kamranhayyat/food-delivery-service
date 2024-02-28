<?php

use App\Mail\DefaultStorePassword;
use App\Models\Store;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;

test('store is registered with empty request', function () {
    $response = $this->postJson('/api/store');
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'password', 'phone', 'moto']);
});

test('store is registered with valid request', function () {
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
    $response = $this->postJson('/api/store', $registerStorePayload);
    $response->assertStatus(200);
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.store', fn(AssertableJson $json) => $json->where('name', $registerStorePayload['name'])->etc())->etc());
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.user', fn(AssertableJson $json) => $json->where('email', $registerStorePayload['email'])->etc())->etc());
    $this->assertDatabaseCount(Store::class, 1);
    Mail::fake();
    Mail::assertSent(DefaultStorePassword::class, function($mail) use ($response) {
        return $mail->hasTo($response['data']['user']['email']);
    });
});
