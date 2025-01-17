@extends('layouts.app2', ['heading' => 'Take Exam - ' . $examSet->name ])

@section('content')

    <x-card.main title="{!! $examSet->name !!}">
        <x-text.dim>Question # {{ $session->current_question }} <span class="text-xs opacity-50">of {{ $session->question_count }}</span></x-text.dim>
        <x-card.mini>
            @php
                $length = 0;
                if ($question->group) {
                    $length += strlen($question->group->question);
                }

                $length += strlen($question->text);
                $textSize = ($length > 15) ? "text-lg" : "text-2xl";
            @endphp

            <h3 class="{{ $textSize }} leading-relaxed text-neutral-content">@if ($question->group) {!! $question->group->question !!} @endif</h3>
            <div id="markdown" class="w-full"><x-markdown>
                {!! $question->text !!}
            </x-markdown></div>
        </x-card.mini>
    </x-card.main>

    <div class="w-full mx-auto text-center">
        <flux:tab.group class="mx-auto mt-6">
            <flux:tabs variant="segmented">
                <flux:tab variant="segmented" name="results">Question Result</flux:tab>
                <flux:tab variant="segmented" name="insights">Instructor Insights</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="results">
                <x-card.main>

                    <div class="flex w-full">
                        <div class="w-1/3 text-left">

                        </div>
                        <div class="w-1/3 text-center">
                            <a href="{{ route('exam-session.toggleReviewFlag', ['set' => $examSet, 'question' => $question]) }}" class="btn"><i class="{{ config('icon.review_flag') }} @if ($userQuestionStats->reviewFlagged) text-{{ config('color.review_flag_on') }} @else text-{{ config('color.review_flag_off') }} @endif text-3xl"></i></a>
                        </div>

                        <div class="w-2/3 text-right">
                            <a href="{{ route('exam-session.test', $examSet) }}" class="btn btn-primary btn-outline">Next Question</a>
                        </div>
                    </div>

                    <x-card.mini>
                        <div class="shadow stats">
                            <div class="text-center stat">
                                <div class="stat-figure text-success">
                                </div>
                                <div class="stat-title" id="scroll-to">You got this answer </div>
                                @if ($result)
                                    <div class="stat-value text-success">Correct</div>
                                @else
                                    <div class="stat-value text-error">Incorrect</div>
                                @endif
                            <div class="stat-desc"></div>
                            </div>
                        </div>
                    </x-card.mini>

                    <x-card.mini title="Your Answer">
                        @foreach ($answers as $answer)
                            <div class="flex items-center p-2 rounded-lg hover:bg-base-200">
                                <div class="flex items-center w-1/4 lg:w-1/6">
                                    @if ($normalizedAnswer[$answer['id']])
                                        <input type="checkbox" checked="checked" disabled class="mr-2 checkbox checkbox-primary">
                                    @else
                                        <input type="checkbox" disabled class="mr-2 checkbox checkbox-primary">
                                    @endif
                                </div>
                                <div class="flex w-3/4 text-left lg:w-5/6">
                                    @if ($answer['correct'])
                                        <i class="mr-2 fa-regular fa-square-check text-success"></i>
                                        <span class="font-bold text-success">{{ $answer['text'] }}</span>
                                    @else
                                        <i class="mr-2 fa-regular fa-square-xmark text-error"></i>
                                        <span class="text-gray-500 line-through">{{ $answer['text'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </x-card.mini>

                    <x-card.mini title="Your Mastery">
                        <ul class="w-full md:w-3/4 lg:w-1/2 timeline timeline-vertical">
                            @for ($i = (config('test.grade_mastered')); $i > 0; $i --)
                                <li>
                                    @if ($i == $userQuestionStats->score)
                                        <div class="timeline-start">
                                            @if ($userQuestionStats->score > $previousScore)
                                                @if ($previousScore == 0) <div class="badge badge-accent"><i class="{{ config('icon.bonus_mastery') }} mr-2"></i> First Seen</div> @endif
                                                <div class="badge badge-secondary">Mastery: + {{ $userQuestionStats->score - $previousScore }}</div>
                                            @else
                                                @if ($userQuestionStats->score == $previousScore ) <div class="badge badge-info">Keeper's Grace</div> @endif
                                                <div class="badge badge-secondary">Mastery: - {{ $previousScore - $userQuestionStats->score }}</div>
                                            @endif
                                        </div>
                                    @endif

                                    @if ($i < (config('test.grade_mastered')))
                                        <hr @if ($userQuestionStats->score > $i) class="bg-primary" @endif />
                                    @endif
                                    @if ($userQuestionStats->score >= $i)
                                        <div class="timeline-middle text-primary">
                                            <i class="fa-solid fa-circle"></i>
                                        </div>
                                    @else
                                        <div class="timeline-middle text-base-300">
                                            <i class="fa-regular fa-circle"></i>
                                        </div>
                                    @endif
                                        @if ( $i == config('test.grade_mastered') )
                                            <div class="timeline-end timeline-box @if ($userQuestionStats->score >= config('test.grade_mastered')) text-{{ config('color.mastered') }} @else text-neutral @endif">
                                                Mastered
                                            </div>
                                        @elseif ( $i == config('test.grade_proficient') )
                                            <div class="timeline-end timeline-box @if ($userQuestionStats->score >= config('test.grade_proficient')) text-{{ config('color.proficient') }} @else text-neutral @endif">
                                                Proficient
                                            </div>
                                        @elseif ( $i == config('test.grade_familiar') )
                                            <div class="timeline-end timeline-box @if ($userQuestionStats->score >= config('test.grade_familiar')) text-{{ config('color.familiar') }} @else text-neutral @endif">
                                                Familiar
                                            </div>
                                        @elseif ( $i == config('test.grade_apprentice') )
                                            <div class="timeline-end timeline-box @if ($userQuestionStats->score >= config('test.grade_apprentice')) text-{{ config('color.apprentice') }} @else text-neutral @endif">
                                                Apprentice
                                            </div>
                                        @else
                                            <div class="timeline-end">
                                                &nbsp;
                                            </div>
                                        @endif
                                    @if ($i > 1)
                                        <hr @if ($userQuestionStats->score >= $i) class="bg-primary" @endif />
                                    @endif
                                </li>
                            @endfor
                        </ul>
                    </x-card.mini>

                    <div class="w-full text-right">
                        @can('update', $question)
                            <a href="{{ route('exam.question', ['exam' => $examSet, 'question' => $question]) }}" target="_blank" class="mx-4 btn btn-secondary btn-outline">Edit Question</a>
                        @endcan
                        <a href="{{ route('exam-session.test', $examSet) }}" class="btn btn-primary btn-outline">Next Question</a>
                    </div>
                </x-card.main>
            </flux:tab.panel>

            <flux:tab.panel name="insights">
                @livewire('question-insights-display', ['question' => $question])
            </flux:tab.panel>
        </flux:tab.group>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.innerWidth < 768) {
                document.getElementById('scroll-to').scrollIntoView({
                    behavior: 'instant'
                });
            }
        });
    </script>

@endsection
