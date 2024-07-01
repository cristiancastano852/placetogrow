<?php

namespace App\Http\Controllers;

use App\Actions\RolePermissions\EditPermissionsAction;
use App\Actions\RolePermissions\UpdateRolesAction;
use App\Constants\PolicyName;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $this->authorize(PolicyName::VIEW_ANY_PERMISSIONS, User::class);
        $users = User::all();
        $roles = Role::all();
        return view('admin.rolePermission.index', compact('users', 'roles'));
    }

    public function managePermissions()
    {
        $this->authorize(PolicyName::VIEW_ANY_PERMISSIONS, User::class);
        $roles = Role::all();
        $permissions = Permission::all();
        $rolesHasPermissions = Role::with('permissions')->get();
        return view('admin.rolePermission.permissions', compact('roles', 'permissions'));
    }


    public function editPermissions(Role $role, Request $request, EditPermissionsAction $editPermissionsAction)
    {
        $user = $request->user();
        $this->authorize(PolicyName::UPDATE, $user);

        $permissions = $request->input('permissions', []);
        $editPermissionsAction->execute($role, $permissions);

        return back()->with('success', 'Permisos actualizados correctamente.');
    }

    public function update(User $user, Request $request, UpdateRolesAction $updateRolesAction)
    {
        $this->authorize(PolicyName::UPDATE, $user);
        $roles = $request->input('role', []);
        $updateRolesAction->execute($user, $roles);
        return redirect()->route('users.index')->with('success', 'El rol del usuario ha sido actualizado correctamente.');
    }
}
