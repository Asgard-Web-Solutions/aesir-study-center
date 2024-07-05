@extends('layouts.app2')

@section('content')

<x-page.header :text="$question->set->name" />

    <x-card.main :title="$question->text">
        <p>Question {{ $test->questions->count() + 1 }} of {{ $test->num_questions }}</p>
        
        <form action="{{ route('answer', $test->id) }}" method="post">
            @csrf
            <input type="hidden" name="question" value="{{ $question->id }}">
            <input type="hidden" name="order" value="{{ $order }}">
    
            <div class="my-4 space-y-4">
                @foreach ($answers as $answer)
                    <div class="flex items-center">
                        @if ($multi)
                            <input type="checkbox" id="answer-{{ $answer->id }}" class="mr-2 checkbox checkbox-primary" name="answer[{{ $answer->id }}]">
                        @else
                            <input type="radio" id="answer-{{ $answer->id }}" class="mr-2 radio radio-primary" name="answer" value="{{ $answer->id }}">
                        @endif
                        <label class="label" for="answer-{{ $answer->id }}">
                            <span class="text-primary">{{ $answer->text }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        
            <x-card.buttons submitLabel="Submit Answer" />
        </form>
    </x-card.main>

@endsection
