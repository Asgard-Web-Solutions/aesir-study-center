@extends('layouts.app2')

@section('content')
    <x-page.header :text="$set->name" />

    <x-card.main title="Add Exam Question">
        <form action="{{ route('save-question', $set->id) }}" method="post">
            @csrf

            <x-form.text name="text" label="Question" />

            <x-card.buttons submitLabel="Add Question" />
        </form>
    </x-card.main>

    <x-card.buttons secondaryLabel='Return to Question List' secondaryAction="{{ route('manage-questions', $set->id) }}" />
@endsection
