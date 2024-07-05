@extends('layouts.app2')

@section('content')
    <x-page.header :text="$question->set->name" />

    <x-card.main title="Edit Question">
        <x-card.mini>
            <form action="{{ route('update-question', $question->id) }}" method="post">
                @csrf
    
                <x-form.text name="question" label="Question" value="{{ old('question', $question->text) }}" />
    
                <x-form.text name="group" label="Question Group" value="{{ old('group', $question->group) }}" helptext="Question Groups allow you to organize similar questions together. If a question is in a group and only has a single answer, then the incorrect answers on the test will be pulled from the answers of questions with the same Question Group label."/>
    
                <x-card.buttons submitLabel="Update Question" />
            </form>    
        </x-card.mini>
    </x-card.main>

    <x-card.buttons primaryLabel="Return to Question List" primaryAction="{{ route('manage-answers', $question->id) }}" />
@endsection
