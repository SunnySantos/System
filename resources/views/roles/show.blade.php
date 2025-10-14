@extends('layouts.dashboard')

@section('title', "{$role->name} Role")

@section('page_title', "{$role->name} Role")

@section('content')

<div class="flex items-center justify-end mt-4 mb-8">
    <div class="flex gap-4">
        @if(Auth::user()->canAccess('roles.edit'))
        <a href="{{ route('roles.edit', $role) }}" class="btn btn-soft btn-primary"><x-lucide-pen /> Edit</a>
        @endif
        @if(Auth::user()->canAccess('roles.destroy'))
        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this role?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-soft btn-error">
                <x-lucide-trash />
                Delete
            </button>
        </form>
        @endif
    </div>
</div>

@foreach ($modules as $module)
<div class="mb-8">
    <div class="divider divider-start capitalize">
        {{ $module }} Module
    </div>
    <ul class="list-none mb-4 flex gap-4 flex-wrap">
        @foreach ($authenticatedRoutes as $route => $details)
        @if ($details['module'] === $module)
        <li {{ $details['can_access'] ? '' : 'class=opacity-25' }}>
            @if ($details['can_access'])
            <x-lucide-circle-check class="inline-block mb-1 text-green-500" />
            @else
            <x-lucide-circle-x class="inline-block mb-1 text-red-500" />
            @endif
            <span>{{ $details['label'] }}</span>
        </li>
        @endif
        @endforeach
    </ul>
</div>
@endforeach

@endsection