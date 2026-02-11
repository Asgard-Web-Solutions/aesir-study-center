@extends('layouts.app2', ['heading' => 'Exam - ' . $examSet->name ])

@section('content')

  <x-card.main title="{!! $examSet->name !!} - Latest Session">
    @if ($session)
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
      </x-card.mini>

      <x-card.mini title="Mastery Change">
        @php
          $remaining['apprentice'] = $examSet->questions->count() - $examRecord->mastery_apprentice_count;
          $remaining['familiar'] = $examSet->questions->count() - $examRecord->mastery_familiar_count;
          $remaining['proficient'] = $examSet->questions->count() - $examRecord->mastery_proficient_count;
          $remaining['mastered'] = $examSet->questions->count() - $examRecord->mastery_mastered_count;
        @endphp
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
            <div class="stat-desc">@if ($remaining['apprentice']) {{ $remaining['apprentice'] }} Remaining @else Completed @endif</div>
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
            <div class="stat-desc">@if ($remaining['familiar']) {{ $remaining['familiar'] }} Remaining @else Completed @endif</div>
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
            <div class="stat-desc">@if ($remaining['proficient']) {{ $remaining['proficient'] }} Remaining @else Completed @endif</div>
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
            <div class="stat-desc">@if ($remaining['mastered']) {{ $remaining['mastered'] }} Remaining @else Completed @endif</div>
          </div>

        </div>
        <x-help.box>
          <x-help.text><x-help.highlight>Mastery Change</x-help.highlight> shows you how much progress was made toward mastering this subject during your latest Exam Session.</x-help.text>
          <x-help.text>As you get questions correct they gain experience. Once they have enough experience that question gains a new mastery level.</x-help.text>
          <x-help.text>Once <x-help.highlight color="secondary">every question reaches a certain level</x-help.highlight> then you will be awarded a <x-help.highlight color="warning">Mastery Badge</x-help.highlight> for that exam.</x-help.text>
          <x-help.text>This <x-help.highlight color="normal">Mastery Change</x-help.highlight> section shows you how many questions achieved a new rank during your Exam Session.</x-help.text>
          <x-help.text>Beware, questions can <x-help.highlight color="secondary">lose mastery if you get them wrong</x-help.highlight>.</x-help.text>
        </x-help.box>
      </x-card.mini>
    @else
      <x-card.mini>
        <x-text.main>You have not taken this exam yet.</x-text.main>
      </x-card.mini>
    @endif
  </x-card.main>

  <x-card.main>
    <div class="block object-center w-full text-center lg:flex">
      <div class="w-full text-center lg:w-1/3 md:text-left">
        <div class="dropdown">
          <div class="m-1 btn btn-secondary btn-outline" tabindex="0" role="button">More Actions...</div>
          <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
              @can('update', $examSet)
                <li><a href="{{ route('exam.edit', $examSet) }}"><i class="{{ config('icon.edit_exam') }} text-lg"></i> Edit Exam</a></li>
              @endcan
            <li><a href="{{ route('practice.start', $examSet) }}"><i class="{{ config('icon.take_exam') }} text-lg"></i> Practice Flash Cards</a></li>
            <li><a href="{{ route('exam-session.start', $examSet) }}"><i class="{{ config('icon.practice_exam') }} text-lg"></i> Take Exam</a></li>
          </ul>
        </div>
      </div>

      <div class="w-full text-center md:text-left md:w-1/3">
        <a href="{{ route('profile.exams') }}" class="mx-2 my-2 btn btn-secondary btn-outline"><i class="{{ config('icon.manage_exams') }} text-lg"></i> Your Exam Library</a>
      </div>

      <div class="w-full text-center lg:w-1/3 md:text-right">
        <a href="{{ route('exam-session.start', $examSet->id) }}" class="mx-2 my-2 btn btn-primary"><i class="{{ config('icon.take_exam') }} text-lg"></i> Retake Exam</a>
      </div>
    </div>
  </x-card.main>

  @if ($examRecord)
    <x-card.main title="{!! $examSet->name !!} - Overall Summary">
      <x-card.mini>
        <div class="w-full shadow stats stats-vertical md:stats-horizontal">
          <div class="stat">
            <div class="stat-title">Highest Mastery</div>
            <div class="w-full my-2 text-center stat-value text-{{ config('color.' . strtolower($mastery)) }}"><i class="text-5xl {{ config('icon.' . strtolower($mastery)) }}"></i></div>
            <div class="stat-desc">{{ $mastery }}</div>
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

        <x-help.box>
          <x-help.text><x-help.highlight>Highest Mastery</x-help.highlight> shows you the badge for the highest mastery level that you have achieved for <x-help.highlight color="secondary">this exam</x-help.highlight>.</x-help.text>
          <x-help.text>Once <x-help.highlight color="info">100%</x-help.highlight> of the Questions in an exam have achieved a certain Mastery Level, then you will unlock that Mastery Badge.</x-help.text>
        </x-help.box>
      </x-card.mini>
      <x-card.mini title="Your Mastery Progress">
        @if ($session)
          <div class="shadow">
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
          </div>
        @else
          <x-text.main>You have not taken this exam, yet.</x-text.main>
        @endif

        <x-help.box>
          <x-help.text><x-help.highlight>Mastery Progress</x-help.highlight> shows how many questions in this exam you have leveled up to each mastery level.</x-help.text>
          <x-help.text>To gain levels <x-help.highlight color="secondary">keep taking the exam and getting every question correct</x-help.highlight>!</x-help.text>
          <x-help.text>My own goal is to get every exam to 100% Mastered! It's a big goal, I know, but I like learning things and taking lots of Exams.</x-help.text>
          <x-help.text>Other acolytes at Acolyte Academy will be able to see your exam badges in your <x-help.highlight color="info">Acolyte Transcript</x-help.highlight> page.</x-help.text>
        </x-help.box>
      </x-card.mini>
    </x-card.main>
  @endif




@endsection
