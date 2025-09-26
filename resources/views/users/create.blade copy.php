@extends('layouts.dashboard')

@section('title', 'Add New User')

@section('page_title', 'Add New User')

@section('content')
<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="label block w-fit" for="first_name">First Name <span class="text-red-500">*</span></label>
            <input type="text" id="first_name" name="first_name" class="input w-full" value="{{ old('first_name') }}" placeholder="First Name">
            @error('first_name')
            <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
            @endif
        </div>

        <div>
            <label class="label block w-fit" for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" class="input w-full" value="{{ old('middle_name') }}" placeholder="Middle Name">
            @error('middle_name')
            <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
            @endif
        </div>

        <div>
            <label class="label block w-fit" for="last_name">Last Name <span class="text-red-500">*</span></label>
            <input type="text" id="last_name" name="last_name" class="input w-full" value="{{ old('last_name') }}" placeholder="Last Name">
            @error('last_name')
            <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
            @endif
        </div>
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="email">Email</label>
        <input type="email" id="email" name="email" class="input w-full" value="{{ old('email') }}" placeholder="Email">
        @error('email')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="password">Password</label>
        <input type="password" id="password" class="input w-full" name="password" placeholder="Password">
        @error('password')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" class="input w-full" name="password_confirmation" placeholder="Confirm Password">
        @error('password_confirmation')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Create User</button>
</form>
@endsection