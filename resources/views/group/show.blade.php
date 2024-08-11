@extends('layouts.app2')

@section('content')
<x-card.main title="Exam: {!! $group->set->name !!} // {!! $group->name !!}">

    <x-card.mini title="Test Settings">
        <form action="{{ route('group-update', $group) }}" method="POST">
            @csrf
            <x-form.text name="name" label="Name" value="{!! $group->name !!}" />
            <x-form.text name="question" label="Group Question Prefix" value="{!! $group->question !!}" helpText="Optionally show this question text just before every question in this group." />

            <x-card.buttons submitLabel="Update Group Settings" />
        </form>
    </x-card.mini>
</x-card.main>

<x-card.main>
    <div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
        <a href="{{ route('exam.edit', $group->set) }}" class="btn btn-secondary">{{ __('Back to Exam Manager') }}</a>
    </div>
</x-card.main>

<x-card.main size='xl'>

    <x-card.mini title="Group Questions">
        <x-table.main>
            <x-table.head>
                <x-table.hcell>Question</x-table.hcell>
                <x-table.hcell>Answer</x-table.hcell>
                <x-table.hcell>Actions</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach ($questions as $question)
                    <x-table.row>
                        <x-table.cell>{{ $question->text }}</x-table.cell>
                        <x-table.cell>{{ $question->answers[0]->text }}</x-table.cell>
                        <x-table.cell>
                            <a href="{{ route('group-edit-question', ['group' => $group, 'question' => $question]) }}" class="mx-2 text-xl text-secondary hover:underline hover:text-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="{{ route('group-delete-question', ['group' => $group, 'question' => $question]) }}" class="mx-2 text-xl text-secondary hover:underline hover:text-primary"><i class="fa-solid fa-trash-can"></i></a>
                        </x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>
    </x-card.mini>
</x-card.main>

<x-card.main size="full" title="Add Questions">

    <x-card.mini>
        <div class="block w-full lg:flex">
            @feature('mage-upgrade')
                @if (!auth()->user()->isMage)
                    <div><span class="mr-4 tooltip" data-tip="Question Credits Remaining"><i class="{{ config('icon.credit') }} text-{{ config('color.credit') }} text-lg"></i> <i class="{{ config('icon.question_credit') }} text-{{ config('color.question_credit') }} text-lg"></i> {{ $question->set->user->credit->question }}</span></div>
                @endif
            @endfeature

            <span class="mx-4 tooltip" data-tip="Question Count"><i class="{{ config('icon.question_count') }} text-{{ config('color.question_count') }} text-lg"></i> {{ $group->set->questions->count() }} / {{ config('test.max_exam_questions') }}</span>
        </div>
    </x-card.mini>
    
    @if ($group->question)
        <x-card.mini>
            Group Question Prefix: <span class="text-xl text-primary">{{ $group->question }}</span>
        </x-card.mini>
        <div class="py-2 my-5 divider"></div>
    @endif

    <form action="{{ route('group-store-questions', $group) }}" method="POST">
        @csrf
        @for ($i = 1; $i <= 10; $i++)
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
    <a href="{{ route('exam.edit', $group->set) }}" class="btn btn-secondary">{{ __('Back to Exam Manager') }}</a>
</div>
@endsection
