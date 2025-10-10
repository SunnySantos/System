<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\BulkDeleteUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): View
    {
        $users = User::search($request)->paginate(5)->withQueryString();

        return view('users.index', compact('users', 'request'));
    }

    public function show(int $id): View
    {
        $user = User::with('profile')->findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->createWithProfile($request->validated());

            return redirect()
                ->route('users.index')
                ->with('success', 'User created successfully!');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Unable to create user. Please try again.']);
        }
    }

    public function edit(User $user): View
    {
        $country = null;
        $state = null;
        $city = null;

        if ($user->profile && $user->profile->country) {
            $country = Country::where('name', $user->profile->country)->first(['id', 'name']);
        }

        if ($user->profile && $user->profile->state) {
            $state = State::where('name', $user->profile->state)->first(['id', 'name']);
        }

        if ($user->profile && $user->profile->city) {
            $city = City::where('name', $user->profile->city)->first(['id', 'name']);
        }

        return view('users.edit', compact('user', 'country', 'state', 'city'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('profile')) {
                $file = $request->file('profile');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('profile_pictures', $filename, 'public');

                $validated['file_base_name'] = pathinfo($filename, PATHINFO_FILENAME);
                $validated['file_extension'] = '.' . pathinfo($filename, PATHINFO_EXTENSION);
            }

            $this->userService->updateWithProfile($validated, $user);

            return redirect()
                ->route('users.index')
                ->with('success', 'User updated successfully!');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Unable to update user. Please try again.' . $e->getMessage()]);
        }
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
