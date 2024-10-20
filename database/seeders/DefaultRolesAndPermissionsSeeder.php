<?php

namespace Database\Seeders;

use App\Constants\PermissionSlug;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DefaultRolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $baseRolesPermission = [
            [
                'name' => 'Admin',
                'permissions' => [
                    PermissionSlug::MICROSITES_VIEW_ANY->value,
                    PermissionSlug::MICROSITES_VIEW->value,
                    PermissionSlug::MICROSITES_CREATE->value,
                    PermissionSlug::MICROSITES_UPDATE->value,
                    PermissionSlug::MICROSITES_DELETE->value,
                    PermissionSlug::USERS_VIEW_ANY->value,
                    PermissionSlug::USERS_VIEW->value,
                    PermissionSlug::USERS_CREATE->value,
                    PermissionSlug::USERS_UPDATE->value,
                    PermissionSlug::USERS_DELETE->value,
                    PermissionSlug::ROLES_VIEW_ANY->value,
                    PermissionSlug::ROLES_VIEW->value,
                    PermissionSlug::ROLES_UPDATE->value,
                    PermissionSlug::ROLE_PERMISSION_VIEW->value,
                    PermissionSlug::ROLE_PERMISSION_UPDATE->value,
                    PermissionSlug::TRANSACTIONS_VIEW_ANY->value,
                    PermissionSlug::TRANSACTIONS_VIEW->value,
                    PermissionSlug::IMPORT_INVOICES->value,
                    PermissionSlug::INVOICES_VIEW_ANY->value,
                ],
            ],
            [
                'name' => 'Customer',
                'permissions' => [
                    PermissionSlug::IMPORT_INVOICES->value,
                    PermissionSlug::MICROSITES_VIEW_ANY->value,
                    PermissionSlug::MICROSITES_VIEW->value,
                    PermissionSlug::MICROSITES_CREATE->value,
                    PermissionSlug::MICROSITES_UPDATE->value,
                    PermissionSlug::MICROSITES_DELETE->value,
                    PermissionSlug::TRANSACTIONS_VIEW_ANY->value,
                    PermissionSlug::TRANSACTIONS_VIEW->value,
                    PermissionSlug::INVOICES_VIEW_ANY->value,
                ],
            ],
            [
                'name' => 'Guests',
                'permissions' => [
                    PermissionSlug::TRANSACTIONS_VIEW_ANY->value,
                    PermissionSlug::TRANSACTIONS_VIEW->value,
                ],
            ],
        ];

        foreach ($baseRolesPermission as $role) {
            $rol = Role::query()->updateOrCreate([
                'name' => $role['name'],
            ]);

            $rol->syncPermissions($role['permissions']);
        }

        User::query()->find(1)
            ->assignRole('Admin');

        User::query()->find(2)
            ->assignRole('Customer');

        User::query()->find(3)
            ->assignRole('Guests');
    }
}
