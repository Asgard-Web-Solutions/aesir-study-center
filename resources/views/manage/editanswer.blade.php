@extends('layouts.app')

@section('content')
<x-page.header :text="$question->set->name" />

    <x-card.setup :header="$question->text">

        <form action="{{ route('update-answer', $answer->id) }}" method="post">
            @csrf

            <x-forms.text-box name="answer" label="Answer" :value="$answer->text" />

            @php
                $values[0] = "Wrong";
                $values[1] = "Correct";   
            @endphp
            <x-forms.dropdown name="correct" label="Correct Answer?" :values="$values" :selected="$answer->correct" />

            <x-forms.submit-button text="Update Answer" />
        </form>

    </x-card.setup>
    
    <x-page.actions secondary="Cancel" :secondaryLink="route('manage-answers', $answer->question_id)" />
@endsection
