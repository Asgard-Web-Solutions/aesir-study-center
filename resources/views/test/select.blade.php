@extends('layouts.app')

@section('content')
        @foreach ($sets as $set)
            <div class="w-1/2 m-auto my-7 card bg-neutral text-neutral-content shadow-x1">
                <div class="w-full card-body">
                    <div class="items-center w-full text-center">
                        <h2 class="card-title text-accent" style="display: block">{{ $set->name }}</h2>
                    </div>
                    <p>{{ $set->description }}</p>
                    <p class="text-info"><strong>Question Pool:</strong> {{ $set->questions->count() }}</p>
                    <div class="justify-end w-full text-right card-action">
                        <a href="{{ route('select-test', $set->id) }}" class="btn btn-primary">{{ __('TAKE TEST') }}</a>
                    </div>
                </div>
            </div>
        @endforeach
        
@endsection
