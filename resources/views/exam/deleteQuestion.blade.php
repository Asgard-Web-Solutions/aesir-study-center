@extends('layouts.app2', ['heading' => 'EDIT - ' . $exam->name . ' | Delete Question'])

@section('content')
    <x-card.main title="Exam: {!! $exam->name !!}">

        <x-card.mini title="Question: {!! $question->text !!}">
            <form action="{{ route('exam.questionRemove', ['exam' => $exam, 'question' => $question]) }}" method="post">
                @csrf
                <input type='hidden' name='confirm' vaule='true' />

                <x-text.main>Are you sure you want to DELETE this question from your exam? (This CANNOT be undone).</x-text.main>
                <x-text.dim>All users that have made progress on this question will be impacted.</x-text.dim>

                <br />
                <x-card.buttons submitLabel="Permanantly Delete This Question" secondaryLabel="Never Mind..." secondaryAction="{{ route('exam.edit', $exam) }}" />
            </form>
        </x-card.mini>
    </x-card.main>
@endsection
