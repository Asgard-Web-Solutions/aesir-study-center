@extends('layouts.app2')

@section('content')
<h1 class="text-2xl font-bold text-center text-base-content">{{ __('Manage Exams') }}</h1>

<x-card.main title='Your Exams' size='grid'>
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

            <x-card.buttons submitLabel="Create Exam" />
        </form>
    </x-card.mini>
</x-card.main>

@endsection
