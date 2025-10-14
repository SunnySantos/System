@extends('layouts.dashboard')

@section('title', 'Add New Role')

@section('page_title', 'Add New Role')


@section('content')
<form action="{{ route('roles.store') }}" method="POST">
    @csrf

    <div class="mt-3 mb-8">
        <label class="label block w-fit" for="name">Role Name <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" class="input w-full" value="{{ old('name') }}" placeholder="Role Name" autocomplete="true">
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
            @foreach ($authenticatedRoutes as $route => $details)
            @if ($details['module'] === $module)
            <li>
                <input type="checkbox" class="checkbox checkbox-sm mr-2" id="{{ str_replace('.', '_', $route) }}" name="role_accesses[]" value="{{ $route }}" {{ $details['can_access'] ? 'checked' : '' }} />
                <label for="{{ str_replace('.', '_', $route) }}">{{ $details['label'] }}</label>
            </li>
            @endif
            @endforeach
        </ul>
    </div>
    @endforeach


    <button type="submit" class="btn btn-soft btn-primary"><x-lucide-plus /> Create Role</button>
</form>
@endsection