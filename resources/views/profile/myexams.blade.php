@extends('layouts.app2')

@section('content')

    <x-card.main title="Your Exams">
        <x-table.main>
            <x-table.head>
                <x-table.hcell>Exam Name</x-table.hcell>
                <x-table.hcell># of Questions</x-table.hcell>
                <x-table.hcell>Actions</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach($exams as $exam)
                    <x-table.row>
                        <x-table.cell>{{ $exam->name }}</x-table.cell>
                        <x-table.cell>{{ $exam->questions->count() }}</x-table.cell>
                        <x-table.cell><x-card.buttons primaryLabel="Take Exam" primaryAction="{{ route('exam-session.start', $exam) }}" secondaryLabel="Edit Exam" secondaryAction="{{ route('manage-questions', $exam->id) }}" /></x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>
    </x-card.main>

@endsection
