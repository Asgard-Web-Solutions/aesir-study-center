@extends('layouts.app2')

@section('content')

    <x-card.main title="Your Profile">
        <x-card.mini title="Update Main Settings">

            <form action="{{ route('profile.update') }}" method="post">
                @csrf

                <x-form.text name="name" value="{{ $user->name }}" />
                <x-form.text name="email" type="email" value="{{ $user->email }}" />
                
                <x-card.buttons submitLabel="Save Profile" />
            </form>

        </x-card.mini>

        <x-card.mini title="Update Password">
            <form action="{{ route('profile.changepass') }}" method="POST">
                @csrf
                <x-form.text name="current_password" type="password" value="{{ $user->name }}" />
                <x-form.text name="new_password" type="password" value="{{ $user->name }}" />
                <x-form.text name="new_password_confirmation" type="password" value="{{ $user->name }}" />

                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>        
        </x-card.mini>
    </x-card.main>

@endsection
