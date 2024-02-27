<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

it('user tries to register with empty request', function () {
    $response = $this->postJson('/api/register');
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'password', 'phone']);
});

it('user tries to register with invalid request', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'user',
        'email' => 'user@test.com',
        'password' => 'admin123@',
        'password_confirmation' => 'admin123',
        'phone' => '0313502814'
    ]);
    $response->assertStatus(422);
})->withD;

it('user tries to register with valid request', function () {
    $registerPayload = [
        'name' => 'user',
        'email' => 'user@test.com',
        'password' => 'admin123@',
        'password_confirmation' => 'admin123@',
        'phone' => '03135028148'
    ];
    $response = $this->postJson('/api/register', $registerPayload);
    $response->assertOk();
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.user', fn(AssertableJson $json) => $json->where('email', $registerPayload['email'])->etc())->where('message', 'User registered successfully!'));
});
