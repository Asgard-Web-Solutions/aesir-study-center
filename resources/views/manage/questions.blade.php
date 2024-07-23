@extends('layouts.app2')

@section('content')
<x-card.main title="{{ $set->name }}">

    <x-card.mini title="Exam Settings">
        <form action="{{ route('update-exam', $set) }}" method="POST">
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

    <x-card.mini title="Question Groups">
        <x-table.main>
            <x-table.head>
                <x-table.hcell>Name</x-table.hcell>
                <x-table.hcell># Questions</x-table.hcell>
                <x-table.hcell>&nbsp;</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach ($set->groups as $group)
                    <x-table.row>
                        <x-table.cell>{{ $group->name }}</x-table.cell>
                        <x-table.cell>{{ $group->questions->count() }}</x-table.cell>
                        <x-table.cell><x-card.buttons secondaryLabel="Manage Group" secondaryAction="{{ route('group-view', $group) }}" /></x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>
        
        <x-card.buttons primaryLabel="Add a Group" primaryAction="{{ route('group-create', $set) }}" />
    </x-card.mini>

    <x-card.mini title="Test Questions">
        <x-table.main>
            <x-table.head>
                <x-table.hcell>{{ __('Question') }}</x-table.hcell>
                <x-table.hcell hideMobile='true'>{{ __('# Answers') }}</x-table.hcell>
                <x-table.hcell>{{ __('Actions') }}</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach ($questions as $question)
                    <x-table.row>
                        <x-table.cell>{{ $question->text }}</x-table.cell>
                        <x-table.cell hideMobile='true'>{{ $question->answers->count() }}</x-table.cell>
                        <x-table.cell><x-card.buttons primaryAction="{{ route('manage-answers', $question->id) }}" primaryLabel="Edit"/></x-table.cell>        
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>

        <x-card.buttons primaryAction="{{ route('add-question', $set->id) }}" primaryLabel="Add Question" />
    </x-card.mini>
</x-card.main>

<div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
    <a href="{{ route('profile.myexams') }}" class="btn btn-secondary">{{ __('Manage Your Exams') }}</a>
</div>
@endsection
