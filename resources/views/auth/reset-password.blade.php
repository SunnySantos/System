@extends('layouts.blank')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="card bg-base-100 w-full max-w-sm shadow-2xl">
        <div class="card-body">
            {{-- Success message --}}
            @if (session('success'))
            <x-alert type="success" message="{{ session('success') }}" />
            @elseif (session('error'))
            <x-alert type="error" message="{{ session('error') }}" />
            @endif

            <h2 class="text-2xl font-bold mb-4">Reset Your Password</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                {{-- Hidden token (Laravel requires this) --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div class="mb-4">
                    <label class="label block w-fit" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', request('email')) }}"
                        class="input w-full" autofocus>
                </div>

                {{-- New Password --}}
                <div class="mb-4">
                    <label class="label block w-fit" for="password">New Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" class="input w-full">
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

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label class="label block w-fit" for="password_confirmation">Confirm Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" class="input w-full">
                        <label class="swap absolute top-[10px] right-[12px] z-10">
                            <input type="checkbox" id="show_hide_password_confirmation" />
                            <x-lucide-eye-off class="swap-on" />
                            <x-lucide-eye class="swap-off" />
                        </label>
                    </div>
                    @error('password_confirmation')
                    <div class="text-red-500 dark:text-red-400">{{ $message }}</div>
                    @endif
                </div>

                <button type="submit" class="btn btn-soft btn-primary w-full">
                    <x-lucide-key-round />
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection



@push('scripts')
<script>
    const showHidePassword = document.getElementById('show_hide_password');
    const showHidePasswordConfirmation = document.getElementById('show_hide_password_confirmation');
    const passwordField = document.getElementById('password');
    const passwordConfirmationField = document.getElementById('password_confirmation');

    if (showHidePassword && passwordField) {
        showHidePassword.addEventListener('change', function(e) {
            passwordField.type = e.target.checked ? 'text' : 'password';
        });
    }

    if (showHidePasswordConfirmation && passwordConfirmationField) {
        showHidePasswordConfirmation.addEventListener('change', function(e) {
            passwordConfirmationField.type = e.target.checked ? 'text' : 'password';
        });
    }
</script>
@endpush