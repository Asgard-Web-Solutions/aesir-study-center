@extends('layouts.app2')

@section('content')

    <x-card.main title='Your Tests' size='grid'>
        @foreach($tests as $test)
            <x-card.mini :title="$test['name']">
                <x-text.main label='Recent Average:'><span class="font-bold text-gray-50">{{ $test['average'] }}%</span></x-text.main>
                <x-text.dim label='Mastery Level:'>{{ $test['familiar'] }}% / {{ $test['proficient'] }}% / {{ $test['mastery'] }}%</x-text.dim>
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

    <x-card.buttons primaryAction="{{ route('tests') }}" primaryLabel="View Public Tests" />

@endsection
