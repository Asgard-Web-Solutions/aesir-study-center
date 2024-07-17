@extends('layouts.app2')

@section('content')

    <x-card.main title="Exam Summary: {{ $examSet->name }}">
        <x-card.mini>
            <div class="w-1/2 mx-auto shadow stats">
                <div class="stat">
                  <div class="stat-title text-accent">Grade</div>
                  <div class="stat-value text-primary">{{ $session->grade }}%</div>
                  <div class="stat-desc">You got {{ $session->correct_answers }} out of {{ $session->question_count }} questions correct.</div>
                </div>
              </div>
              
              {{-- // TODO: Make this look better --}}
            <x-text.main label="Correct">{{ $session->correct_answers }}</x-text.main>
            <x-text.main label="Incorrect">{{ $session->incorrect_answers }}</x-text.main>

            {{-- // TODO: Show mastery stats --}}
            {{-- // TODO: Button to take the test again // Button to return to dashboard --}}
        </x-card.mini>
    </x-card.main>            

@endsection
