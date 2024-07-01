<?php

namespace App\Http\Controllers;

use App\Actions\User\StoreAction;
use App\Actions\User\DeleteAction;
use App\Constants\PolicyName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize(PolicyName::VIEW_ANY, User::class);
        $users = User::all();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $this->authorize(PolicyName::CREATE, User::class);
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request, StoreAction $storeAction): RedirectResponse
    {
        $user = $storeAction->execute($request->validated());
        $user->roles()->attach($request->role);
        event(new Registered($user));
        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
    }

    public function destroy(User $user, DeleteAction $deleteAction): RedirectResponse
    {
        $this->authorize(PolicyName::DELETE, $user);
        $deleteAction->execute($user);
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente');
    }

    public function dashboard(): \Inertia\Response | RedirectResponse
    {
        return Inertia::render('Dashboard', []);
    }
}
