@extends('layouts.app2')

@section('content')

    <x-card.main title='{{ $exam->name }}' size='lg'>
            <x-card.mini>
                <div class="p-2 text-lg">{{ $exam->description }}</div>
                <br />
                @if ($exam->user_id) <div class="block p-3 m-1 text-lg md:flex badge badge-{{ config('color.author') }} tooltip" data-tip="Author: {{ $exam->user->name }}"><i class="{{ config('icon.author') }} mx-2"></i> {{ $exam->user->name }}</div> @endif
            </x-card.mini>

            <x-card.mini title="Exam Stats">
                <div class="shadow stats stats-vertical md:stats-horizontal">
                    <div class="stat">
                        <div class="stat-title"># of Questions</div>
                        <div class="stat-value text-primary">{{ $exam->questions->count() }}</div>
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

            @if ($examRecord)
                <x-card.mini title="Your Stats">
                    <div class="w-full shadow stats stats-vertical md:stats-horizontal">
                        <div class="stat">
                        <div class="text-2xl stat-figure text-secondary">
                            <i class="fa-solid fa-cubes-stacked"></i>
                        </div>
                        <div class="stat-title">Times Taken</div>
                        <div class="stat-value">{{ $examRecord->times_taken }}</div>
                        <div class="stat-desc">Last: {{ $examRecord->last_completed }}</div>
                        </div>
                        <div class="stat">
                        <div class="text-2xl stat-figure text-secondary">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                        <div class="stat-title">Average Score</div>
                        <div class="stat-value">{{ $examRecord->recent_average }}%</div>
                        <div class="stat-desc">Previous {{ config('test.count_tests_for_average_score') }} Exams</div>
                        </div>
                    </div>
                </x-card.mini>

                <x-card.mini title="Your Mastery Level">  
                    <div class="flex w-full">
                        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('test.color_mastered') }}">Mastered:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('test.color_mastered') }} " value="{{ $examRecord->mastery_mastered_count / $exam->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('test.color_proficient') }}">Proficient:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('test.color_proficient') }} " value="{{ $examRecord->mastery_proficient_count / $exam->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('test.color_familiar') }}">Familiar:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('test.color_familiar') }} " value="{{ $examRecord->mastery_familiar_count / $exam->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('test.color_apprentice') }}">Apprentice:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('test.color_apprentice') }} " value="{{ $examRecord->mastery_apprentice_count / $exam->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                </x-card.mini>
            @endif
        
            <x-card.mini>
                <x-card.buttons primaryLabel="Take Exam" primaryAction="{{ route('exam-session.start', $exam) }}" />
            </x-card.mini>
    
    </x-card.main>

@endsection