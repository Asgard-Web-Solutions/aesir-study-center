@extends('layouts.app2')

@section('content')
<x-page.header :text="$question->set->name" />

    <x-card.main :title="$question->text">

        <x-text.dim>Question {{ $test->questions->count() }} of {{ $test->num_questions }}</x-text.dim>
    
        <p class="my-4 text-lg text-center @if ($correct) text-success @else text-error @endif">
            @if ($correct) CORRECT @else INCORRECT @endif
        </p>
    
        <div class="p-4 my-4 space-y-4 rounded-lg bg-neutral">
            <div class="flex items-center p-2 rounded-lg hover:bg-base-200">
                <div class="flex items-center w-1/2 sm:w-1/4">
                    <span class="text-base font-medium">{{ __('Your Answer') }}</span>
                </div>
            </div>
    
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
        </div>
    
    </x-card.main>
            
    <x-page.actions primary="Next Question" :primaryLink="route('take-test', $test->id)" />

@endsection
