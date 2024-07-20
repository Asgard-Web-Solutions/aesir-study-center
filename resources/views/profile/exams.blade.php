@extends('layouts.app2')

@section('content')

    <x-card.buttons primaryLabel="Manage Your Own Exams" primaryAction="{{ route('profile.myexams') }}" secondaryLabel="Public Exams" secondaryAction="{{ route('tests') }}" />
    <x-card.main title="Your Exams" size="grid">
        @forelse ($records as $record)
            <x-card.mini title="{{ $record->name }}">
                <x-text.main label='Recent Average:'><span class="font-bold text-neutral-content">{{ $record->pivot->recent_average }}%</span></x-text.main>
                <div class="block w-full p-4 mb-3 rounded-md md:flex bg-base-100">
                    <div class="block m-1 md:flex badge badge-accent">Questions: {{ $record->questions->count() }}</div>
                    @if ($record->user )<div class="block m-1 badge badge-secondary md:flex">Author: {{ $record->user->name }}</div>@endif
                </div>
                @if ($record->questions->count())
                    <div class="flex w-full">
                        <div class="w-1/4 text-sm row text-secondary">Mastery:</div><div class="w-3/4"><progress class="w-52 progress progress-accent " value="{{ $record->pivot->mastery_mastered_count / $record->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                        <div class="w-1/4 text-sm row text-secondary">Proficient:</div><div class="w-3/4"><progress class="w-52 progress progress-secondary " value="{{ $record->pivot->mastery_proficient_count / $record->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                        <div class="w-1/4 text-sm row text-secondary">Familiar:</div><div class="w-3/4"><progress class="w-52 progress progress-success " value="{{ $record->pivot->mastery_familiar_count / $record->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                        <div class="w-1/4 text-sm row text-secondary">Apprentice:</div><div class="w-3/4"><progress class="w-52 progress progress-info " value="{{ $record->pivot->mastery_apprentice_count / $record->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <br />
                @endif

                @feature('flash-cards')
                    <x-card.buttons primaryLabel="Take Exam" primaryAction="{{ route('exam-session.start', $record) }}" secondaryLabel="Practice" secondaryAction="{{ route('practice-start', $record) }}"></x-card.buttons>
                @else
                    <x-card.buttons primaryLabel="Take Exam" primaryAction="{{ route('exam-session.start', $record) }}"></x-card.buttons>
                @endfeature
            </x-card.mini>
        @empty
            <x-text.main>You have not taken a test yet. <a href="{{ route('tests') }}" class="link-primary link">Find a Public Exam</a> or else <a href="{{ route('exam-create') }}">Create Your Own Exams</a>!</x-text.main>
        @endforelse
    </x-card.main>

@endsection
