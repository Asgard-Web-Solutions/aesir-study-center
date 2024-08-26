@extends('layouts.app2', ['heading' => 'Exam - ' . $question->set->name ])

@section('content')
<x-page.header :text="$question->set->name" />

    <x-card.main>
        <x-text.dim>Question # {{ $test->questions->count() }} <span class="text-xs opacity-50">of {{ $test->num_questions }}</span></x-text.dim>
        <x-card.mini>
            <h3 class="text-3xl text-neutral-content">{{ $question->text }}</h3>
        </x-card.mini>
    
        <p class="my-4 text-lg text-center @if ($correct) text-success @else text-error @endif" id="scroll-to">
            @if ($correct) Correct @else Incorrect @endif
        </p>
    
        <x-card.mini title="Your Answer">
            @foreach ($answers as $answer)
                <div class="flex items-center p-2 rounded-lg hover:bg-base-200">
                    <div class="flex items-center w-1/4">
                        @if ($normalizedAnswer[$answer['id']])
                            <input type="checkbox" checked="checked" disabled class="mr-2 checkbox checkbox-primary">
                        @else
                            <input type="checkbox" disabled class="mr-2 checkbox checkbox-primary">
                        @endif
                    </div>
                    <div class="flex items-center w-3/4">
                        @if ($answer['correct'])
                            <i class="mr-2 fa-regular fa-square-check text-success"></i>
                            <span class="font-bold text-success">{{ $answer['text'] }}</span>
                        @else
                            <i class="mr-2 fa-regular fa-square-xmark text-error"></i>
                            <span class="text-gray-500 line-through">{{ $answer['text'] }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </x-card.mini>
        <x-page.actions primary="Next Question" :primaryLink="route('take-test', $test->id)" />
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
