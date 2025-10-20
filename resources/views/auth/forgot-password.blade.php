@extends('layouts.blank')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="card bg-base-100 w-full max-w-sm shadow-2xl">
        <div class="card-body">
            @if (session('success'))
            <x-alert type="success" message="{{ session('success') }}" />
            @elseif (session('throttled'))
            <x-alert type="warning" message="{{ session('throttled') }}" />
            @endif

            <h2 class="text-2xl font-bold mb-4">Forgot your password?</h2>

            <p class="mb-6 text-gray-600">
                Enter your email address and we’ll send you a link to reset your password.
            </p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label class="label block w-fit" for="email">Email</label>
                    <input type="email" id="email" name="email" class="input w-full" value="{{ old('email') }}" placeholder="Email">
                    @error('email')
                    <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
                    @endif
                </div>

                <button type="submit" class="btn btn-soft btn-primary w-full"><x-lucide-send /> Send Password Reset Link</button>
            </form>
        </div>
    </div>
</div>
@endsection