@extends('layouts.app2')

@section('content')

    <div class="w-full text-right">
        <a href="{{ route('exam-create') }}" class="btn btn-primary"><i class="text-lg {{ config('icon.new_exam') }}"></i> Create an Exam</a>
    </div>

    <x-card.main title="Manage Your Exams" size="full">
        <x-card.mini>
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
                            <x-table.cell hideMobile='true'>@if ($exam->visibility) <span class="badge badge-{{ config('color.public') }}"><i class="{{ config('icon.public') }} mr-2"></i> Public</span> @else <span class="badge badge-{{ config('color.private') }}"><i class="{{ config('icon.private') }} mr-2"></i> Private</span> @endif </x-table.cell>
                            <x-table.cell>
                                <a href="{{ route('exam-session.start', $exam) }}" class="mx-2 btn btn-sm btn-outline btn-primary"><i class="{{ config('icon.take_exam') }} text-lg"></i> Take Exam</a>
                                <a href="{{ route('exam.edit', $exam) }}" class="mx-2 btn btn-sm btn-outline btn-secondary"><i class="{{ config('icon.edit_exam') }} text-lg"></i> Edit Exam</a>
                            </x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-table.body>
            </x-table.main>
        </x-card.mini>
    </x-card.main>
    
    <div class="w-full text-right">
        <a href="{{ route('exam-create') }}" class="btn btn-primary"><i class="text-lg {{ config('icon.new_exam') }}"></i> Create an Exam</a>
    </div>
    
@endsection
