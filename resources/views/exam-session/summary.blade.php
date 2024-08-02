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

    <x-card.mini title="Mastery Change">
      <div class="shadow stats stats-vertical md:stats-horizontal">
        <div class="stat">
          <div class="stat-title text-{{ config('color.apprentice') }}">Apprentice</div>
          @if ($session->mastery_apprentice_change > 0)
            <div class="stat-value text-warning">+ {{ $session->mastery_apprentice_change }}</div>
          @elseif ($session->mastery_apprentice_change < 0)
            <div class="stat-value text-error">{{ $session->mastery_apprentice_change }}</div>
          @else
            <div class="stat-value text-neutral">0</div>  
          @endif
          <div class="stat-desc"></div>
        </div>

        <div class="stat">
          <div class="stat-title text-{{ config('color.familiar') }}">Familiar</div>
          @if ($session->mastery_familiar_change > 0)
            <div class="stat-value text-warning">+ {{ $session->mastery_familiar_change }}</div>
          @elseif ($session->mastery_familiar_change < 0)
            <div class="stat-value text-error">{{ $session->mastery_familiar_change }}</div>
          @else
            <div class="stat-value text-neutral">0</div>  
          @endif
          <div class="stat-desc"></div>
        </div>

        <div class="stat">
          <div class="stat-title text-{{ config('color.proficient') }}">Proficient</div>
          @if ($session->mastery_proficient_change > 0)
            <div class="stat-value text-warning">+ {{ $session->mastery_proficient_change }}</div>
          @elseif ($session->mastery_proficient_change < 0)
            <div class="stat-value text-error">{{ $session->mastery_proficient_change }}</div>
          @else
            <div class="stat-value text-neutral">0</div>  
          @endif
          <div class="stat-desc"></div>
        </div>

        <div class="stat">
          <div class="stat-title text-{{ config('color.mastered') }}">Mastered</div>
          @if ($session->mastery_mastered_change > 0)
            <div class="stat-value text-warning">+ {{ $session->mastery_mastered_change }}</div>
          @elseif ($session->mastery_mastered_change < 0)
            <div class="stat-value text-error">{{ $session->mastery_mastered_change }}</div>
          @else
            <div class="stat-value text-neutral">0</div>  
          @endif
          <div class="stat-desc"></div>
        </div>

      </div>
    </x-card.mini>
  </x-card.main>

  <x-card.main>
    <div class="block object-center w-full text-center md:flex">
        <div class="w-full text-center md:w-1/2 md:text-right">
          <a href="{{ route('exam-session.start', $examSet->id) }}" class="mx-2 btn btn-primary"><i class="{{ config('icon.take_exam') }} text-lg"></i> Retake Exam</a>
        </div>
        <div class="w-full text-center md:text-left md:w-1/2">
          <a href="{{ route('profile.exams') }}" class="mx-2 btn btn-secondary"><i class="{{ config('icon.manage_exams') }} text-lg"></i> Manage Exams</a>
        </div>
    </div>
  </x-card.main>

  <x-card.main title="{!! $examSet->name !!} - Summary">
    <x-card.mini>
      <div class="w-full shadow stats stats-vertical md:stats-horizontal">
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
    <x-card.mini title="Your Mastery Level">
      <div class="flex w-full">
        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('color.mastered') }}">Mastered:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('color.mastered') }} " value="{{ $examRecord->mastery_mastered_count / $examSet->questions->count() * 100 }}" max="100"></progress></div>
      </div>
      <div class="flex w-full">
        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('color.proficient') }}">Proficient:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('color.proficient') }} " value="{{ $examRecord->mastery_proficient_count / $examSet->questions->count() * 100 }}" max="100"></progress></div>
      </div>
      <div class="flex w-full">
        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('color.familiar') }}">Familiar:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('color.familiar') }} " value="{{ $examRecord->mastery_familiar_count / $examSet->questions->count() * 100 }}" max="100"></progress></div>
      </div>
      <div class="flex w-full">
        <div class="w-1/2 md:w-1/4 text-sm row text-{{ config('color.apprentice') }}">Apprentice:</div><div class="w-1/2 md:w-3/4"><progress class="w-36 md:w-64 progress progress-{{ config('color.apprentice') }} " value="{{ $examRecord->mastery_apprentice_count / $examSet->questions->count() * 100 }}" max="100"></progress></div>
      </div>
    </x-card.mini>
  </x-card.main>



     

@endsection
