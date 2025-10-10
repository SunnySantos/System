@extends('layouts.blank')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="card-body">
            <form action="{{ route('login.authenticate') }}" method="POST" class="">
                @csrf

                <div class="mb-6">
                    <img src="{{ Vite::image('logo.svg') }}" alt="Logo" class="mx-auto">
                </div>

                <div class="mb-4">
                    <label class="label block w-fit" for="email">Email</label>
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

                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <input type="checkbox" id="remember" name="remember" class="checkbox checkbox-sm me-2">
                        <label for="remember" class="cursor-pointer select-none">Remember me</label>
                    </div>
                    <a class="link link-hover">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-soft btn-primary w-full"><x-lucide-log-in /> Login</button>
            </form>
        </div>
    </div>
</div>
@endsection