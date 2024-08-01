<?php

namespace App\Http\Controllers;

use App\Actions\RolePermissions\EditPermissionsAction;
use App\Constants\PolicyName;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function managePermissions()
    {
        $this->authorize(PolicyName::VIEW_ANY_PERMISSIONS, User::class);
        $roles = Role::all();
        $permissions = Permission::all();
        $rolesHasPermissions = Role::with('permissions')->get();
        $rolesWithPermissionsRelations = Role::with('permissions')->get();

        //inertia vue return
        return Inertia::render('Roles/RolePermission', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolesHasPermissions' => $rolesHasPermissions,
        ]);

        // return view('admin.rolePermission.permissions', compact('roles', 'permissions'));
    }

    public function editPermissions(Role $role, Request $request, EditPermissionsAction $editPermissionsAction)
    {
        $user = $request->user();
        $this->authorize(PolicyName::UPDATE, $user);

        $permissions = $request->input('permissions', []);
        $editPermissionsAction->execute($role, $permissions);

        return back()->with('success', 'Permisos actualizados correctamente.');
    }

    public function update(User $user, Request $request)
    {
        $this->authorize(PolicyName::UPDATE, $user);
        $roles = $request->input('role', []);
        $user->syncRoles($roles);

        return redirect()->route('users.index')->with('success', 'El rol del usuario ha sido actualizado correctamente.');
    }
}
