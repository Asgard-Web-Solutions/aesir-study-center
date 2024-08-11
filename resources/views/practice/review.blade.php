@extends('layouts.app2')

@section('content')

    <x-card.main title="Review: {!! $exam->name !!}">
        <x-text.dim>Flash Card # {{ $session->question_index }} <span class="text-xs opacity-50">of {{ $session->question_count }}</span></x-text.dim>
        <x-card.mini>
            <h3 class="text-3xl text-primary">@if ($question->group) {!! $question->group->question !!} @endif {!! $question->text !!}</h3>
        </x-card.mini>

        <div class="collapse bg-base-200">
            <input type="checkbox" />
            <div class="w-1/2 mx-auto text-xl font-medium collapse-title btn btn-outline btn-secondary">Reveal Answer</div>
            <div class="collapse-content">
                <x-card.mini>
                    @foreach ($answers as $answer)
                        <h2 class="text-xl text-secondary">{!! $answer->text !!}</h2>
                    @endforeach
                </x-card.mini>
            </div>
        </div>
          
    </x-card.main>

    <x-card.main>
        <div class="flex w-full">
            <div class="w-1/2 text-left">
                @if ($session->question_index > 0)
                    <a href="{{ route('practice.previous', $exam) }}" class="btn btn-secondary btn-outline">Previous</a>
                @endif
            </div>
            <div class="w-1/2 text-right">
                <a href="{{ route('practice.next', $exam) }}" class="btn btn-primary btn-outline">Next</a>
            </div>
        </div>
    </x-card.main>

@endsection
