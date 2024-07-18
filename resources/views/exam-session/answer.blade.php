@extends('layouts.app2')

@section('content')

    <x-card.main title="{{ $examSet->name }}">
        <x-text.dim>Question # {{ $session->current_question }} <span class="text-xs opacity-50">of {{ $session->question_count }}</span></x-text.dim>
        <x-card.mini>
            <h3 class="text-3xl text-neutral-content">{{ $question->text }}</h3>
        </x-card.mini>
        
        <x-card.mini>
            <div class="shadow stats">
                <div class="text-center stat">
                  <div class="stat-figure text-success">
                  </div>
                  <div class="stat-title">You got this answer </div>
                  <div class="stat-value text-success">@if ($correct) Correct @else Incorrect @endif</div>
                  <div class="stat-desc"></div>
                </div>
            </div>
        </x-card.mini>
    
        <x-card.mini title="Your Answer">
            @foreach ($answers as $answer)
                <div class="flex items-center p-2 rounded-lg hover:bg-base-200">
                    <div class="flex items-center w-1/4">
                        @if ($normalizedAnswer[$answer['id']])
                            <input type="checkbox" checked="checked" disabled class="mr-2 checkbox checkbox-primary">
                        @else
                            <input type="checkbox" disabled class="mr-2 checkbox checkbox-primary">
                        @endif
                    </div>
                    <div class="flex items-center w-3/4">
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

            <ul class="w-1/4 timeline timeline-vertical">
                @for ($i = (config('test.grade_mastered')); $i > 0; $i --)
                    <li>
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
                                <div class="timeline-end timeline-box">
                                    Mastered
                                </div>
                            @elseif ( $i == config('test.grade_proficient') )
                                <div class="timeline-end timeline-box">
                                    Proficient
                                </div>
                            @elseif ( $i == config('test.grade_familiar') )
                                <div class="timeline-end timeline-box">
                                    Familiar
                                </div>
                            @elseif ( $i == config('test.grade_apprentice') )
                                <div class="timeline-end timeline-box">
                                    Apprentice
                                </div>
                            @else
                                <div class="timeline-end">&nbsp;</div>
                            @endif
                        @if ($i > 1)
                            <hr @if ($userQuestionStats->score >= $i) class="bg-primary" @endif />
                        @endif
                    </li>
                @endfor
            </ul>
        </x-card.mini>
        <x-page.actions primary="Next Question" :primaryLink="route('exam-session.test', $examSet->id)" />
    </x-card.main>
            

@endsection
