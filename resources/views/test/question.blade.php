@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold text-center text-base-content">{{ $question->set->name }}</h1>

<div class="w-1/2 m-auto my-10 shadow-xl card bg-neutral text-neutral-content">
    <div class="w-full card-body">
        <div class="items-center w-full text-center">
            <h2 class="card-title text-accent" style="display: block">Question {{ $test->questions->count() + 1 }} of {{ $test->num_questions }}</h2>
        </div>

        <span class="my-5 text-lg text-secondary">{{ $question->text }}</span>
        
        <form action="{{ route('answer', $test->id) }}" method="post">
            @csrf
            <input type="hidden" name="question" value="{{ $question->id }}">
            <input type="hidden" name="order" value="{{ $order }}">

            <div class="overflow-x-auto">
                <table class="table w-full my-4 bg-neutral">
                    
                    @foreach ($answers as $answer)
                        <tr class="hover">
                            <td>
                                @if ($multi)
                                    <input type="checkbox" id="answer-{{ $answer->id }}" class="checkbox checkbox-primary" name="answer[{{ $answer->id }}]">
                                @else
                                    <input type="radio" id="answer-{{ $answer->id }}" class="radio radio-primary" name="answer" value="{{ $answer->id }}">
                                @endif
                            </td>
                            <td>
                                <label class="label" for="answer-{{ $answer->id }}"><span class="label-text">{{ $answer->text }}</span></label>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="justify-end w-full my-5 text-right card-action">
                <input type="submit" class="btn btn-primary" value="{{ __('SUBMIT ANSWER') }}">
            </div>

        </form>
    </div>
</div>
@endsection
