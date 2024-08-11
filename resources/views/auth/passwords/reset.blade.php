@extends('layouts.app2')

@section('content')

    <x-card.main title="Confirm Password">
        <x-card.mini>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <x-form.text name="email" label="E-mail Address" type="email" />
                <x-form.text name="password" label="Password" type="password" />
                <x-form.text name="password_confirmation" label="Confirm Password" type="password" />

                <x-card.buttons submitLabel="Reset Password" />
            </form>
        </x-card.mini>
    </x-card.main>

@endsection
