<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
 
    public function run(): void
    {
        $roles = [
            'Admin',
            'Customer',
            'Guests'
        ];

        foreach ($roles as $role) {
            Role::query()->create([
                'name' => $role,
            ]);
        }
    }
}
