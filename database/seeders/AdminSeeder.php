<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::query()->where('name', 'ADMIN')->get();

        $adminUser = User::query()->create([
            'name' => 'ADMIN',
            'email' => 'ADMIN@test.com',
            'password' => Hash::make('admin123'),
            'phone' => '03135028148'
        ]);

        $adminUser->roles()->sync($adminRole);
    }
}
