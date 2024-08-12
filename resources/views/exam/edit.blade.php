@extends('layouts.app2')

@section('content')
<x-card.main title="{!! $exam->name !!}">

    <x-card.mini title="Exam Stats">
        Total Questions: {{ $exam->questions->count() }}
    </x-card.mini>

    <div class="collapse collapse-arrow">
        <input type="checkbox">
        <div class="w-1/2 mx-auto collapse-title btn btn-secondary btn-outline btn-md">Show/Hide Settings</div>
        <div class="collapse-content">
            <x-card.mini title="Exam Settings">
                <form action="{{ route('exam.update', $exam) }}" method="POST">
                    @csrf
                    <x-form.text name="name" label="Name" value="{!! $exam->name !!}" />
                    <x-form.text name="description" label="Description" value="{!! $exam->description !!}" />
                    @php
                        foreach ($visibilityOptions as $visibility)
                        {
                            $visibilityValues[$visibility->value] = str_replace("is", "", $visibility->name);
                        }
                    @endphp

                    @if ($exam->questions->count() >= config('test.min_public_questions'))
                        @feature('mage-upgrade')
                            @if (!$exam->user->isMage)
                                @if (!$exam->isPublished)
                                    <div class="badge"><span class="tooltip" data-tip="Publish Credits"><i class="{{ config('icon.credit') }} text-{{ config('color.credit') }} text-lg"></i> <i class="{{ config('icon.publish_credit') }} text-{{ config('color.publish_credit') }} text-lg"></i> {{ $exam->user->credit->publish }}</span></div>

                                    <x-help.box>
                                        <x-help.text><x-help.highlight>Publish Credits</x-help.highlight> allow you to make an exam <x-help.highlight color="accent">Public</x-help.highlight> while you are on a free account.</x-help.text>
                                        <x-help.text>Once you spend a <x-help.highlight color="none">Publish Credit</x-help.highlight> on an exam you are free to <x-help.highlight color="secondary">switch between public and private</x-help.highlight> as often as you need to.</x-help.text>
                                        <x-help.text>If you need more credits, <x-help.highlight>Master</x-help.highlight> more exams, and get other people to <x-help.highlight color="none">Master</x-help.highlight> your exams.</x-help.text>
                                        <x-help.text>Or <x-help.highlight color="accent">upgrade to Mage to unlock all features</x-help.highlight> of <x-help.highlight color="primary">Acolyte Academy</x-help.highlight>. This helps support the site so we can continue to exist and add new features.</x-help.text>
                                    </x-help.box>
                                @endif
                                <x-form.dropdown name="visibility" label="Public / Private" :values="$visibilityValues" selected="{{ $exam->visibility }}" />
                            @else
                                <x-form.dropdown name="visibility" label="Public / Private" :values="$visibilityValues" selected="{{ $exam->visibility }}" />
                            @endif
                        @else            
                            <x-form.dropdown name="visibility" label="Public / Private" :values="$visibilityValues" selected="{{ $exam->visibility }}" />
                        @endfeature
                    @else
                        <x-text.main>A test must have at least <span class="font-bold text-accent">{{ config('test.min_public_questions') }} Questions</span> before it can be made public.</x-text.main>
                        <x-text.dim>Progress: {{ $exam->questions->count() }} / {{ config('test.min_public_questions') }}</x-text.dim>
                    @endif
                    
                    <x-help.box>
                        <x-help.text>The exams <x-help.highlight>Visibility</x-help.highlight> determines who can see or take an exam.</x-help.text>
                        <x-help.text>If you set the exam to <x-help.highlight>Private</x-help.highlight> then only you, the exam's <x-help.highlight color="info">Architect</x-help.highlight>, can see the exam or take.</x-help.text>
                        <x-help.text>If you set the exam to <x-help.highlight>Public</x-help.highlight> then every Acolyte at the academy will be able to see the exam and take it, starting their own journey down the path of mastery.</x-help.text>
                        <x-help.text>Note that an Exam must have <x-help.highlight color="accent">{{ config('test.min_public_questions') }} Questions</x-help.highlight> before it is eligable to be made Public.</x-help.text>
                    </x-help.box>

                    <x-card.buttons submitLabel="Update Exam Settings" />
                </form>
            </x-card.mini>
        </div>
    </div>
</x-card.main>

<x-card.main>
    <x-card.mini>
        <a href="{{ route('exam-session.start', $exam) }}" class="my-2 btn btn-outline btn-secondary">Take exam</a>
        <a href="{{ route('exam.index') }}" class="my-2 btn btn-secondary">Back to Manage Exams</a>
    </x-card.mini>
</x-card.main>

<x-card.main title="Question Groups">
    <x-card.mini>
        <x-help.box>
            <x-help.text>Hey there! I bet you're wondering what this whole <x-help.highlight>Question Groups</x-help.highlight> thing is about.</x-help.text>
            <x-help.text><x-help.highlight color="normal">Question Groups</x-help.highlight> allow you to group questions together that have similar answers. These can be similar in <x-help.highlight color="info">appearance</x-help.highlight> or <x-help.highlight color="info">content</x-help.highlight>.</x-help.text>
            <x-help.text>Unlike regualr test questions, when you add a question to a <x-help.highlight color="normal">Question Group</x-help.highlight> you only have to specify <x-help.highlight color="secondary">the one correct answer</x-help.highlight>.</x-help.text>
            <x-help.text>The real magic happens when an acolyte takes an exam.</x-help.text>
            <x-help.text>When a question shows up from your <x-help.highlight color="normal">Question Groups</x-help.highlight>, the incorrect answers are <x-help.highlight color="accent">magically</x-help.highlight> selected from the other questions in the same group.</x-help.text>
            <x-help.text>This helps make your exam dynamic. You don't have to worry about figuring out the best fake answers to put in to test the acolytes. As long as all of the answers in your <x-help.highlight color="normal">Question Groups</x-help.highlight> are similar then it will give the other acolytes a challenging exam.</x-help.text>
            <x-help.text>And it makes exam creation easier for you as well!</x-help.text>
            <x-help.text>You can have as many <x-help.highlight color="normal">Question Groups</x-help.highlight> as you need.</x-help.text>
        </x-help.box>

        <x-table.main>
            <x-table.head>
                <x-table.hcell>Name</x-table.hcell>
                <x-table.hcell># Questions</x-table.hcell>
                <x-table.hcell>&nbsp;</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach ($exam->groups as $group)
                    <x-table.row>
                        <x-table.cell>{{ $group->name }}</x-table.cell>
                        <x-table.cell>{{ $group->questions->count() }}</x-table.cell>
                        <x-table.cell><a href="{{ route('group-view', $group) }}" class="btn btn-secondary btn-outline">Manage Question Group</a></x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>
    </x-card.mini>

    
    <div class="collapse collapse-arrow">
        <input type="checkbox">
        <div class="w-1/2 mx-auto collapse-title btn btn-md btn-secondary btn-outline">Create Question Group</div>
        <div class="collapse-content">
            <x-card.mini title="Create Question Group">
                <form action="{{ route('group-store', $exam->id) }}" method="post">
                    @csrf
    
                    <x-form.text name="name" label="Name" />
    
                    <x-card.buttons submitLabel="Add Group" />
                </form>
            </x-card.mini>
        </div>        
    </div>
</x-card.main>

<x-card.main title="Test Questions">
    <x-card.mini>
        <div class="block w-full lg:flex">

            @feature('mage-upgrade')
                @if (!auth()->user()->isMage)
                    <div><span class="tooltip" data-tip="Question Credits Remaining"><i class="{{ config('icon.credit') }} text-{{ config('color.credit') }} text-lg"></i> <i class="{{ config('icon.question_credit') }} text-{{ config('color.question_credit') }} text-lg"></i> {{ $exam->user->credit->question }}</span></div>
                @endif
            @endfeature

            <span class="mx-4 tooltip" data-tip="Question Count"><i class="{{ config('icon.question_count') }} text-{{ config('color.question_count') }} text-lg"></i> {{ $exam->questions->count() }} / {{ config('test.max_exam_questions') }}</span>
        </div>
    </x-card.mini>

    @feature('mage-upgrade')
        @if (!auth()->user()->isMage)
            <x-help.box>
                <x-help.text>I bet you are wondering what these <x-help.highlight>Question Credits</x-help.highlight> are all about.</x-help.text>
                <x-help.text>As a free user at <x-help.highlight color="primary">Acolyte Academy</x-help.highlight> you are limited for how many questions you can add to exams that you manage.</x-help.text>
                <x-help.text>Every question that you add to an exam will cost you <x-help.highlight color="accent">1 Question Credit</x-help.highlight>. When you run out of credits you will no longer be able to add more questions to any exam.</x-help.text>
                <x-help.text>This helps make sure that you have lots of ability to try out this website and its features, and you can create an exam that you really need to start learning for free.</x-help.text>
                <x-help.text>You will also be <x-help.highlight color="secondary">Awarded extra Question Credits</x-help.highlight> when you achieve a <x-help.highlight color="{{ config('color.mastered') }}">Mastered</x-help.highlight> rating on a test, or when <x-help.highlight color="secondary">other people reach Mastered with one of your public exams</x-help.highlight> you will also be awarded credits.</x-help.text>
                <x-help.text>If you want to have unlimited access to <x-help.highlight color="none">Acolyte Academny</x-help.highlight> then you can <x-help.highlight color="success">Upgrade to a Mage Account</x-help.highlight>! This helps our academy continue to exist in this world and allows us to keep updating with newer features.</x-help.text>
            </x-help.box>
        @endif
    @endfeature

    <div class="mx-5 divider"></div>
    
    <x-card.mini title="Add Quesiton">
        <form action="{{ route('exam.add', $exam) }}" method="post">
            @csrf
            <h3 class="text-lg font-bold text-secondary">Question</h3>
            <x-form.text name="question" value="{{ old('question') }}" />

            <h3 class="text-lg font-bold text-secondary">Answers</h3>
            @for ($i = 1; $i <= 4; $i ++)
                <div class="divider">Answer #{{ $i }}</div>
                <div class="block lg:flex">
                    <div class="w-full px-4 lg:w-1/4">
                        @if ($i == 1)
                            <x-form.checkbox name="correct[{{ $i }}]" label="Answer is correct?" checked="yes" />
                        @else
                            <x-form.checkbox name="correct[{{ $i }}]" label="Answer is correct?"  />
                        @endif
                    </div>

                    <div class="w-full lg:w-3/4">
                        <x-form.text name="answers[{{ $i }}]" value="{{ old('answers[' . $i . ']') }}" />
                    </div>
                </div>
            @endfor

            <div class="w-full my-2 text-right">
                <input type="submit" value="Add Question" class="btn btn-primary">
            </div>
        </form>
    </x-card.mini>

    <x-card.mini title="Normal Questions">
        <x-table.main>
            <x-table.head>
                <x-table.hcell>{{ __('Question') }}</x-table.hcell>
                <x-table.hcell hideMobile='true'>{{ __('# Answers') }}</x-table.hcell>
                <x-table.hcell>{{ __('Actions') }}</x-table.hcell>
            </x-table.head>
            <x-table.body>
                @foreach ($questions as $question)
                    <x-table.row>
                        <x-table.cell>{{ $question->text }}</x-table.cell>
                        <x-table.cell hideMobile='true'>{{ $question->answers->count() }}</x-table.cell>
                        <x-table.cell>
                            <a href="{{ route('exam.question', ['exam' => $exam, 'question' => $question]) }}" class="btn btn-secondary btn-outline">Edit Question</a>
                        </x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>

    </x-card.mini>
</x-card.main>

<div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
    <a href="{{ route('exam.index') }}" class="btn btn-secondary">{{ __('Manage Your Exams') }}</a>
</div>
@endsection
