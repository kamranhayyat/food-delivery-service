<?php

use Database\Seeders\AdminSeeder;
use Illuminate\Testing\Fluent\AssertableJson;

test('user can logout', function () {
    $this->seed(AdminSeeder::class);
    $loginResponse = $this->postJson('/api/login', [
        'email' => 'ADMIN@test.com',
        'password' => 'admin123'
    ]);
    $response = $this->deleteJson('/api/logout', [], ['Authorization: Bearer ' . $loginResponse->json('token')]);
    $response->assertOk();
    $response->assertJson(fn(AssertableJson $json) => $json->has('data.message'));
});


