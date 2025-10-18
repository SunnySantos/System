@extends('layouts.blank')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md text-center">
        <h2 class="text-2xl font-bold mb-4">Verify Your Email Address</h2>

        @if (session('resent'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded-lg">
            {{ session('resent') }}
        </div>
        @endif

        <p class="mb-6 text-gray-600">
            Before proceeding, please check your email for a verification link.<br>
            If you did not receive the email, click the button below to request another.
        </p>

        <form class="inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                Resend Verification Email
            </button>
        </form>
    </div>
</div>
@endsection