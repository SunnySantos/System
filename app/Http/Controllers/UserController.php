<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\BulkDeleteUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::search($request)->paginate(5)->withQueryString();

        return view('users.index', compact('users', 'request'));
    }

    public function show(int $id): View
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function bulkDelete(BulkDeleteUserRequest $request): RedirectResponse
    {
        $ids = explode(',', $request->ids);
        $singular = 'user';
        $plural = 'users';

        User::whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected ' . (sizeof($ids) > 1 ? $plural : $singular) . ' deleted successfully!');
    }
}
