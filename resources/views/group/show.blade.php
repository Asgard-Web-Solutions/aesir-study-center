@extends('layouts.app2')

@section('content')
<x-card.main title="Exam: {{ $group->set->name }} // {{ $group->name }}">

    <x-card.mini title="Test Settings">
        <form action="" method="POST">
            @csrf
            <x-form.text name="name" label="Name" value="{{ $group->name }}" />

            <x-card.buttons submitLabel="Update Group Settings" />
        </form>
    </x-card.mini>

    <x-card.mini title="Group Questions">
        <x-table.main>
            <x-table.head>
                <x-table.hcell>Question</x-table.hcell>
                <x-table.hcell>Answer</x-table.hcell>
                <x-table.hcell>&nbsp;</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach ($questions as $question)
                    <x-table.row>
                        <x-table.cell>{{ $question->text }}</x-table.cell>
                        <x-table.cell>{{ $question->answers[0]->text }}</x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>
    </x-card.mini>
</x-card.main>

<x-card.main size="full" title="Add Questions">
    <form action="{{ route('group-store-questions', $group) }}" method="POST">
        @csrf
        @for ($i = 1; $i <= 8; $i++)
            <x-card.mini title="Question #{{ $i }}">                
                <x-form.group>
                    <x-form.text name="questions[{{ $i }}][question]" label="Question" size='half' />
                    <x-form.text name="questions[{{ $i }}][answer]" label="Answer" size='half'/>
                </x-form.group>
            </x-card.mini>
        @endfor
        
        <x-card.buttons submitLabel="Save Questions" />
    </form>
</x-card.main>

<div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
    <a href="{{ route('manage-questions', $group->set->id) }}" class="btn btn-secondary">{{ __('Back to Exam Manager') }}</a>
</div>
@endsection
