@extends('layouts.app2')

@section('content')

    <x-card.main title="Register Account">
        <x-card.mini>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <x-form.text name="name" label="Username" helptext="This will be visible to other users." />
                <x-form.text name="email" label="E-mail Address" type="email" />
                <x-form.text name="password" label="Password" type="password" />
                <x-form.text name="password_confirmation" label="Confirm Password" type="password" />

                <x-card.buttons submitLabel="Register" />
            </form>
        </x-card.mini>

        <x-text.main>{{ __('Already have an account?') }} <a class="text-blue-500 no-underline hover:text-blue-700" href="{{ route('login') }}">{{ __('Login') }}</a></x-text.main>
    </x-card.main>

@endsection
