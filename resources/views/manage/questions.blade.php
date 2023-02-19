@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold text-center text-base-content">{{ $set->name }}</h1>

<div class="m-auto my-10 shadow-xl sm:w-full md:w-10/12 card bg-neutral text-neutral-content">
    <div class="w-full card-body">
        <div class="items-center w-full text-center">
            <h2 class="card-title text-accent" style="display: block">{{ __('Test Questions') }}</h2>
        </div>

        <div class="overflow-x-auto text-base-content">
            <table class="table w-full my-4 table-zebra table-compact">
                <thead>
                    <tr>
                        <th>{{ __('Question') }}</th>
                        <th>{{ __('# Answers') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($set->questions as $question)
                        <tr>
                            <td>{{ $question->text }}</td>
                            <td>{{ $question->answers->count() }}</td>
                            <td><a href="{{ route('manage-answers', $question->id) }}"><i class="fa-solid fa-pen-to-square text-primary" alt="Edit"></i> Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
    <a href="{{ route('add-question', $set->id) }}" class="btn btn-primary">{{ __('Add Question') }}</a>
    <a href="{{ route('manage-exams') }}" class="btn btn-secondary">{{ __('Manage Exams') }}</a>
</div>
@endsection
