@extends('layouts.app2')

@section('content')

    <x-card.buttons primaryAction="{{ route('exam-create') }}" primaryLabel="Create an Exam" />
    
    <x-card.main title="Manage Your Exams" size="full">
        <x-table.main>
            <x-table.head>
                <x-table.hcell>Exam Name</x-table.hcell>
                <x-table.hcell hideMobile='true'># of Questions</x-table.hcell>
                <x-table.hcell hideMobile='true'>Public</x-table.hcell>
                <x-table.hcell>Actions</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach($exams as $exam)
                    <x-table.row>
                        <x-table.cell><a href="{{ route('exam.view', $exam) }}" class="link link-primary">{{ $exam->name }}</a></x-table.cell>
                        <x-table.cell hideMobile='true'>{{ $exam->questions->count() }}</x-table.cell>
                        <x-table.cell hideMobile='true'>@if ($exam->visibility) <span class="badge badge-primary">Public</span> @else <span class="badge badge-accent">Private</span> @endif </x-table.cell>
                        <x-table.cell>
                            <a href="{{ route('exam-session.start', $exam) }}" class="mx-2 text-xl link link-primary"><i class="{{ config('icon.take-exam') }}"></i> Take Exam</a>
                            <a href="{{ route('manage-questions', $exam->id) }}" class="mx-2 text-xl link link-secondary"><i class="{{ config('icon.edit-exam') }}"></i></a>
                        </x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>

    </x-card.main>
    
    <x-card.buttons primaryAction="{{ route('exam-create') }}" primaryLabel="Create an Exam" />
    
@endsection
