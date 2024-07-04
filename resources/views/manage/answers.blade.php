@extends('layouts.app')

@section('content')
    <x-page.header :text="$question->set->name" />

    <x-card.main title="Question: {{ $question->text }}">
        
    </x-card.main>

    <x-card.setup :header="$question->text">

        <div class="w-full p-2 m-4 mx-auto rounded-md bg-base-100 text-base-content">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="w-1">Action</th>
                        <th class="w-1">Correct</th>
                        <th>Answer Text</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($question->answers as $answer)
                        <tr>
                            <td>
                                <a href="{{ route('edit-answer', $answer->id) }}"><i class="fa-solid fa-pen-to-square text-primary"></i></a>
                            </td>
                            <td>
                                @if ($answer->correct) <i class="fa-regular fa-square-check text-success"></i> @else <i class="fa-regular fa-square-xmark text-error"></i> @endif
                            </td>
                            <td>
                                {{ $answer->text }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <x-page.actions primary="Edit Question" :primaryLink="route('edit-question', $question->id)"/>
    </x-card.setup>

    <x-card.setup header="Add Answer">

        <form action="{{ route('save-answers', $question->id) }}" method="post">
            @csrf

            <x-forms.text-box name="answer" label="New Answer" />

            @php
                $values[0] = "Wrong";
                $values[1] = "Correct";
                $selected = ($question->answers->count() > 0) ? 0 : 1;
            @endphp

            <x-forms.dropdown name="correct" label="Correct Answer?" :selected="$selected" :values="$values" />

            <x-forms.submit-button text="Add Answer" />
        </form>

    </x-card.setup>
    
    <x-page.actions primary="Add Another Question" :primaryLink="route('add-question', $question->set->id)" secondary="Back to Questions" :secondaryLink="route('manage-questions', $question->set->id)" />

@endsection
