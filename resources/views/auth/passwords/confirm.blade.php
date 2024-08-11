@extends('layouts.app2')

@section('content')

    <x-card.main title="Confirm Password">
        <x-card.mini>
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <x-text.main>Please confirm your password before continuing.</x-text.main>

                <x-form.text name="password" label="Password" type="password" />
                <x-form.text name="password_confirmation" label="Confirm Password" type="password" />

                <x-card.buttons submitLabel="Confirm Password" />
            </form>
        </x-card.mini>

        <x-text.main><a class="text-blue-500 no-underline hover:text-blue-700" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a></x-text.main>
    </x-card.main>

@endsection
