@extends('layouts.app2', ['heading' => 'Manage Authored Exams'])

@section('content')
<x-card.main title='Manage Your Exams' size='grid'>
    @foreach ($sets as $set)
        <x-card.mini title="{{ $set->name }}">
            <x-text.dim>{{ $set->description }}</x-text.dim>
            <x-text.dim label="Questions:">{{ $set->questions->count() }}</x-text.dim>
            <x-card.buttons primaryAction="{{ route('manage-questions', $set->id) }}" primaryLabel="Edit Exam"/>
        </x-card.mini>
    @endforeach
</x-card.main>

<x-card.main title="Create An Exam">
    <x-card.mini>
        <form action="{{ route('save-exam') }}" method="post">
            @csrf

            <x-form.text label="Name" name="name" />
            <x-form.text label="Description" name="description" />

            @php
                foreach ($visibility as $status)
                {
                    $values[$status->value] = str_replace("is", "", $status->name);
                }
            @endphp
            <x-form.dropdown name="visibility" label="Public / Private" :values="$values" />

            <x-card.buttons submitLabel="Create Exam" />
        </form>
    </x-card.mini>
</x-card.main>

@if ($privateExams)
    <x-card.main title="Admin: Manage Other's Private Exams" size='grid'>
        @foreach ($privateExams as $set)
            <x-card.mini title="{{ $set->name }}">
                <x-text.dim>{{ $set->description }}</x-text.dim>
                <x-text.dim label="Questions:">{{ $set->questions->count() }}</x-text.dim>
                <x-card.buttons primaryAction="{{ route('manage-questions', $set->id) }}" primaryLabel="Edit Exam"/>
            </x-card.mini>
        @endforeach
    </x-card.main>
@endif
@endsection
