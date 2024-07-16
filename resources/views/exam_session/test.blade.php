@extends('layouts.app2')

@section('content')
    {{-- <x-page.header :text="$question->set->name" />

    <x-card.main title="">
        <x-text.dim>Question # {{ $test->questions->count() + 1 }} <span class="text-xs opacity-50">of {{ $test->num_questions }}</span></x-text.dim>
        <x-card.mini>
            <h3 class="text-3xl text-neutral-content">{{ $question->text }}</h3>
        </x-card.mini>
        <form action="{{ route('answer', $test->id) }}" method="post">
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

    </x-card.main> --}}

@endsection
