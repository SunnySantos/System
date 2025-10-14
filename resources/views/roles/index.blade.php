@extends('layouts.dashboard')

@section('title', 'Roles')

@section('page_title', 'Roles')

@section('content')

@if (session('success'))
<x-alert type="success" message="{{ session('success') }}" />
@endif

@if (Auth::user()->canAccess('roles.create'))
<a href="{{ route('roles.create') }}" class="btn btn-soft btn-primary mb-4">
    <x-lucide-user-plus />
    Add New
</a>
@endif

<div class="flex">
    @if (Auth::user()->canAccess('roles.destroy'))
    <x-bulk-delete-form singular="user" plural="roles" route="roles.bulk-delete" />
    @endif
    <x-table-search />
</div>

<div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 my-4">
    <table class="table" id="data_table">
        <!-- head -->
        <thead>
            <tr>
                @if (Auth::user()->canAccess('roles.destroy'))
                <th width="50px">
                    <label>
                        <input type="checkbox" class="checkbox" id="select_all_checkboxes" />
                    </label>
                </th>
                @endif
                <th width="90%">
                    <x-sortable-column column="role" label="Role" />
                </th>
                <th>
                    User
                </th>
                @if (Auth::user()->canAccess('roles.show') || Auth::user()->canAccess('roles.destroy'))
                <th width="10%">Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
            <tr>
                @if (Auth::user()->canAccess('roles.destroy'))
                <th width="50px">
                    <label>
                        <input type="checkbox" class="checkbox" name="id[]" value="{{ $role->id }}" />
                    </label>
                </th>
                @endif
                <td width="90%">
                    <div class="font-bold">{{ $role->name }}</div>
                </td>
                <td>
                    <a href="{{ route('users.index', ['role' => $role->id]) }}">
                        {{ $role->users_count }}
                    </a>
                </td>
                @if (Auth::user()->canAccess('roles.show') || Auth::user()->canAccess('roles.destroy'))
                <th width="10%">
                    @if (Auth::user()->canAccess('roles.show'))
                    <div class="tooltip tooltip-bottom" data-tip="View">
                        <a href="{{ route('roles.show', $role->id) }}" class="text-[#297AFF]">
                            <x-lucide-eye />
                        </a>
                    </div>
                    @endif
                    @if (Auth::user()->canAccess('roles.destroy'))
                    <div class="tooltip tooltip-bottom" data-tip="Delete">
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
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
                <td colspan="5" class="text-center">No roles</td>
            </tr>
            @endforelse
        </tbody>
        <!-- foot -->
        <tfoot>
            <tr>
                @if (Auth::user()->canAccess('roles.destroy'))
                <th width="50px">
                    <label>
                        <input type="checkbox" class="checkbox" id="select_all_checkboxes_footer" />
                    </label>
                </th>
                @endif
                <th width="90%">
                    <x-sortable-column column="role" label="Role" />
                </th>
                <th>
                    Users
                </th>
                @if (Auth::user()->canAccess('roles.show') || Auth::user()->canAccess('roles.destroy'))
                <th width="10%">Action</th>
                @endif
            </tr>
        </tfoot>
    </table>
</div>

{{ $roles->links() }}
@endsection