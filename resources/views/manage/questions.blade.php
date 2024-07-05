@extends('layouts.app2')

@section('content')
<h1 class="text-2xl font-bold text-center text-primary">{{ $set->name }}</h1>

<x-card.main title="Test Questions">
    <x-table.main>
        <x-table.head>
            <x-table.hcell>{{ __('Question') }}</x-table.hcell>
            <x-table.hcell hideMobile='true'>{{ __('# Answers') }}</x-table.hcell>
            <x-table.hcell hideMobile='true'>{{ __('Question Group') }}</x-table.hcell>
            <x-table.hcell>{{ __('Actions') }}</x-table.hcell>
        </x-table.head>
        <x-table.body>
            @foreach ($set->questions as $question)
                <x-table.row>
                    <x-table.cell>{{ $question->text }}</x-table.cell>
                    <x-table.cell hideMobile='true'>{{ $question->answers->count() }}</x-table.cell>
                    <x-table.cell hideMobile='true'>{{ $question->group }}</x-table.cell>
                    <x-table.cell><x-card.buttons primaryAction="{{ route('manage-answers', $question->id) }}" primaryLabel="Edit"/></x-table.cell>        
                </x-table.row>
            @endforeach
        </x-table.body>
    </x-table.main>
</x-card.main>

<div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
    <a href="{{ route('add-question', $set->id) }}" class="btn btn-primary">{{ __('Add Question') }}</a>
    <a href="{{ route('manage-exams') }}" class="btn btn-secondary">{{ __('Manage Exams') }}</a>
</div>
@endsection
