@extends('layouts.dashboard')

@section('title', 'Add New User')

@section('page_title', 'Add New User')

@section('content')

@if($errors->has('error'))
<x-alert type="error" message="{{ $errors->first('error') }}" />
@endif

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="divider divider-start">Personal Information</div>

    <div class="mb-4">
        <label class="label block w-fit" for="first_name">First Name <span class="text-red-500">*</span></label>
        <input type="text" id="first_name" name="first_name" class="input w-full" value="{{ old('first_name') }}" placeholder="First Name" autocomplete="true">
        @error('first_name')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="middle_name">Middle Name</label>
        <input type="text" id="middle_name" name="middle_name" class="input w-full" value="{{ old('middle_name') }}" placeholder="Middle Name">
        @error('middle_name')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="last_name">Last Name <span class="text-red-500">*</span></label>
        <input type="text" id="last_name" name="last_name" class="input w-full" value="{{ old('last_name') }}" placeholder="Last Name">
        @error('last_name')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="phone">Phone</label>
        <input type="text" id="phone" name="phone" class="input w-full" value="{{ old('phone') }}" placeholder="Phone" autocomplete="true">
        @error('phone')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="divider divider-start mt-8">Address Information</div>

    <div class="mb-4">
        <label class="label block w-fit" for="country">Country <span class="text-red-500">*</span></label>
        <input type="hidden" id="country_name" name="country_name" value="{{ old('country_name', '') }}">
        <select class="w-full" id="country" name="country" autocomplete="true">
            @if(old('country') && old('country_name'))
            <option value="{{ old('country', '') }}" selected>{{ old('country_name', '') }}</option>
            @endif
        </select>
        @error('country')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="street_address">Street Address <span class="text-red-500">*</span></label>
        <input type="text" id="street_address" name="street_address" class="input w-full" value="{{ old('street_address') }}" placeholder="Street Address" autocomplete="true">
        @error('street_address')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="state">State <span class="text-red-500">*</span></label>
        <input type="hidden" id="state_name" name="state_name" value="{{ old('state_name', '') }}">
        <select class="w-full" id="state" name="state">
            @if(old('state') && old('state_name'))
            <option value="{{ old('state', '') }}" selected>{{ old('state_name', '') }}</option>
            @endif
        </select>
        @error('state')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="city">City <span class="text-red-500">*</span></label>
        <input type="hidden" id="city_name" name="city_name" value="{{ old('city_name', '') }}">
        <select class="w-full" id="city" name="city">
            @if(old('city') && old('city_name'))
            <option value="{{ old('city', '') }}" selected>{{ old('city_name', '') }}</option>
            @endif
        </select>
        @error('city')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="mb-4">
        <label class="label block w-fit" for="zip">ZIP Code <span class="text-red-500">*</span></label>
        <input type="text" id="zip" name="zip" class="input w-full" value="{{ old('zip') }}" placeholder="ZIP Code">
        @error('zip')
        <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
        @endif
    </div>

    <div class="divider divider-start mt-8">Account Information</div>

    <div class="mb-4">
        <label class="label block w-fit" for="email">Email <span class="text-red-500">*</span></label>
        <input type="email" id="email" name="email" class="input w-full" value="{{ old('email') }}" placeholder="Email" autocomplete="true">
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

    <button type="submit" class="btn btn-soft btn-primary"><x-lucide-plus /> Create User</button>
</form>
@endsection