@extends('layouts.app2')

@section('content')

<h1 class="text-2xl font-bold text-center text-primary">{{ $exam->name }}</h1>

    <x-card.main title='Enroll in Exam'>
            @feature('mage-upgarde')
                <x-card.mini>
                    <div class="badge"><span class="tooltip" data-tip="Study Credits"><i class="{{ config('icon.credit') }} text-{{ config('color.credit') }} text-lg"></i> <i class="{{ config('icon.study_credit') }} text-{{ config('color.study_credit') }} text-lg"></i> {{ $exam->user->credit->study }}</span></div>            
                </x-card.mini>
            @endfeature

        <x-card.mini>
            @feature('mage-upgrade')
                    <x-text.main>Registering this to your account will cost <span class="font-bold text-warning">1 Study Credit</span>. You have <span class="font-bold text-accent">{{ auth()->user()->credit->study }} Study Credit(s)</span> available.</x-text.main>
            @else
                <x-text.main>This exam is not registered to your Transcripts, yet. Do you want to add it?</x-text.main>
            @endfeature
    
            <x-text.main>Enrolling will make the exam show up in your Transcripts and in your Exams page so you can easily reference it.</x-text.main>

            <a href="{{ route('exam-session.enroll', $exam) }}" class="mx-auto btn btn-primary">Enroll in Exam</a>
        </x-card.mini>
    </x-card.main>

@endsection
