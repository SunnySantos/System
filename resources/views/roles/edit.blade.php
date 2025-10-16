@extends('layouts.dashboard')

@section('title', "Edit {$role->name} Role")

@section('page_title', "Edit {$role->name} Role")

@section('content')

@if ($errors->any())
<ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif


<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT') {{-- Important for updating --}}

    <div class="mt-3 mb-8">
        <label class="label block w-fit" for="name">Role Name <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" class="input w-full" value="{{ old('name', isset($role->name) ? $role->name : '') }}" placeholder="Role Name" autocomplete="true">
        @error('name')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    @foreach ($modules as $module)
        <div class="mb-8">
            <div class="divider divider-start capitalize">
                {{ $module }} Module
            </div>
            <ul class="list-none mb-4 flex gap-4 flex-wrap">
                @foreach ($authenticatedRoutes as $route => $value)
                    @if ($value['module'] === $module && array_key_exists($value['action'], App\Models\Role::ROUTE_ACTIONS))
                        <li>
                            <input type="checkbox" class="checkbox checkbox-sm mr-2" id="{{ str_replace('.', '_', $route) }}" name="role_accesses[]" value="{{ $route }}" {{ $value['can_access'] ? 'checked' : '' }} />
                            <label for="{{ str_replace('.', '_', $route) }}">{{ $value['label'] }}</label>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endforeach

    <button type="submit" class="btn btn-soft btn-primary"><x-lucide-refresh-cw /> Update Role</button>
</form>
@endsection