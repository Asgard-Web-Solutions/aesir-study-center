@extends('layouts.app')

@section('content')
    <x-page.header :text="$set->name" />

    <x-card.setup header="Add Exam Question">

        <form action="{{ route('save-question', $set->id) }}" method="post">
            @csrf

            <x-forms.text-box name="question" label="Question" />

            <x-forms.submit-button text="Add Question" />
        </form>

    </x-card.setup>
    <x-page.actions secondary="Back to question List" :secondaryLink="route('manage-questions', $set->id)" />
@endsection
