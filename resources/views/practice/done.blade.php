@extends('layouts.app2', ['heading' => 'Practice - ' . $exam->name ])

@section('content')

    <x-card.main title="Review: {{ $exam->name }}">
        <x-card.mini>
            <h3 class="text-3xl text-accent">Review Session Complete</h3>
            <br />
            <x-text.main>You successfully completed the review session. Start a new session?</x-text.main>
        </x-card.mini>
    </x-card.main>

    <x-card.main>
        <div class="flex w-full">
            <div class="w-1/2">
                <a href="{{ route('profile.exams') }}" class="btn btn-secondary">View Your Exams</a>
            </div>
            <div class="w-1/2 text-right">
                <a href="{{ route('practice.start', $exam) }}" class="btn btn-primary">Start New Review Session</a>
            </div>
        </div>
    </x-card.main>

@endsection
