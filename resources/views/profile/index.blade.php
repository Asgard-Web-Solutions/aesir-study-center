@extends('layouts.app2', ['heading' => 'Personal Profile'])

@section('content')

    <x-card.main title="Your Profile Settings">
        <x-card.mini title="Profile Image / Gravatar">
            <div class="block md:flex">
                <div>
                    <x-user.avatar size='lg'>{{ $user->gravatarUrl(256) }}</x-user.avatar>
                </div>
                <div class="pl-4 text-justify">
                    <x-text.main>Update your Profile Image here: <a href="https://gravatar.com/" class="link link-accent" target="_blank">Gravatar</a></x-text.main>

                    <x-help.box>
                        <x-help.text>One of the greatest things about the internet is when you can have a unified profile across many sites. One of the ways this can be done is by using <x-help.highlight>Gravatars</x-help.highlight>.</x-help.text>
                        <x-help.text><x-help.highlight color="normal">Gravatar</x-help.highlight> simply means <x-help.highlight color="info">Global Avatar</x-help.highlight>, and is a site that allows you to have the same avatar image across the web!</x-help.text>
                        <x-help.text>We think it's a pretty cool system! We do understand that acolytes may be used to uploading their own profile image to each website, but that is such a tedious process for everyone.</x-help.text>
                        <x-help.text>Imagine instead of updating your profile on the dozens of websites that you use, instead you just have to update it in one place and it goes everywhere!</x-help.text>
                        <x-help.text>Go on over to <a href="https://gravatar.com/" class="link link-accent" target="_blank">Gravatar.com</a> and create your <x-help.highlight color="info">free account</x-help.highlight>.</x-help.text>
                        <x-help.text>Make sure that you use the <x-help.highlight color="info">same email address</x-help.highlight> that you use at Acolyte Academy, and that's it!</x-help.text>
                        <x-help.text>Note that we only allow <x-help.highlight color="accent">G</x-help.highlight> and <x-help.highlight color="accent">PG</x-help.highlight> rated avatars at the academy.</x-help.text>
                        <x-help.text>If you have any questions, reach out to us at the <x-page.communitylink>Community Forums</x-page.communitylink>!</x-help.text>
                    </x-help.box>
                </div>
            </div>
        </x-card.mini>
        
        <x-card.mini title="Update Main Settings">
            <form action="{{ route('profile.update') }}" method="post">
                @csrf

                <x-form.text name="name" value="{{ $user->name }}" />
                <x-form.text name="email" type="email" value="{{ $user->email }}" />
                <x-form.checkbox name="showTutorial" label="Show Query the Help Owl Tutorials" checked="{{ $user->showTutorial }}" style="toggle" />
                
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
