@extends('layouts.app2')

@section('content')
    <x-page.header :text="$question->set->name" />

    <x-card.main title="Question: {{ $question->text }}">
        <x-table.main>
            <x-table.head>
                <x-table.hcell>{{ __('Action') }}</x-table.hcell>
                <x-table.hcell>{{ __('Correct') }}</x-table.hcell>
                <x-table.hcell>{{ __('Answer') }}</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach ($question->answers as $answer)
                    <x-table.row>
                        <x-table.cell><x-card.buttons alignButtons="center" secondaryLabel="<i class='fa-solid fa-pen-to-square text-primary'> Edit" secondaryAction="{{ route('edit-answer', $answer->id) }}" /></x-table.cell>
                        <x-table.cell>@if ($answer->correct) <i class="fa-regular fa-square-check text-success"></i> Correct @else <i class="fa-regular fa-square-xmark text-error"></i> Wrong @endif</x-table.cell>
                        <x-table.cell>{{ $answer->text }}</x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>

        <br />

        <x-card.mini title="Add Answer">
            <form action="{{ route('save-answers', $question->id) }}" method="post">
                @csrf
                
                <x-form.text name="answer" label="New Answer" />

                @php
                    $values[0] = "Wrong";
                    $values[1] = "Correct";
                    $selected = ($question->answers->count() > 0) ? 0 : 1;
                @endphp

                <x-form.dropdown name="correct" label="Correct Answer?" :selected="$selected" :values="$values" />

                <x-card.buttons submitLabel="Add Answer" />
            </form>
        </x-card.mini>

        <br /><br />

        <x-card.buttons alignButtons='right' primaryAction="{{ route('edit-question', $question->id) }}" primaryLabel="Edit Question Details" />
    </x-card.main>

    <x-card.buttons primaryLabel="New Question" primaryAction="{{ route('add-question', $question->set->id) }}" secondaryLabel="Question List" secondaryAction="{{ route('manage-questions', $question->set->id) }}" />
@endsection
