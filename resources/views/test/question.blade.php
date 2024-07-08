@extends('layouts.app2')

@section('content')
    <x-page.header :text="$question->set->name" />

    <x-card.main title="">
        <div class="w-3/4 mx-auto text-center chat chat-start">
            <div class="chat-header">
                Question # {{ $test->questions->count() + 1 }} 
                <time class="text-xs opacity-50">of {{ $test->num_questions }}</time>
              </div>            
            <div class="text-2xl chat-bubble chat-bubble-ghost">{{ $question->text }}</div>
        </div>
        <form action="{{ route('answer', $test->id) }}" method="post">
            <x-card.mini title="Select Answer">
                
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
