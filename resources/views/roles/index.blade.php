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
                    @if ($role->users_count > 0)
                    <div class="tooltip tooltip-bottom" data-tip="Delete">
                        <button class="cursor-pointer text-red-500 delete-modal-btn" data-role-id="{{ $role->id }}"><x-lucide-trash /></button>
                    </div>
                    @else
                    <div class="tooltip tooltip-bottom" data-tip="Delete">
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this role?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer text-red-500">
                                <x-lucide-trash />
                            </button>
                        </form>
                    </div>
                    @endif
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

<dialog id="delete_role_modal" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold">Reassign Users Before Deleting Role</h3>
        <div class="modal-action block">
            <p class="mb-4">This role is currently assigned to users. Please select a new role for them before deleting it.</p>
            <form action="{{ route('roles.update-user-role') }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this role?');">
                @csrf
                @method('DELETE')

                <input type="hidden" id="role_id" name="role_id" value="">

                <div class="mb-4">
                    <label class="label block w-fit" for="role">Role <span class="text-red-500">*</span></label>
                    <select class="w-full tom-select" id="new_role_id" name="new_role_id" autocomplete="off">
                        <option value="">Select Role</option>
                        @foreach(App\Models\Role::all() as $_role)
                        <option value="{{ $_role->id }}">{{ $_role->name }}</option>
                        @endforeach
                    </select>
                    @error('new_role_id')
                    <div class="text-red-500 dark:text-red-400 error">{{ $message }}</div>
                    @endif
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-soft btn-error">
                        <x-lucide-trash />
                        Delete
                    </button>
                    <button type="button" class="btn" onclick="delete_role_modal.close()">Close</button>
                </div>
            </form>
        </div>
    </div>
</dialog>

@endsection