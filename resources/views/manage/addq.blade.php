@extends('layouts.app2')

@section('content')
    <x-page.header :text="$set->name" />

    <x-card.main title="Add Exam Question">
        <form action="{{ route('save-question', $set->id) }}" method="post">
            @csrf

            <x-form.text name="question" label="Question" />

            <x-form.text name="group" label="Question Group" helptext="Question Groups allow you to organize similar questions together. If a question is in a group and only has a single answer, then the incorrect answers on the test will be pulled from the answers of questions with the same Question Group label."/>

            <x-card.buttons submitLabel="Add Question" />
        </form>
    </x-card.main>

    <x-card.buttons secondaryLabel='Return to Question List' secondaryAction="{{ route('manage-questions', $set->id) }}" />
@endsection
