@extends('layouts.app')

@section('content')
    <x-page.header :text="$set->name" />

    <x-card.setup header="Add Exam Question">

        <form action="{{ route('save-question', $set->id) }}" method="post">
            @csrf

            <x-forms.text-box name="question" label="Question" />

            <x-forms.text-box name="group" label="Question Group" />
            <div class="m-3">
                <p class="mx-3 my-3">Question Groups allow you to organize similar questions together. If a question is in a group and only has a single answer, then the incorrect answers on the test will be pulled from the answers of questions with the same Question Group label.</p>
            </div>

            <x-forms.submit-button text="Add Question" />
        </form>

    </x-card.setup>
    <x-page.actions secondary="Back to question List" :secondaryLink="route('manage-questions', $set->id)" />
@endsection
