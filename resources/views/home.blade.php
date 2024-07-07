@extends('layouts.app2')

@section('content')

    <x-card.main title='Your Completed Exams' size='grid'>
        @foreach($tests as $test)
            <x-card.mini :title="$test['name']">
                <x-text.main label='Recent Average:'><span class="font-bold text-gray-50">{{ $test['average'] }}%</span></x-text.main>
                <div>
                    <div class="tooltip" data-tip="Mastery: {{ $test['mastery'] }}%"><progress class="w-56 progress progress-accent " value="{{ $test['mastery'] }}" max="100"></progress></div>
                    <div class="tooltip" data-tip="Proficient: {{ $test['proficient'] }}%"><progress class="w-56 progress progress-secondary " value="{{ $test['proficient'] }}" max="100"></progress></div>
                    <div class="tooltip" data-tip="Familiar: {{ $test['familiar'] }}%"><progress class="w-56 progress progress-success " value="{{ $test['familiar'] }}" max="100"></progress></div>
                    <div class="tooltip" data-tip="Apprentice: {{ $test['apprentice'] }}%"><progress class="w-56 progress progress-info" value="{{ $test['apprentice'] }}" max="100"></progress></div>
                </div>
                <x-card.buttons primaryLabel='Retake Test' primaryAction="{{ route('select-test', $test['id']) }}"></x-card.buttons>
            </x-card.mini>
        @endforeach
    </x-card.main>

    @if ($incomplete->count())
        <x-card.main title="Incomplete Exams" size="grid">
            @foreach($incomplete as $test)
                <x-card.mini title="{{ $test->set->name }}">
                    <x-text.main>{{ $test->set->description }}</x-text.main>
                    <x-card.buttons primaryAction="{{ route('take-test', $test->id) }}" primaryLabel="Continue Test" />
                </x-card.mini>
            @endforeach
        </x-card.main>
    @endif

    <x-card.buttons primaryAction="{{ route('tests') }}" primaryLabel="View Public Exams" />

@endsection
