@extends('layouts.app2', ['heading' => 'Exam - ' . $examSet->name ])

@section('content')
    <x-card.main title="{!! $examSet->name !!}">
        <x-text.dim>Question # {{ $session->current_question + 1 }} <span class="text-xs opacity-50" id="scroll-to">of {{ $session->question_count }}</span></x-text.dim>
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
        <form action="{{ route('exam-session.answer', $examSet) }}" method="post">
            <x-text.dim>
                @if ($multi)
                    Select <span class="font-bold">two or more</span> answers
                @else
                    Select <span class="font-bold">one</span> answer
                @endif
            </x-text.dim>
            <x-card.mini>

                @csrf
                <input type="hidden" name="question" value="{{ $question->id }}">
                <input type="hidden" name="order" value="{{ $order }}">

                <div class="my-4 space-y-4">
                    @foreach ($answers as $answer)
                        <div class="flex items-center">
                            @if ($multi)
                                <input type="checkbox" id="answer-{{ $answer->id }}" class="max-w-lg mx-2 text-primary-content checkbox checkbox-primary" name="answer[{{ $answer->id }}]">
                            @else
                                <input type="radio" id="answer-{{ $answer->id }}" class="max-w-lg mx-2 text-primary-content radio radio-primary" name="answer" value="{{ $answer->id }}">
                            @endif
                            <label class="label" for="answer-{{ $answer->id }}">
                                <span class="text-primary">{{ $answer->text }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

            </x-card.mini>
            <x-card.buttons submitLabel="Submit Answer" />
        </form>

    </x-card.main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Scroll to the target section
            document.getElementById('scroll-to').scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>

@endsection
