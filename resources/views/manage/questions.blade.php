@extends('layouts.app2')

@section('content')
<h1 class="text-2xl font-bold text-center text-primary">{{ $set->name }}</h1>

<x-card.main title="Test Questions">
    <div class="overflow-x-auto text-base-content">
        <table class="table w-full my-4 table-zebra table-compact">
            <thead>
                <tr>
                    <th class="p-2">{{ __('Question') }}</th>
                    <th class="hidden p-2 md:table-cell">{{ __('# Answers') }}</th>
                    <th class="hidden p-2 md:table-cell">{{ __('Question Group') }}</th>
                    <th class="p-2">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($set->questions as $question)
                    <tr>
                        <td class="p-2">{{ $question->text }}</td>
                        <td class="hidden p-2 md:table-cell">{{ $question->answers->count() }}</td>
                        <td class="hidden p-2 md:table-cell">{{ $question->group }}</td>
                        <td class="p-2">
                            <x-card.buttons primaryAction="{{ route('manage-answers', $question->id) }}" primaryLabel="Edit"/>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-card.main>

<div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
    <a href="{{ route('add-question', $set->id) }}" class="btn btn-primary">{{ __('Add Question') }}</a>
    <a href="{{ route('manage-exams') }}" class="btn btn-secondary">{{ __('Manage Exams') }}</a>
</div>
@endsection
