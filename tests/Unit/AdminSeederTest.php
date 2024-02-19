<?php

use App\Models\User;
use Database\Seeders\AdminSeeder;

it('check if admin seeder was run', function () {
    $this->assertDatabaseCount(User::class, 0);
    $this->seed(AdminSeeder::class);
    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseHas(User::class, [
        'email' => 'ADMIN@test.com'
    ]);
    $user = User::first();
    $this->assertTrue($user->isAdmin());
});
