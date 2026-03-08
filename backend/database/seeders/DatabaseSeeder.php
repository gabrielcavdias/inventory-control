<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminUserExists = User::query()->where('email', 'admin@inventory.com')->first();
        if (!$adminUserExists) {
            User::query()->createOrFirst([
                'name' => 'Administrador',
                'email' => 'admin@inventory.com',
                'password' => 'admin12345',
                'role' => UserRoles::ADMIN
            ]);
        }
    }
}
