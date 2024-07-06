@extends('layouts.app2')

@section('content')
<x-card.main title="{{ $set->name }}">

    <x-card.mini title="Test Settings">
        <form action="{{ route('update-exam', $set->id) }}" method="POST">
            @csrf
            <x-form.text name="name" label="Name" value="{{ $set->name }}" />
            <x-form.text name="description" label="Description" value="{{ $set->description }}" />
            @php
                foreach ($visibilityOptions as $visibility)
                {
                    $visibilityValues[$visibility->value] = str_replace("is", "", $visibility->name);
                }
            @endphp
            <x-form.dropdown name="visibility" label="Public / Private" :values="$visibilityValues" selected="{{ $set->visibility }}" />

            <x-card.buttons submitLabel="Update Exam Settings" />
        </form>
    </x-card.mini>

    <x-card.mini title="Test Questions">
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
    </x-card.mini>
</x-card.main>

<div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
    <a href="{{ route('add-question', $set->id) }}" class="btn btn-primary">{{ __('Add Question') }}</a>
    <a href="{{ route('manage-exams') }}" class="btn btn-secondary">{{ __('Manage Exams') }}</a>
</div>
@endsection
