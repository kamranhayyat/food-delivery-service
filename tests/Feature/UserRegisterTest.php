<?php

use Illuminate\Support\Facades\Hash;

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
        'password_confirmation' => 'admin123@',
        'phone' => '03135028148'
    ]);
    $response->assertOk();
    $response->dump();
});
