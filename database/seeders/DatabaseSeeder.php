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
            'email' => 'superadmin@microsites.com',
        ]);

        User::factory()->create([
            'name' => 'Customer Admin',
            'email' => 'customeradmin@microsites.com',
        ]);

        User::factory()->create([
            'name' => 'Guest',
            'email' => 'guest@microsites.com',
        ]);

        $this->call(CategorySeeder::class);
        $this->call(micrositesSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(DefaultRolesAndPermissionsSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(SubscriptionSeeder::class);
    }
}
