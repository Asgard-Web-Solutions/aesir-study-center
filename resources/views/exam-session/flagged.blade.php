@extends('layouts.app2')

@section('content')

<h1 class="text-2xl font-bold text-center text-primary">{{ $exam->name }}</h1>

    <x-card.main title='Review Book Updated'>
        <x-card.mini>
            This question was added to your review book.
        </x-card.mini>

        <x-card.mini>
            <div class="block w-full text-right lg:flex">
                <a href="{{ route('practice.start', $exam) }}" class="m-2 btn btn-secondary btn-outline">Start Practice Session</a>
                <a href="{{ route('exam-session.test', $exam) }}" class="m-2 btn btn-primary">Next Question</a>
            </div>
        </x-card.mini>
    </x-card.main>
@endsection
