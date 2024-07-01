<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
        ]);

        User::factory()->create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
        ]);

        $this->call(CategorySeeder::class);
        $this->call(micrositesSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(DefaultRolesAndPermissionsSeeder::class);
    }
}
