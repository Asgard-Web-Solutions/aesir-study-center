@extends('layouts.app2')

@section('content')

    <x-card.main title='Public Exams' size='grid'>
        @foreach ($sets as $set)
            <x-card.mini title='{{ $set->name }}'>
                <x-text.dim>{{ $set->description }}</x-text.dim>
                
                <div class="block w-full p-4 my-3 rounded-md md:flex bg-base-100">
                    <div class="block m-1 md:flex badge badge-accent">Questions: {{ $set->questions->count() }}</div>
                    @if ($set->user )<div class="block m-1 md:flex badge badge-secondary">Author: {{ $set->user->name }}</div>@endif
                </div>
                <div class="justify-end w-full text-right card-action">
                    <a href="{{ route('select-test', $set->id) }}" class="btn btn-primary">{{ __('TAKE TEST') }}</a>
                </div>
            </x-card.mini>
        @endforeach
    </x-card.main>

    @if ($privateSets)
        <x-card.main title='Your Private Exams' size='grid'>
            @foreach ($privateSets as $set)
                <x-card.mini title='{{ $set->name }}'>
                    <x-text.dim>{{ $set->description }}</x-text.dim>
                    <x-text.dim label='Question Pool:'>{{ $set->questions->count() }}</x-text.dim>
                    <div class="justify-end w-full text-right card-action">
                        <a href="{{ route('select-test', $set->id) }}" class="btn btn-primary">{{ __('TAKE TEST') }}</a>
                    </div>
                </x-card.mini>
            @endforeach
        </x-card.main>
    @endif
@endsection
