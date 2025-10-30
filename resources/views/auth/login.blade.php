@extends('layouts.blank')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="card-body">
            <form action="{{ route('login') }}" method="POST" class="">
                @csrf

                <div class="mb-6">
                    <img src="{{ Vite::image('logo.svg') }}" alt="Logo" class="mx-auto">
                </div>

                @if (session('status'))
                <x-alert type="success" message="{{ session('status') }}" />
                @endif

                <div class="mb-4">
                    <label class="label block w-fit" for="email">Email</label>
                    <input type="email" id="email" name="email" class="input w-full" value="{{ old('email') }}" placeholder="Email">
                    @error('email')
                    <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="label block w-fit" for="password">Password</label>
                    <div class="relative">
                        <input type="password" id="password" class="input w-full" name="password" placeholder="Password">
                        <label class="swap absolute top-[10px] right-[12px] z-10">
                            <input type="checkbox" id="show_hide_password" />
                            <x-lucide-eye-off class="swap-on" />
                            <x-lucide-eye class="swap-off" />
                        </label>
                    </div>
                    @error('password')
                    <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
                    @endif
                </div>

                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <input type="checkbox" id="remember" name="remember" class="checkbox checkbox-sm me-2 mb-1">
                        <label for="remember" class="cursor-pointer select-none">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="link link-hover">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-soft btn-primary w-full"><x-lucide-log-in /> Login</button>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    window.addEventListener('load', function() {
        window.showHidePassword([{
            fieldId: 'password',
            toggleId: 'show_hide_password'
        }])
    });
</script>
@endpush