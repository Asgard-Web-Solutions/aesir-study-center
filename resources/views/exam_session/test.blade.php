@extends('layouts.app2')

@section('content')

    <x-card.main title="{{ $examSet->name }}">
        <x-text.dim>Question # {{ $session->current_question + 1 }} <span class="text-xs opacity-50">of {{ $session->question_count }}</span></x-text.dim>
        <x-card.mini>
            <h3 class="text-3xl text-neutral-content">{{ $question->text }}</h3>
        </x-card.mini>
        <form action="{{ route('exam-session.answer', $examSet) }}" method="post">
            <x-text.dim>Select your Answer</x-text.dim>
            <x-card.mini>
                
                @csrf
                <input type="hidden" name="question" value="{{ $question->id }}">
                <input type="hidden" name="order" value="{{ $order }}">
        
                <div class="my-4 space-y-4">
                    @foreach ($answers as $answer)
                        <div class="flex items-center">
                            @if ($multi)
                                <input type="checkbox" id="answer-{{ $answer->id }}" class="max-w-lg text-primary-content radio radio-primary" name="answer[{{ $answer->id }}]">
                            @else
                                <input type="radio" id="answer-{{ $answer->id }}" class="max-w-lg text-primary-content radio radio-primary" name="answer" value="{{ $answer->id }}">
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

@endsection
