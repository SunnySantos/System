@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('page_title', 'Edit User')

@section('content')

@if($errors->has('error'))
<x-alert type="error" message="{{ $errors->first('error') }}" />
@endif

<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT') {{-- Important for updating --}}

    {{ $user->profile->first_name }}
    {{ $user->profile->last_name }}

    <div class="divider divider-start">Personal Information</div>

    <div class="mb-4">
        <label class="label block w-fit" for="first_name">First Name <span class="text-red-500">*</span></label>
        <input type="text" id="first_name" name="first_name" class="input w-full" value="{{ old('first_name', isset($user->profile->first_name) ? $user->profile->first_name : '') }}" placeholder="First Name">
        @error('first_name')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="middle_name">Middle Name</label>
        <input type="text" id="middle_name" name="middle_name" class="input w-full" value="{{ old('middle_name', isset($user->profile->middle_name) ? $user->profile->middle_name : '') }}" placeholder="Middle Name">
        @error('middle_name')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="last_name">Last Name <span class="text-red-500">*</span></label>
        <input type="text" id="last_name" name="last_name" class="input w-full" value="{{ old('last_name', isset($user->profile->last_name) ? $user->profile->last_name : '') }}" placeholder="Last Name">
        @error('last_name')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="phone">Phone</label>
        <input type="text" id="phone" name="phone" class="input w-full" value="{{ old('phone', isset($user->profile->phone) ? $user->profile->phone : '') }}" placeholder="Phone">
        @error('phone')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="divider divider-start mt-8">Address Information</div>

    <div class="mb-4">
        <label class="label block w-fit" for="country">Country <span class="text-red-500">*</span></label>
        <select class="w-full" id="country" name="country" data-value="{{ old('country', isset($countryId) ? $countryId : '') }}">
        </select>
        @error('country')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="street_address">Street Address <span class="text-red-500">*</span></label>
        <input type="text" id="street_address" name="street_address" class="input w-full" value="{{ old('street_address', isset($user->profile->street_address) ? $user->profile->street_address : '') }}" placeholder="Street Address">
        @error('street_address')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="state">State <span class="text-red-500">*</span></label>
        <select class="w-full" id="state" name="state" data-value="{{ old('state', isset($stateId) ? $stateId : '') }}"></select>
        @error('state')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="city">City <span class="text-red-500">*</span></label>
        <select class="w-full" id="city" name="city" data-value="{{ old('city', isset($cityId) ? $cityId : '') }}"></select>
        @error('city')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="zip">ZIP Code <span class="text-red-500">*</span></label>
        <input type="text" id="zip" name="zip" class="input w-full" value="{{ old('zip', isset($user->profile->zip) ? $user->profile->zip : '') }}" placeholder="ZIP Code">
        @error('zip')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="divider divider-start mt-8">Account Information</div>

    <div class="mb-4">
        <label class="label block w-fit" for="email">Email <span class="text-red-500">*</span></label>
        <input type="email" id="email" name="email" class="input w-full" value="{{ old('email', isset($user->email) ? $user->email : '') }}" placeholder="Email">
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

    <button type="submit" class="btn btn-primary">Update User</button>
</form>
@endsection