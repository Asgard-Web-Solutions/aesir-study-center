@extends('layouts.app2')

@section('content')
    <x-card.main title='Public Exams' size='grid'>
        @foreach ($exams as $exam)
            <x-card.mini>
                <h2 class="my-2 text-xl"><a href="{{ route('exam.view', $exam) }}" class="font-bold no-underline link link-primary">{{ $exam->name }}</a></h2>

                <div class="w-full p-2 mt-2 mb-4 rounded-lg bg-base-100">
                    <x-text.dim>{{ $exam->description }}</x-text.dim>
                </div>

                <div class="flex w-full py-2 my-2 rounded-lg bg-base-100">
                    @if ($exam->user) <a href="{{ route('profile.view', $exam->user) }}"><x-user.avatar size="tiny">{{ $exam->user->gravatarUrl(64) }}</x-user.avatar></a> <a href="{{ route('profile.view', $exam->user) }}" class="link link-secondary">{{ $exam->user->name }}</a> @endif
                    <span class="mx-4 tooltip text-accent" data-tip="Question Count"><i class="mr-1 text-lg fa-regular fa-block-question"></i> {{ $exam->questions->count() }}</span>
                </div>

                
                <div class="block w-full px-2 py-4 bg-base-100 md:flex">
                    <x-card.buttons primaryLabel="Take Exam" primaryAction="{{ route('exam-session.start', $exam) }}" />
                </div>

            </x-card.mini>
        @endforeach
    </x-card.main>

@endsection