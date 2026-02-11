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
            <x-table.main>
                <x-table.body>
                    @foreach ($user->exams as $exam)
                        <x-table.row>
                            <x-table.cell><a href="{{ route('exam.view', $exam) }}" class="link link-primary">{{ $exam->name }}</a></x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-table.body>
            </x-table.main>
        </x-card.mini>
    </x-card.main>

@endsection
