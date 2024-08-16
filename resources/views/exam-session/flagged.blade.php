@extends('layouts.app2')

@section('content')

<h1 class="text-2xl font-bold text-center text-primary">{{ $exam->name }}</h1>

    <x-card.main title='Review Book Updated'>
        <x-card.mini>
            <div class="block lg:flex">
                <div class="w-full my-2 text-center lg:w-1/6">
                    <a href="{{ route('exam-session.toggleReviewFlag', ['set' => $exam, 'question' => $question]) }}" class="btn"><i class="{{ config('icon.review_flag') }} @if ($userQuestion->reviewFlagged) text-{{ config('color.review_flag_on') }} @else text-{{ config('color.review_flag_off') }} @endif text-3xl"></i></a>
                </div>
                <div>
                    @if ($userQuestion->reviewFlagged)
                        This question was Added to your Review Book.
                    @else
                        This question was Removed from your Review Book.
                    @endif
                </div>
            </div>
        </x-card.mini>

        <x-card.mini>
            <div class="block w-full text-right lg:flex">
                <a href="{{ route('practice.start', $exam) }}" class="m-2 btn btn-secondary btn-outline">Start Practice Session</a>
                <a href="{{ route('exam-session.test', $exam) }}" class="m-2 btn btn-primary">Continue Exam</a>
            </div>
        </x-card.mini>
    </x-card.main>
@endsection
