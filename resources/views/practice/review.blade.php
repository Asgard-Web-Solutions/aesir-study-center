@extends('layouts.app2', ['heading' => 'Practice - ' . $exam->name ])

@section('content')

    <x-card.main title="Review: {!! $exam->name !!}">
        <x-text.dim>Flash Card # {{ $session->question_index + 1 }} <span class="text-xs opacity-50">of {{ $session->question_count }}</span></x-text.dim>
        <x-card.mini>
            @php
                $length = 0;
                if ($question->group) {
                    $length += strlen($question->group->question);
                }

                $length += strlen($question->text);
                $textSize = ($length > 15) ? "text-lg" : "text-2xl";
            @endphp

            <h3 class="{{ $textSize }} leading-relaxed text-neutral-content"></h3>
                @if ($question->group) {!! $question->group->question !!} @endif
                <div id="markdown" class="w-full">
                    <x-markdown>
                        {!! $question->text !!}
                    </x-markdown>
                </div>
        </x-card.mini>

        <div class="collapse bg-base-200">
            <input type="checkbox" />
            <div class="w-full mx-auto text-xl font-medium lg:w-1/2 collapse-title btn btn-outline btn-secondary">Reveal Answer</div>
            <div class="collapse-content">
                <x-card.mini>
                    @foreach ($answers as $answer)
                        <h2 class="text-xl text-secondary">{!! $answer->text !!}</h2>
                    @endforeach
                </x-card.mini>
            </div>
        </div>
    </x-card.main>

    <x-card.main>
        <div class="flex w-full">
            <div class="w-1/3 text-left">
                @if ($session->question_index > 0)
                    <a href="{{ route('practice.previous', $exam) }}" class="btn btn-secondary btn-outline">Previous</a>
                @endif
            </div>
            <div class="w-1/3 text-center">
                <a href="{{ route('practice.toggleReviewFlag', ['set' => $exam, 'question' => $question]) }}" class="btn"><i class="{{ config('icon.review_flag') }} @if ($userQuestion->reviewFlagged) text-{{ config('color.review_flag_on') }} @else text-{{ config('color.review_flag_off') }} @endif text-3xl"></i></a>
            </div>
            <div class="w-1/3 text-right">
                <a href="{{ route('practice.next', $exam) }}" class="btn btn-primary btn-outline">Next</a>
            </div>
        </div>
    </x-card.main>

    <x-card.main>
        <div class="w-full text-right">
            <a href="{{ route('practice.done', $exam) }}" class="btn btn-error btn-outline">End Review Session</a>
        </div>
    </x-card.main>

@endsection
