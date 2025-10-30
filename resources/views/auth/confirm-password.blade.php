@extends('layouts.blank')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="card-body">
            <form action="{{ route('password.confirm') }}" method="POST" class="">
                @csrf

                <div class="mb-6">
                    <img src="{{ Vite::image('logo.svg') }}" alt="Logo" class="mx-auto">
                </div>

                <div class="mb-4">
                    <p>For your security, please confirm your password before continuing.</p>
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

                <button type="submit" class="btn btn-soft btn-primary w-full"><x-lucide-log-in /> Confirm</button>
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