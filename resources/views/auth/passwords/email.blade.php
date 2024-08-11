@extends('layouts.app2')

@section('content')

    <x-card.main title="Register Account">
        <x-card.mini>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <x-form.text name="email" label="E-mail Address" type="email" />

                <x-card.buttons submitLabel="Send Password Reset Link" />
            </form>
        </x-card.mini>
        <x-text.main>{{ __('Back to Login') }} <a class="text-blue-500 no-underline hover:text-blue-700" href="{{ route('login') }}">{{ __('Login') }}</a></x-text.main>
    </x-card.main>

@endsection
