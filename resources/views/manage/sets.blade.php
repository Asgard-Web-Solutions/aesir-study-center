@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold text-center text-base-content">{{ __('Manage Exams') }}</h1>

<div class="w-1/2 m-auto my-10 shadow-xl card bg-neutral text-neutral-content">
    <div class="w-full card-body">
        <div class="items-center w-full text-center">
            <h2 class="card-title text-accent" style="display: block">{{ __('Available Exams') }}</h2>
        </div>

        <div class="overflow-x-auto text-base-content">
            <table class="table w-full my-4 table-zebra table-compact">
                <thead>
                    <tr>
                        <th>{{ __('Exam') }}</th>
                        <th>{{ __('Questions') }}</th>
                        <th>{{ __('Modify') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sets as $set)
                        <tr>
                            <td>{{ $set->name }}</td>
                            <td>{{ $set->questions->count() }}</td>
                            <td><a href="{{ route('manage-questions', $set->id) }}" class="link link-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="w-1/2 m-auto my-10 shadow-xl card bg-neutral text-neutral-content">
    <div class="w-full card-body">
        <div class="items-center w-full text-center">
            <h2 class="card-title text-accent" style="display: block">{{ __('Create New Exam') }}</h2>
        </div>

        <form action="{{ route('save-exam') }}" method="post">
            @csrf

            <div class="w-full my-4 form-control">
                <label class="label" for="exam_set_name">
                    <span class="label-text text-neutral-content">{{ __('Exam Set Name') }}</span>
                </label>
                <input id="exam_set_name" name="name" value="{{ old('name', '') }}" class="w-full max-w-xs input input-bordered input-primary" type="text">
            </div>
            @error('name')
                <div class="my-4 shadow-lg alert alert-error">
                    <div>
                        <i class="fa-regular fa-circle-xmark"></i>
                        <span>{{ $message }}</span>
                    </div>
                </div>
            @enderror

            <div class="w-full my-4 form-control">
                <label class="label" for="exam_set_description">
                    <span class="label-text text-neutral-content">{{ __('Exam Set Description') }}</span>
                </label>
                <input id="exam_set_description" value="{{ old('description', '') }}" name="description" class="w-full max-w-xs input input-bordered input-primary" type="text">
            </div>
            @error('description')
                <div class="my-4 shadow-lg alert alert-error">
                    <div>
                        <i class="fa-regular fa-circle-xmark"></i>
                        <span>{{ $message }}</span>
                    </div>
                </div>
            @enderror


            <div class="justify-end w-full text-right card-action">
                <input type="submit" class="btn btn-primary" value="{{ __('Create Exam') }}">
            </div>

        </form>
    </div>
</div>

@endsection
