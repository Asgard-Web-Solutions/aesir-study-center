@extends('layouts.app')

@section('content')
    <x-page.header :text="$question->set->name" />

    <x-card.setup header="Edit Exam Question">

        <form action="{{ route('update-question', $question->id) }}" method="post">
            @csrf

            <x-forms.text-box name="question" label="Question" value="{{ old('question', $question->text) }}" />

            <x-forms.text-box name="group" label="Question Group" value="{{ old('group', $question->group) }}" />
            <div class="m-3">
                <p class="mx-3 my-3">Question Groups allow you to organize similar questions together. If a question is in a group and only has a single answer, then the incorrect answers on the test will be pulled from the answers of questions with the same Question Group label.</p>
            </div>

            <x-forms.submit-button text="Update Question" />
        </form>

    </x-card.setup>
    <x-page.actions secondary="Back to question List" :secondaryLink="route('manage-answers', $question->id)" />
@endsection
