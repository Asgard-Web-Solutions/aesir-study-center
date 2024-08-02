@extends('layouts.app2')

@section('content')

    <x-card.main title='{!! $exam->name !!}' size='lg'>
        <x-card.mini>
            <div class="p-2 text-lg">{!! $exam->description !!}</div>
            <br />
            <div class="flex w-full py-2 my-2 rounded-lg bg-base-100">
                @if ($exam->user) <a href="{{ route('profile.view', $exam->user) }}"><x-user.avatar size="tiny">{{ $exam->user->gravatarUrl(64) }}</x-user.avatar></a> <a href="{{ route('profile.view', $exam->user) }}" class="link link-secondary">{{ $exam->user->name }}</a> @endif
                <span class="mx-4 tooltip text-accent" data-tip="Question Count"><i class="mr-1 text-lg fa-regular fa-block-question"></i> {{ $exam->questions->count() }}</span>
            </div>
        </x-card.mini>
    </x-card.main>

    <x-card.main title="Exam Stats">

        <x-card.mini title="">
            <div class="shadow stats stats-vertical md:stats-horizontal">
                <div class="stat">
                    <div class="text-2xl stat-figure text-secondary">
                        <i class="{{ config('icon.question_count') }} text-{{ config('color.question_count') }}"></i>
                    </div>
                    <div class="stat-title"># of Questions</div>
                    <div class="stat-value text-{{ config('color.question_count') }}">{{ $exam->questions->count() }}</div>
                </div>

                <div class="stat">
                    <div class="stat-title"># of Users</div>
                    <div class="stat-value text-secondary">{{ $exam->records->count() }}</div>
                </div>
            </div>

            <br />
            <hr />
            <x-text.dim>Date Create: {{ $exam->created_at }}</x-text.dim>
        </x-card.mini>
    
        <x-card.mini>
            <x-card.buttons primaryLabel="Take Exam" primaryAction="{{ route('exam-session.start', $exam) }}" secondaryLabel="Practice" secondaryAction="{{ route('practice.start', $exam) }}" />
        </x-card.mini>

    </x-card.main>

    @if ($examRecord)
        <x-card.main title="Your History">
            <x-card.mini title="Statistics">
                <div class="w-full shadow stats stats-vertical md:stats-horizontal">
                    <div class="stat">
                    <div class="stat-title">Highest Mastery</div>
                    <div class="w-full my-2 text-center stat-value text-{{ config('color.' . strtolower($mastery[$examRecord->highest_mastery])) }}"><i class="text-5xl {{ config('icon.' . strtolower($mastery[$examRecord->highest_mastery])) }}"></i></div>
                    <div class="stat-desc">{{ $mastery[$examRecord->highest_mastery] }}</div>
                    </div>
                    <div class="stat">
                    <div class="text-2xl stat-figure text-secondary">
                        <i class="{{ config('icon.times_taken') }} text-{{ config('color.times_taken') }}"></i>
                    </div>
                    <div class="stat-title">Times Taken</div>
                    <div class="stat-value text-{{ config('color.times_taken') }}">{{ $examRecord->times_taken }}</div>
                    <div class="stat-desc">Last: {{ $examRecord->last_completed }}</div>
                    </div>
                    <div class="stat">
                    <div class="text-2xl stat-figure text-secondary">
                        <i class="{{ config('icon.recent_average') }} text-{{ config('color.recent_average') }}"></i>
                    </div>
                    <div class="stat-title">Average Score</div>
                    <div class="stat-value text-{{ config('color.recent_average') }}">{{ $examRecord->recent_average }}%</div>
                    <div class="stat-desc">Previous {{ config('test.count_tests_for_average_score') }} Exams</div>
                    </div>
                </div>
                </x-card.mini>
                <x-card.mini title="Your Mastery Progress">
                <div class="shadow">
                    <div class="flex w-full">
                    <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('color.mastered') }}">Mastered:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('color.mastered') }} " value="{{ $examRecord->mastery_mastered_count / $exam->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                    <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('color.proficient') }}">Proficient:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('color.proficient') }} " value="{{ $examRecord->mastery_proficient_count / $exam->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                    <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('color.familiar') }}">Familiar:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('color.familiar') }} " value="{{ $examRecord->mastery_familiar_count / $exam->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                    <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('color.apprentice') }}">Apprentice:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('color.apprentice') }} " value="{{ $examRecord->mastery_apprentice_count / $exam->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                </div>
            </x-card.mini>
        </x-card.main>
    @endif

    <x-card.main title="Exam Masters Hall of Fame">
        @forelse ($masters as $master)
            <x-card.mini title="{{ $master->user->name }}" >

                <div class="flex w-full md:flex">
                    <div class="w-1/2 md:w-1/4">
                        <a href="{{ route('profile.view', $master->user) }}"><x-user.avatar size='lg'>{{ $master->user->gravatarUrl(256) }}</x-user.avatar></a>
                    </div>
                    <div class="w-full mx-auto mb-4 md:w-1/4 tooltip" data-tip="{{ $mastery[$master->records[0]->pivot->highest_mastery] }}">
                        <i class="
                            text-5xl
                            text-{{ config('color.' . strtolower($mastery[$master->records[0]->pivot->highest_mastery])) }} 
                            {{ config('icon.' . strtolower($mastery[$master->records[0]->pivot->highest_mastery])) }}
                            rounded-lg ring-2 p-1 ring-base-300
                        "></i>
                    </div>
                </div>
                
                <div class="flex w-full py-2 my-2 rounded-lg bg-base-100">
                    <span class="mx-2 tooltip text-{{ config('color.times_taken') }}" data-tip="Times Taken"><i class="mr-1 text-lg {{ config('icon.times_taken') }}"></i> {{ $master->records[0]->pivot->times_taken }}</span>
                    <span class="mx-2 tooltip text-{{ config('color.recent_average') }}" data-tip="Recent Average"><i class="mr-1 text-lg {{ config('icon.recent_average') }}"></i> {{ $master->records[0]->pivot->recent_average }}</span>
                </div>
            </x-card.mini>
        @empty
            <x-card.mini>
                <x-text.main>There are no masters for this exam. Be the first?</x-text.main>
            </x-card.mini>
        @endforelse
    </x-card.main>

@endsection