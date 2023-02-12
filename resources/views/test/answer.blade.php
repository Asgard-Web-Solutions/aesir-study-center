@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold text-center text-base-content">{{ $question->set->name }}</h1>

<div class="w-1/2 m-auto my-10 shadow-xl card bg-neutral text-neutral-content">
    <div class="w-full card-body">
        <div class="items-center w-full text-center">
            <h2 class="card-title text-accent" style="display: block">Question {{ $test->questions->count() }} of {{ $test->num_questions }}</h2>
        </div>

        <span class="my-5 text-lg text-secondary">{{ $question->text }}</span>
    
        <div class="overflow-x-auto">
            <table class="table w-full my-4 bg-neutral">
                <tr>
                    <th>{{ __('Your Answer') }}</th>
                    <th>{{ __('Answers') }}</th>
                </tr>
                
                @foreach ($answers as $answer)
                    <tr class="hover">
                        <td>
                            @if ($normalizedAnswer[$answer['id']])
                                <input type="checkbox" checked="checked" disabled class="checkbox checkbox-primary">
                            @else
                                <input type="checkbox" disabled class="checkbox checkbox-primary">
                            @endif
                        <td>
                            @if ($answer['correct'])
                                <i class="text-green-700 far fa-check-circle"></i> <span class="font-bold text-green-600">{{ $answer['text'] }}</span>
                            @else 
                                <i class="text-red-700 far fa-times-circle"></i> <span class="text-gray-500 line-through">{{ $answer['text'] }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="justify-end w-full my-5 text-right card-action">
            <a href="{{ route('take-test', $test->id) }}" class="btn btn-primary">{{ __('NEXT QUESTION') }}</a>
        </div>

    </div>
</div>
@endsection
