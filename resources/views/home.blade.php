@extends('layouts.app2')

@section('content')

    <x-card.main title='Tests' size='grid'>
        @foreach($tests as $test)
            <x-card.mini :title="$test['name']">
                <x-text.main label='Recent Average:'><span class="font-bold text-gray-50">{{ $test['average'] }}%</span></x-text.main>
                <x-text.dim label='Mastery Level:'>{{ $test['familiar'] }}% / {{ $test['proficient'] }}% / {{ $test['mastery'] }}%</x-text.dim>
                <x-card.buttons primaryLabel='Retake Test' primaryAction="{{ route('select-test', $test['id']) }}"></x-card.buttons>
            </x-card.mini>
        @endforeach
    </x-card.main>

@endsection
