@extends('layouts.app2')

@section('content')
    <x-card.main title="Exam: {!! $group->set->name !!}">

        <x-card.mini title="Question: {!! $question->text !!}">
            <form action="{{ route('group-remove-question', ['group' => $group, 'question' => $question]) }}" method="post">
                @csrf
                <input type='hidden' name='confirm' vaule='true' />

                <x-text.main>Are you sure you want to DELETE this question from your group? (This CANNOT be undone).</x-text.main>
                <x-text.dim>All users that have made progress on this question will be impacted.</x-text.dim>

                <br />
                <x-card.buttons submitLabel="Permanantly Delete This Question" secondaryLabel="Never Mind..." secondaryAction="{{ route('group-view', $group) }}" />
            </form>
        </x-card.mini>
    </x-card.main>
@endsection
