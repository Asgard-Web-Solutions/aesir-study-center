@extends('layouts.app2')

@section('content')

    <x-card.main title="User Manager" size="lg">
        <x-card.mini >

            <form action="{{ route('admin.user-update', $user) }}" method="post">
                @csrf
                <x-form.text name="name" value="{{ $user->name }}" />
                <x-form.text name="email" type="email" value="{{ $user->email }}" />
                <x-form.checkbox name="showTutorial" label="Show Query the Help Owl Tutorials" checked="{{ $user->showTutorial }}" />
                
                <x-card.buttons submitLabel="Update User" />
            </form>
        </x-card.mini>
    </x-card.main>

    <x-card.main title="User's Exams">
        <x-card.mini>
            @foreach ($user->exams as $exam)
                {{ $exam->name }}
            @endforeach
        </x-card.mini>
    </x-card.main>

@endsection
