@extends('layouts.app2')

@section('content')

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
        @feature('mage-upgradae')
            @if (auth()->user()->credit->architect >= 1)
                <a href="{{ route('exam.create') }}" class="btn btn-primary"><i class="text-lg {{ config('icon.new_exam') }}"></i> Create an Exam</a>
            @else
                <a href="{{ route('exam.create') }}" class="btn btn-primary btn-disabled" @disabled(true)><i class="text-lg {{ config('icon.new_exam') }}"></i> Not enough Architect Credits to create an Exam</a>
                <x-help.box>
                    <x-help.text>Oh no! What happened to the <x-help.highlight color="warning">Create an Exam</x-help.highlight> button?</x-help.text>
                    <x-help.text>Acolytes who are <x-help.text color="accent">Adepts</x-help.text>, that is, are using a <x-help.highlight color="accent">Free Account</x-help.highlight>, can only create a certain number of exams. Once your <x-help.text color="secondary">Architect Credits</x-help.text> are used up, you will have to obtain more credits, or <x-help.text color="accent">Upgrade to Mage</x-help.text> level to create more exams.</x-help.text>
                    <x-help.text>To obtain more credits you can <x-help.text color="secondary">Master an Exam</x-help.text>, either yours or another public exam. You will also get an <x-help.text color="secondary">Architect Credit</x-help.text> if other people master your exams, so make sure to make it a good one!</x-help.text>
                    <x-help.text>Upgrading to Mage helps to support Acolyte Academy, allowing it to continue running and receiving updates, so if you get benefit from this site, please consider doing so.</x-help.text>
                </x-help.box>
            @endif
        @else
            <a href="{{ route('exam.create') }}" class="btn btn-primary"><i class="text-lg {{ config('icon.new_exam') }}"></i> Create an Exam</a>
        @endfeature
    </div>
    
@endsection
