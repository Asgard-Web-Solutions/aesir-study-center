@extends('layouts.app2')

@section('content')

    <x-card.main title="Your Profile">
        <x-card.mini title="Profile Image">
            <div class="block md:flex">
                <div>
                    <x-user.avatar size='lg'>{{ $user->gravatarUrl(256) }}</x-user.avatar>
                </div>
                <div class="pl-4 text-justify">
                    <x-text.main>We use your <a href="https://gravatar.com/" class="link link-accent">Gravatar</a> (Global Avatar) for your profile image. This is based on the email address that you give us to look up your Gravatar profile.</x-text.main>
                    <x-text.main>To change your profile picture, please update your settings in the Gravatar website. Note that we only allow <span class="text-accent">G</span> and <span class="text-accent">PG</span> rated images.</x-text.main>
                </div>
            </div>
        </x-card.mini>
        
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

                <x-card.buttons submitLabel="Change Password" />
            </form>        
        </x-card.mini>
    </x-card.main>

@endsection
