<?php

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

test('user entered invalid fields', function () {
    $response = $this->postJson('/api/login');
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email', 'password']);
});

test('admin can login', function () {
    $this->seed(AdminSeeder::class);
    $loginPayload = [
        'email' => 'ADMIN@test.com',
        'password' => 'admin123'
    ];
    $response = $this->postJson('/api/login', $loginPayload);
    $response->assertOk();
    $response->assertJson(
        fn(AssertableJson $json) => $json->has('data.token')
            ->has('data.user', fn(AssertableJson $json) => $json->where('email', $loginPayload['email'])->etc())
            ->where('message', 'User logged in successfully!')
    );
});

test('admin tries to login with invalid', function () {
    $this->seed(AdminSeeder::class);
    $response = $this->postJson('/api/login', [
        'email' => 'ADMIN@test.com.pk',
        'password' => 'admin123'
    ]);
    $response->assertStatus(400);
    $response->assertJson(fn(AssertableJson $json) => $json->has('error'));
});

test('user can login successfully', function () {
    $user = User::factory()->create(['password' => Hash::make('admin123')]);
    $loginResponse = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'admin123'
    ]);
    $loginResponse->assertOk();
    $loginResponse->assertJson(fn(AssertableJson $json) => $json->has('data.token')->etc());
});
