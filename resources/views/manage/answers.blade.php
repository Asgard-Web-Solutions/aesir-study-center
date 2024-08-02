@extends('layouts.app2')

@section('content')
    <x-page.header :text="$question->set->name" />

    <x-card.main title="Question: {!! $question->text !!}">
        
        <x-card.mini title="Answers">
            <x-table.main>
                <x-table.head>
                    <x-table.hcell>{{ __('Answer') }}</x-table.hcell>
                    <x-table.hcell>{{ __('Correct') }}</x-table.hcell>
                    <x-table.hcell>{{ __('Action') }}</x-table.hcell>
                </x-table.head>
                <x-table.body>
                    @forelse ($question->answers as $answer)
                        <x-table.row>
                            <x-table.cell>{{ $answer->text }}</x-table.cell>
                            <x-table.cell>@if ($answer->correct) <i class="fa-regular fa-square-check text-success"></i> Correct @else <i class="fa-regular fa-square-xmark text-error"></i> Wrong @endif</x-table.cell>
                            <x-table.cell>
                                <a href="{{ route('edit-answer', $answer->id) }}" class="link link-secondary"><i class='fa-solid fa-pen-to-square'></i> Edit</a>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <td colspan="3" class="text-center">No answers added yet...</td>
                        </x-table.row>
                @endforelse
                </x-table.body>
            </x-table.main>
        </x-card.mini>

        <br />

        <x-card.mini title="Add Answer">
            <form action="{{ route('save-answers', $question->id) }}" method="post">
                @csrf
                
                <x-form.text name="text" label="New Answer" />

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
