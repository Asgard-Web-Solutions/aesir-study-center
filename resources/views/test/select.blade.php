@extends('layouts.app2')

@section('content')

    <x-card.main title='Available Exams' size='grid'>
        @foreach ($sets as $set)
            <x-card.mini title='{{ $set->name }}'>
                <x-text.dim>{{ $set->description }}</x-text.dim>
                <x-text.dim label='Question Pool:'>{{ $set->questions->count() }}</x-text.dim>
                <div class="justify-end w-full text-right card-action">
                    <a href="{{ route('select-test', $set->id) }}" class="btn btn-primary">{{ __('TAKE TEST') }}</a>
                </div>
            </x-card.mini>
        @endforeach
    </x-card.main>
@endsection
