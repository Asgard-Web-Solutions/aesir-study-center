@extends('layouts.app2')

@section('content')
    <x-card.main title='Public Exams' size='grid'>
        @foreach ($exams as $exam)
            <x-card.mini title='{{ $exam->name }}'>
                <x-text.dim>{{ $exam->description }}</x-text.dim>
                
                <div class="block w-full p-4 my-3 rounded-md md:flex bg-base-100">
                    <div class="block m-1 md:flex badge badge-accent">Questions: {{ $exam->questions->count() }}</div>
                    @if ($exam->user )<div class="block m-1 md:flex badge badge-secondary">Author: {{ $exam->user->name }}</div>@endif
                </div>
                <div class="justify-end w-full text-right card-action">
                    <a href="{{ route('exam-session.start', $exam) }}" class="btn btn-primary">{{ __('TAKE TEST') }}</a>
                </div>
            </x-card.mini>
        @endforeach
    </x-card.main>

@endsection