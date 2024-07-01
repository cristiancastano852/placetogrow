<?php

namespace Database\Seeders;

use App\Constants\PermissionSlug;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionSlug::toArray() as $permission) {
            Permission::query()->create([
                'name' => $permission,
            ]);
        }
    }
}
