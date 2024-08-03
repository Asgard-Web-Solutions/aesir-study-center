@extends('layouts.app2')

@section('content')
    <x-page.title>Acolyte Transcript</x-page.title>
    
    <x-card.main size="lg">
        <x-card.mini >
            <div class="block md:flex">
                <div>
                    <x-user.avatar size='lg'>{{ $user->gravatarUrl(256) }}</x-user.avatar>
                </div>
                <div class="ml-4 text-justify">
                    <span class="text-2xl font-bold text-primary">{{ $user->name }}</span>
                </div>
            </div>
        </x-card.mini>
    </x-card.main>

    <x-card.main title="Exam History">
        
        @forelse ($user->records as $record)
            @if ($record->visibility && ($record->pivot->times_taken > 0))
                <x-card.mini>
                    <div class="block w-full md:flex">
                        <div class="w-full md:w-3/4">
                            <h2 class="my-2 text-xl"><a href="{{ route('exam.view', $record) }}" class="font-bold no-underline link link-primary">{{ $record->name }}</a></h2>
                        </div>
                        <div class="w-full md:w-1/4 tooltip" data-tip="Mastery Level: {{ $mastery[$record->pivot->highest_mastery] }}">
                            <i class="
                                text-5xl
                                text-{{ config('color.' . strtolower($mastery[$record->pivot->highest_mastery])) }} 
                                {{ config('icon.' . strtolower($mastery[$record->pivot->highest_mastery])) }}
                                rounded-lg ring-2 p-1 ring-base-300
                            "></i>
                        </div>
                    </div>
                    
                    <div class="flex w-full py-2 my-2 rounded-lg bg-base-100">
                        @if ($record->user) <a href="{{ route('profile.view', $record->user) }}"><x-user.avatar size="tiny">{{ $record->user->gravatarUrl(64) }}</x-user.avatar></a> <a href="{{ route('profile.view', $record->user) }}" class="mr-2 link link-{{ config('color.author') }} tooltip" data-tip="Exam Author">{{ $record->user->name }}</a> @endif
                        <span class="mx-2 tooltip text-{{ config('color.question_count') }}" data-tip="Question Count"><i class="mr-1 text-lg {{ config('icon.question_count') }}"></i> {{ $record->questions->count() }}</span>
                        <span class="mx-2 tooltip text-{{ config('color.times_taken') }}" data-tip="Times Taken"><i class="mr-1 text-lg {{ config('icon.times_taken') }}"></i> {{ $record->pivot->times_taken }}</span>
                        <span class="mx-2 tooltip text-{{ config('color.recent_average') }}" data-tip="Recent Average"><i class="mr-1 text-lg {{ config('icon.recent_average') }}"></i> {{ $record->pivot->recent_average }}</span>
                    </div>
                </x-card.mini>
            @endif
        @empty
            <x-card.mini>
                <x-text.main>Exam history was not found.</x-text.main>
            </x-card.mini>
        @endforelse
    </x-card.main>

    <x-card.main title="Tests Created" size="grid">
        @forelse ($user->exams as $examSet)
            @if ($examSet->visibility)
                <x-card.mini>
                    <a href="{{ route('exam.view', $examSet) }}" class="text-2xl no-underline link link-primary">{{ $examSet->name }}</a>
                    <x-text.main>{{ $examSet->description }}</x-text.main>
                    <x-card.buttons primaryLabel="Take Exam" primaryAction="{{ route('exam-session.start', $examSet) }}" />
                </x-card.mini>
            @endif
        @empty
            <x-text.main>No tests created yet.</x-text.main>
        @endforelse
    </x-card.main>
@endsection
