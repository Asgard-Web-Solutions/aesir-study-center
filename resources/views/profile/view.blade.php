@extends('layouts.app2')

@section('content')

    <x-card.main size="lg">
        <x-card.mini >
            <div class="block md:flex">
                <div>
                    <x-user.avatar size='lg'>{{ $user->gravatarUrl(256) }}</x-user.avatar>
                </div>
                <div class="ml-4 text-justify">
                    <span class="text-2xl font-bold text-primary">{{ $user->name }}</span>
                </div>
            </div>
        </x-card.mini>
    </x-card.main>

    <x-card.main title="{{ $user->name }}'s Exams Taken" size="lg">
        <x-card.mini>
            <x-table.main>
                <x-table.head>
                    <x-table.hcell>Exam Name</x-table.hcell>
                    <x-table.hcell>Times Taken</x-table.hcell>
                </x-table.head>
                @foreach ($records as $exam)
                    <x-table.row>
                        <x-table.cell><a href="{{ route('exam.view', $exam) }}" class="link link-secondary">{{ $exam->name }}</a></x-table.cell>
                        <x-table.cell>{{ $exam->pivot->times_taken }}</x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.main>
        </x-card.mini>
    </x-card.main>

    <x-card.main title="Tests Created" size="grid">
        @forelse ($user->exams as $examSet)
            @if ($examSet->visibility)
                <x-card.mini>
                    <a href="{{ route('exam.view', $examSet) }}" class="text-2xl no-underline link link-primary">{{ $examSet->name }}</a>
                    <x-text.main>{{ $examSet->description }}</x-text.main>
                    <x-card.buttons primaryLabel="Take Exam" primaryAction="{{ route('exam-session.start', $examSet) }}" />
                </x-card.mini>
            @endif
        @empty
            <x-text.main>No tests created yet.</x-text.main>
        @endforelse
    </x-card.main>
@endsection
