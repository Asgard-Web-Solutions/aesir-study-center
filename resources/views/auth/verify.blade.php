@extends('layouts.app2')

@section('content')

    @if (session('resent'))
        <x-card.main>
            <div role="alert" class="my-2 alert alert-success d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <div>
                    <strong class="font-bold me-2">Success!</strong>
                    <span>A fresh verification link has been sent to your email address.</span>
                </div>
            </div>
        </x-card.main>
    @endif


    <x-card.main title="Please Verify Your Account">
        <x-card.mini>
            <x-text.main>{{ __('Before proceeding, please check your email for a verification link.') }}</x-text.main>
            <x-text.main>{{ __('If you did not receive the email') }}, <a class="text-blue-500 no-underline cursor-pointer hover:text-blue-700" onclick="event.preventDefault(); document.getElementById('resend-verification-form').submit();">{{ __('click here to request another') }}</a>.</x-text.main>

            <form id="resend-verification-form" method="POST" action="{{ route('verification.resend') }}" class="hidden">
                @csrf
            </form>

        </x-card.mini>
    </x-card.main>

@endsection
