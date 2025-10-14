@extends('layouts.dashboard')

@section('title', 'Users')

@section('page_title', 'Users')

@section('content')

@if (session('success'))
<x-alert type="success" message="{{ session('success') }}" />
@endif

<a href="{{ route('users.create') }}" class="btn btn-soft btn-primary mb-4">
    <x-lucide-user-plus />
    Add New
</a>

<div class="flex">
    <x-bulk-delete-form singular="user" plural="users" route="users.bulk-delete" />
    <x-table-search />
</div>

<div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 my-4">
    <table class="table" id="data_table">
        <!-- head -->
        <thead>
            <tr>
                @if (Auth::user()->canAccess('users.destroy'))
                <th width="50px">
                    <label>
                        <input type="checkbox" class="checkbox" id="select_all_checkboxes" />
                    </label>
                </th>
                @endif
                <th>
                    <x-sortable-column column="name" label="Name" />
                </th>
                <th>
                    <x-sortable-column column="email" label="Email" />
                </th>
                <th>Role</th>
                <th>Status</th>
                @if (Auth::user()->canAccess('users.show') || Auth::user()->canAccess('users.destroy'))
                <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <th>
                    <label>
                        <input type="checkbox" class="checkbox" name="id[]" value="{{ $user->id }}" />
                    </label>
                </th>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="avatar">
                            <div class="mask mask-squircle h-12 w-12">
                                <img
                                    src="{{ asset('storage/profile_pictures/' . $user->file_base_name . $user->file_extension) }}"
                                    alt="{{ $user->name }} Avatar" />
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">{{ $user->name }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('roles.show', $user->role_id) }}">
                        {{ $user->role->name ?? '' }}
                    </a>
                </td>
                <td>
                    <span class="badge badge-soft badge-success">Active</span>
                </td>
                @if (Auth::user()->canAccess('users.show') || Auth::user()->canAccess('users.destroy'))
                <th>
                    @if (Auth::user()->canAccess('users.show'))
                    <div class="tooltip tooltip-bottom" data-tip="View">
                        <a href="{{ route('users.show', $user->id) }}" class="text-[#297AFF]">
                            <x-lucide-eye />
                        </a>
                    </div>
                    @endif

                    @if (Auth::user()->canAccess('users.destroy'))
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
                    @endif
                </th>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No users</td>
            </tr>
            @endforelse
        </tbody>
        <!-- foot -->
        <tfoot>
            <tr>
                @if (Auth::user()->canAccess('users.destroy'))
                <th width="50px">
                    <label>
                        <input type="checkbox" class="checkbox" id="select_all_checkboxes_footer" />
                    </label>
                </th>
                @endif
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
        </tfoot>
    </table>
</div>

{{ $users->links() }}
@endsection