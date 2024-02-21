<?php

test('user entered invalid fields', function () {
    $response = $this->postJson('/api/login');
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email', 'password']);
});

test('user can login', function () {
    $response = $this->postJson('/api/login');
    $response->assertStatus(200);
//    $response->assertJsonValidationErrors(['email', 'password']);
});
