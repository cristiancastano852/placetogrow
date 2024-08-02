<?php

namespace App\Http\Controllers;

use App\Actions\User\StoreAction;
use App\Constants\PolicyName;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize(PolicyName::VIEW_ANY, User::class);
        $users = User::all();
        $users = User::with('roles:name')->get();
        $roles = Role::all();

        return Inertia::render('Users/UsersView', [
            'users' => $users,
            'roles' => $roles,

        ]);
        // return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $this->authorize(PolicyName::CREATE, User::class);
        $roles = Role::all();

        // return view('users.create', compact('roles'));
        return Inertia::render('Users/UsersCreate', [
            'roles' => $roles,
        ]);

    }

    public function store(StoreUserRequest $request, StoreAction $storeAction): RedirectResponse
    {
        $user = $storeAction->execute($request->validated());
        $user->roles()->attach($request->role);
        event(new Registered($user));

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize(PolicyName::DELETE, $user);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente');
    }

    public function dashboard(): \Inertia\Response|RedirectResponse
    {
        return Inertia::render('Dashboard', []);
    }
}
