@extends('layouts.dashboard')

@section('title', 'User Information')

@section('page_title', 'User Information')

@section('content')

<div class="flex items-center justify-between">
    <div class="flex gap-4 items-center">
        <div class="avatar">
            <div class="w-24 rounded">
                <img src="{{ asset('storage/profile_pictures/' . $user->profile->file_base_name . $user->profile->file_extension) }}" alt="{{ $user->name }} Avatar" />
            </div>
        </div>
        <div>
            <div class="font-bold">{{ $user->name }}</div>
            <div class="text-sm opacity-50">Administrator</div>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('users.edit', $user) }}" class="btn btn-soft btn-primary mb-4"><x-lucide-user-pen /> Edit</a>
        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this user?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-soft btn-error">
                <x-lucide-trash />
                Delete
            </button>
        </form>
    </div>
</div>

<div class="divider divider-start">Personal Information</div>
<div class="flex items-center gap-2"><x-lucide-phone /> {{ $user->profile->phone }}</div>

<div class="divider divider-start mt-8">Address Information</div>
<div class="flex items-center gap-2"><x-lucide-map-pin /> {{ $user->profile->street_address }}, {{ $user->profile->city }}, {{ $user->profile->state }} {{ $user->profile->zip }}, {{ $user->profile->country }}</div>

<div class="divider divider-start mt-8">Account Information</div>
<div class="flex items-center gap-2 mb-2"><x-lucide-mail /> {{ $user->email }}</div>
<div class="flex items-center gap-2"><x-lucide-circle-user-round /> <span class="badge badge-soft badge-success">Active</span></div>



@endsection