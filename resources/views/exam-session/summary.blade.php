@extends('layouts.app2')

@section('content')

  <x-card.main title="{!! $examSet->name !!}">
    <x-card.mini title="Latest Exam">
        <div class="w-full mx-auto shadow md:w-1/2 stats stats-vertical md:stats-horizontal">
            <div class="stat">
              <div class="stat-title text-accent">Grade</div>
              <div class="stat-value text-primary">{{ $session->grade }}%</div>
              <div class="stat-desc">Completed {{ $session->date_completed }}</div>
            </div>
          </div>
    </x-card.mini>

    <x-card.mini title="Answer Statistics">
          <div class="shadow stats stats-vertical md:stats-horizontal">
            <div class="stat">
              <div class="text-2xl stat-figure text-success">
                <i class="fa-solid fa-file-check"></i>
              </div>
              <div class="stat-title">Correct</div>
              <div class="stat-value text-success">{{ $session->correct_answers }}</div>
              <div class="stat-desc"></div>
            </div>

            <div class="stat">
              <div class="text-2xl stat-figure text-error">
                <i class="fa-solid fa-file-excel"></i>
              </div>
              <div class="stat-title">Incorrect</div>
              <div class="stat-value text-error">{{ $session->incorrect_answers }}</div>
              <div class="stat-desc"></div>
            </div>

            <div class="stat">
              <div class="text-2xl stat-figure text-info">
                <i class="fa-solid fa-newspaper"></i>
              </div>
              <div class="stat-title">Total Questions</div>
              <div class="stat-value text-info">{{ $session->question_count }}</div>
              <div class="stat-desc"></div>
            </div>
          </div>

        {{-- // TODO: Show mastery stats --}}
    </x-card.mini>
  </x-card.main>

  <x-card.main>
    <x-page.actions primary="Start Test" primaryLink="{{ route('exam-session.start', $examSet->id) }}" secondary="Exam Portal" secondaryLink="{{ route('profile.exams') }}" />
  </x-card.main>

  <x-card.main title="{!! $examSet->name !!} - Summary">
    <x-card.mini>
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
        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('test.color_mastered') }}">Mastered:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('test.color_mastered') }} " value="{{ $examRecord->mastery_mastered_count / $examSet->questions->count() * 100 }}" max="100"></progress></div>
      </div>
      <div class="flex w-full">
        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('test.color_proficient') }}">Proficient:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('test.color_proficient') }} " value="{{ $examRecord->mastery_proficient_count / $examSet->questions->count() * 100 }}" max="100"></progress></div>
      </div>
      <div class="flex w-full">
        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('test.color_familiar') }}">Familiar:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('test.color_familiar') }} " value="{{ $examRecord->mastery_familiar_count / $examSet->questions->count() * 100 }}" max="100"></progress></div>
      </div>
      <div class="flex w-full">
        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('test.color_apprentice') }}">Apprentice:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('test.color_apprentice') }} " value="{{ $examRecord->mastery_apprentice_count / $examSet->questions->count() * 100 }}" max="100"></progress></div>
      </div>
    </x-card.mini>
  </x-card.main>



     

@endsection
