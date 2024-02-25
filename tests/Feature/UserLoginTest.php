<?php

use Database\Seeders\AdminSeeder;
use Illuminate\Testing\Fluent\AssertableJson;

test('user entered invalid fields', function () {
    $response = $this->postJson('/api/login');
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email', 'password']);
});

test('user can login', function () {
    $this->seed(AdminSeeder::class);
    $response = $this->postJson('/api/login', [
        'email' => 'ADMIN@test.com',
        'password' => 'admin123'
    ]);
    $response->assertOk();
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.token'));
});

test('user tries to login with invalid', function () {
    $this->seed(AdminSeeder::class);
    $response = $this->postJson('/api/login', [
        'email' => 'ADMIN@test.com.pk',
        'password' => 'admin123'
    ]);
    $response->assertStatus(401);
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.error'));
});
