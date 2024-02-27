<?php

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
});
