<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $users = User::with('roles')->orderByDesc('id')->paginate(20);


        $roles = Role::all();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return inertia('Admin/Users/Create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);
        if ($request->has('role')) {
            $user->syncRoles($request->role);
        }

        return redirect()->route('users.index')
            ->with('success', 'Пользователь успешно создан.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load(['roles', 'documents', 'paymethods']);
        $roles = Role::all();

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $userData = Arr::except($validated, ['documents', 'password', 'role']);
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        if ($request->has('role')) {
            $user->syncRoles($request->role);
        }

        if (isset($validated['documents'])) {
            $user->documents()->updateOrCreate(
                ['user_id' => $user->id],
                $validated['documents']
            );
        }

        if (isset($validated['payment_method'])) {
            $user->paymethods()->updateOrCreate(
                ['user_id' => $user->id],
                $validated['payment_method']
            );
        }


        return redirect()->back()
            ->with('success', 'Пользователь успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Вы не можете удалить свой собственный аккаунт.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Пользователь успешно удален.');
    }

    public function syncRoles(User $user, Request $request)
    {
        $request->validate([
            'role' => 'string|exists:roles,name'
        ]);

        $user->syncRoles($request->role);

        return response()->json([
            'message' => 'Роли успешно синхронизированы',
            'user' => $user->load('roles')
        ]);
    }
}
