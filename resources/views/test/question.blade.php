@extends('layouts.app')

@section('content')

<x-page.header :text="$question->set->name" />

<x-card.setup :header="$question->text">

    <p>Question {{ $test->questions->count() + 1 }} of {{ $test->num_questions }}</p>

    <form action="{{ route('answer', $test->id) }}" method="post">
        @csrf
        <input type="hidden" name="question" value="{{ $question->id }}">
        <input type="hidden" name="order" value="{{ $order }}">

        <table class="table w-full my-4 bg-neutral">
            
            @foreach ($answers as $answer)
                <tr class="hover">
                    <td class="w-1">
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

        <x-forms.submit-button text="Submit Answer" />
    </form>

</x-card.setup>

<x-page.actions />

@endsection
