@extends('layouts.app2', ['heading' => 'EDIT - ' . $exam->name ])

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
                            <x-form.dropdown name="visibility" label="Public / Private" :values="$visibilityValues" selected="{{ $exam->visibility }}" />
                        @else
                            <x-form.dropdown name="visibility" label="Public / Private" :values="$visibilityValues" selected="{{ $exam->visibility }}" />
                        @endfeature
                    @else
                        <x-text.main>A test must have at least <span class="font-bold text-accent">{{ config('test.min_public_questions') }} Questions</span> before it can be made public.</x-text.main>
                        <x-text.dim>Progress: {{ $exam->questions->count() }} / {{ config('test.min_public_questions') }}</x-text.dim>
                    @endif

                    <x-form.checkbox name="multi_lesson_exam" label="Multi-Lesson Exam" checked="{{ $exam->multi_lesson_exam ? 'yes' : '' }}" />

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
        <a href="{{ route('exam.index') }}" class="my-2 btn btn-secondary">Back to Exam Manager</a>
    </x-card.mini>
</x-card.main>

@if ($exam->multi_lesson_exam)
<x-card.main title="Lessons">
    <x-card.mini>
        <x-help.box>
            <x-help.text>Lessons allow you to organize your exam questions into smaller, focused topics.</x-help.text>
            <x-help.text>When an acolyte takes your exam, they can choose to study <x-help.highlight>All Lessons</x-help.highlight> or focus on a specific lesson.</x-help.text>
            <x-help.text>Add lessons below, then assign them to your questions and question groups.</x-help.text>
        </x-help.box>

        @if (!empty($exam->lessons) && count($exam->lessons) > 0)
            <div class="my-4">
                <h3 class="mb-2 text-lg font-bold text-secondary">Current Lessons</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($exam->lessons as $lesson)
                        <div class="badge badge-lg badge-primary">
                            {{ $lesson->name }}
                            <form action="{{ route('exam.update', $exam) }}" method="POST" class="inline ml-2">
                                @csrf
                                <input type="hidden" name="remove_lesson" value="{{ $lesson->id }}">
                                <button type="submit" class="ml-1 text-white hover:text-error"><i class="fa-solid fa-xmark"></i></button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="divider"></div>

        <form action="{{ route('exam.update', $exam) }}" method="POST">
            @csrf
            <x-form.text name="new_lesson" label="Add New Lesson" placeholder="e.g., Introduction, Chapter 1, Advanced Topics" />
            <x-card.buttons submitLabel="Add Lesson" />
        </form>
    </x-card.mini>
</x-card.main>
@endif

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
            <span class="mx-4 tooltip" data-tip="Question Count"><i class="{{ config('icon.question_count') }} text-{{ config('color.question_count') }} text-lg"></i> {{ $exam->questions->count() }} / {{ config('test.max_exam_questions') }}</span>
        </div>
    </x-card.mini>

    <div class="mx-5 divider"></div>

    <x-card.mini title="Create a Question">
        <form action="{{ route('exam.add', $exam) }}" method="post">
            @csrf
            <h3 class="text-lg font-bold text-secondary">Question</h3>
            <x-form.textarea name="question" rows="4" value="{{ old('question') }}" />

            @if ($exam->multi_lesson_exam)
                @php
                    $lessonOptions = ['' => 'No Lesson'];
                    foreach ($exam->lessons as $lesson) {
                        $lessonOptions[$lesson->id] = $lesson->name;
                    }
                @endphp
                <x-form.dropdown name="lesson_id" label="Lesson (Optional)" :values="$lessonOptions" selected="{{ old('lesson_id') }}" />
            @endif

            <h3 class="text-lg font-bold text-secondary">Answers</h3>
            @for ($i = 1; $i <= config('test.number_answers_to_add'); $i ++)
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
                <input type="submit" value="Save Question" class="btn btn-primary">
            </div>
        </form>
    </x-card.mini>

    <div id="questions-section"></div>
    <x-card.mini title="Normal Questions">
        @if ($exam->multi_lesson_exam)
            <div class="mb-4">
                <form method="GET" action="{{ route('exam.edit', $exam) }}">
                    <label class="block mb-2 text-sm font-bold">Filter by Lesson</label>
                    <select name="lesson_filter" class="w-full select select-bordered" onchange="this.form.submit()">
                        <option value="" {{ !isset($lessonFilter) || $lessonFilter === null ? 'selected' : '' }}>All Lessons</option>
                        <option value="no_lesson" {{ isset($lessonFilter) && $lessonFilter === 'no_lesson' ? 'selected' : '' }}>No Lesson</option>
                        @foreach ($exam->lessons as $lesson)
                            <option value="{{ $lesson->id }}" {{ isset($lessonFilter) && $lessonFilter == $lesson->id ? 'selected' : '' }}>{{ $lesson->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif
        
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
                            <div class="dropdown">
                                <div class="m-1 btn btn-secondary btn-sm btn-outline" tabindex="0" role="button">Question Actions...</div>
                                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                                    <li><a href="{{ route('exam.question', ['exam' => $exam, 'question' => $question]) }}" class=""><i class="{{ config('icon.edit_exam') }} text-{{ config('color.edit_exam') }} text-lg"></i> Edit Question</a></li>
                                    <li><a href="{{ route('exam.questionDelete', ['exam' => $exam, 'question' => $question]) }}" ><i class="{{ config('icon.delete') }} text-{{ config('color.delete') }} text-lg"></i> Delete Question</a></li>
                                </ul>
                            </div>
                            @if ($question->insights->count())
                                <span class="mx-4 tooltip tooltip-info" data-tip="Question has Mastery Insight"><i class="{{ config('icon.mastery_insight') }} {{ config('color.mastery_insight_on') }} text-xl"></i></span>
                            @else
                                <span class="mx-4 tooltip tooltip-info" data-tip="Question missing Mastery Insight"><i class="{{ config('icon.mastery_insight') }} {{ config('color.mastery_insight_off') }} text-xl"></i></span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @endforeach
            </x-table.body>
        </x-table.main>

    </x-card.mini>
</x-card.main>

<x-card.main>
    <div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
        <a href="{{ route('exam.index') }}" class="btn btn-secondary">{{ __('Back to Exam Manager') }}</a>
    </div>
</x-card.main>

@if(request()->has('lesson_filter'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const element = document.getElementById('questions-section');
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
</script>
@endif

@endsection
