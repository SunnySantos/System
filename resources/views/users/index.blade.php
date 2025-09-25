@extends('layouts.dashboard')

@section('title', 'Users')

@section('page_title', 'Users')

@section('content')

@if (session('success'))
<x-alert type="success" message="{{ session('success') }}" />
@endif

<a href="{{ route('users.create') }}" class="btn btn-soft btn-primary mb-4">Add New</a>

<div class="flex justify-end">
    <form action="{{ route('users.index') }}" method="GET" class="block ms-auto">
        <label class="input">
            <x-lucide-search />
            <input type="search" name="search" id="search" value="{{ request('search') }}" placeholder="Search" />
        </label>
    </form>
</div>

<div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 my-4">
    <table class="table">
        <!-- head -->
        <thead>
            <tr>
                <th>
                    <label>
                        <input type="checkbox" class="checkbox" />
                    </label>
                </th>
                <th>
                    <x-sortable-column column="name" label="Name" />
                </th>
                <th>
                    <x-sortable-column column="email" label="Email" />
                </th>
                <th>Role</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <th>
                    <label>
                        <input type="checkbox" class="checkbox" />
                    </label>
                </th>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="avatar">
                            <div class="mask mask-squircle h-12 w-12">
                                <img
                                    src="{{ asset('storage/profile_pictures/default_avatar.jpg') }}"
                                    alt="{{ $user->name }} Avatar" />
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">{{ $user->name }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td>Administrator</td>
                <td>
                    <span class="badge badge-soft badge-success">Active</span>
                </td>
                <th>
                    <div class="tooltip tooltip-bottom" data-tip="View">
                        <a href="{{ route('users.show', $user->id) }}" class="text-[#297AFF]">
                            <x-lucide-eye />
                        </a>
                    </div>
                    <div class="tooltip tooltip-bottom" data-tip="Delete">
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-red-500">
                                <x-lucide-trash />
                            </button>
                        </form>
                    </div>
                </th>
            </tr>
            @endforeach
        </tbody>
        <!-- foot -->
        <tfoot>
            <tr>
                <th></th>
                <th>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Name
                    </a>
                </th>
                <th>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'email', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Email
                    </a>
                </th>
                <th>Role</th>
                <th>Status</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

{{ $users->links() }}
@endsection