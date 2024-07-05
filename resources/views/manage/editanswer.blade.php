@extends('layouts.app2')

@section('content')
<x-page.header :text="$question->set->name" />

    <x-card.main title="{{ $question->text }}">
        <x-card.mini>
            <form action="{{ route('update-answer', $answer->id) }}" method="post">
                @csrf
    
                <x-form.text name="answer" label="Answer" value="{{ $answer->text }}" />
    
                @php
                    $values[0] = "Wrong";
                    $values[1] = "Correct";   
                @endphp
                <x-form.dropdown name="correct" label="Correct Answer?" :values="$values" selected="{{ $answer->correct }}" />
    
                <x-card.buttons submitLabel="Update Answer" />
            </form>    
        </x-card.mini>
    </x-card.main>

    <x-card.buttons secondaryLabel="Cancel" secondaryAction="{{ route('manage-answers', $answer->question_id) }}" />
@endsection
