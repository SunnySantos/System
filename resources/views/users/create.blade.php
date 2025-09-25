@extends('layouts.dashboard')

@section('title', 'Add New User')

@section('page_title', 'Add New User')

@section('content')
<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="mb-4">
        <label class="label block w-fit" for="name">Name <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" class="input" value="{{ old('name') }}" placeholder="Name">
        @error('name')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="email">Email</label>
        <input type="email" id="email" name="email" class="input" value="{{ old('email') }}" placeholder="Email">
        @error('email')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="password">Password</label>
        <input type="password" id="password" class="input" name="password" placeholder="Password">
        @error('password')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" class="input" name="password_confirmation" placeholder="Confirm Password">
        @error('password_confirmation')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Create User</button>
</form>
@endsection