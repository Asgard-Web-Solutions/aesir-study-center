@extends('layouts.app2')

@section('content')

    <x-card.main title="Review: {{ $exam->name }}">
        <x-card.mini>
            <h3 class="text-3xl text-primary">{{ $question->text }}</h3>
        </x-card.mini>

        <div class="collapse bg-base-200">
            <input type="checkbox" />
            <div class="text-xl font-medium collapse-title">Reveal Answer</div>
            <div class="collapse-content">
                <x-card.mini>
                    @foreach ($answers as $answer)
                        <h2 class="text-xl text-secondary">{{ $answer->text }}</h2>
                    @endforeach
                </x-card.mini>
            </div>
        </div>
          
    </x-card.main>

    <x-card.main>
        <div class="flex w-full">
            <div class="w-1/2">
            </div>
            <div class="w-1/2 text-right">
                <a href="{{ route('practice.next', $exam) }}" class="btn btn-primary btn-outline">Next</a>
            </div>
        </div>
    </x-card.main>

@endsection
