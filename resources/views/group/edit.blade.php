@extends('layouts.app2')

@section('content')
    <x-card.main title="Exam: {!! $group->set->name !!}">

        <x-card.mini title="Question: {!! $question->text !!}">
            <form action="{{ route('group-update-question', ['group' => $group, 'question' => $question]) }}" method="post">
                @csrf

                <x-form.text name="question" label="Question" value="{!! old('question', $question->text) !!}" />
                <x-form.text name="answer" label="Answer" value="{!! old('answer', $question->answers[0]->text) !!}" />

                <x-card.buttons submitLabel="Update Question" />
            </form>
        </x-card.mini>
    </x-card.main>

    <x-card.buttons secondaryLabel='Back' secondaryAction="{{ route('group-view', $group) }}" />
@endsection
