@extends('layouts.app2')

@section('content')

    <x-card.main title="Your Exams" size="full">
        <x-table.main>
            <x-table.head>
                <x-table.hcell>Exam Name</x-table.hcell>
                <x-table.hcell># of Questions</x-table.hcell>
                <x-table.hcell>Public</x-table.hcell>
                <x-table.hcell>Actions</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach($exams as $exam)
                    <x-table.row>
                        <x-table.cell><a href="{{ route('exam.view', $exam) }}" class="link link-primary">{{ $exam->name }}</a></x-table.cell>
                        <x-table.cell>{{ $exam->questions->count() }}</x-table.cell>
                        <x-table.cell>@if ($exam->visibility) <span class="badge badge-primary">Public</span> @else <span class="badge badge-accent">Private</span> @endif </x-table.cell>
                        <x-table.cell>
                            <a href="{{ route('exam-session.start', $exam) }}" class="mx-2 text-xl link link-primary"><i class="{{ config('icon.take-exam') }}"></i> Take Exam</a>
                            <a href="{{ route('exam-session.start', $exam) }}" class="mx-2 text-xl link link-secondary"><i class="{{ config('icon.edit-exam') }}"></i></a>
                        </x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>
    </x-card.main>

@endsection
