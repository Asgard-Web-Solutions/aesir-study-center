@extends('layouts.app2')

@section('content')

    <x-card.main title="{{ $examSet->name }} - Summary">

    </x-card.main>
    <x-card.main title="Latest Test Info">
        <x-card.mini>
            <div class="w-1/2 mx-auto shadow stats">
                <div class="stat">
                  <div class="stat-title text-accent">Grade</div>
                  <div class="stat-value text-primary">{{ $session->grade }}%</div>
                  <div class="stat-desc">Completed {{ $session->date_completed }}</div>
                </div>
              </div>
        </x-card.mini>

        <x-card.mini title="Answer Statistics">
              <div class="shadow stats">
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
            {{-- // TODO: Button to take the test again // Button to return to dashboard --}}
        </x-card.mini>
    </x-card.main>            

@endsection
