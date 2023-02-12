@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold text-center text-base-content">{{ $set->name }}</h1>

<div class="w-1/2 m-auto my-10 shadow-xl card bg-neutral text-neutral-content">
    <div class="w-full card-body">
        <div class="items-center w-full text-center">
            <h2 class="card-title text-accent" style="display: block">{{ __('Configure Test to Begin') }}</h2>
        </div>

        <form action="{{ route('start-test', $set->id) }}" method="post">
            @csrf
            <div class="w-full my-4 form-control">
                <label class="label" for="num_questions">
                    <span class="label-text text-neutral-content">{{ __('How Many Questions?') }}</span> <span class="text-secondary">(Question Pool: {{  $set->questions->count() }})</span>
                </label>
                <input id="num_questions" class="w-full max-w-xs input input-bordered input-primary text-primary-content" type="text" name="number_questions" value="@if ($set->questions->count() < 10){{ $set->questions->count()}}@else 10 @endif">
            </div>
            @error('number_questions')
                <div class="my-4 shadow-lg alert alert-error">
                    <div>
                        <i class="fa-regular fa-circle-xmark"></i>
                        <span>{{ $message }}</span>
                    </div>
                </div>
            @enderror

            <div class="justify-end w-full text-right card-action">
                <input type="submit" class="btn btn-primary" value="{{ __('BEGIN TEST') }}">
            </div>

        </form>
    </div>
</div>

@endsection
