@extends('layouts.app')

@section('content')
<x-page.header :text="$question->set->name" />

    <x-card.setup :header="$question->text">
    
        <p>Question {{ $test->questions->count() }} of {{ $test->num_questions }}</p>

        <p class="my-4 text-lg text-center text-secondary">@if ($correct) CORRECT @else INCORRECT @endif</p>
    
        <table class="table w-full my-4 bg-neutral">
            <tr>
                <th>{{ __('Your Answer') }}</th>
                <th>{{ __('Answers') }}</th>
            </tr>
            
            @foreach ($answers as $answer)
                <tr class="hover">
                    <td class="w-1">
                        @if ($normalizedAnswer[$answer['id']])
                            <input type="checkbox" checked="checked" disabled class="checkbox checkbox-primary">
                        @else
                            <input type="checkbox" disabled class="checkbox checkbox-primary">
                        @endif
                    <td>
                        @if ($answer['correct'])
                            <i class="fa-regular fa-square-check text-success"></i> <span class="font-bold text-success">{{ $answer['text'] }}</span>
                        @else 
                            <i class="fa-regular fa-square-xmark text-error"></i> <span class="text-gray-500 line-through">{{ $answer['text'] }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    
    </x-card.setup>
        
    <x-page.actions primary="Next Question" :primaryLink="route('take-test', $test->id)" />

@endsection
