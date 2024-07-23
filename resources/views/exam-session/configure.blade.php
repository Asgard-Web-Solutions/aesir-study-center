@extends('layouts.app2')

@section('content')

<h1 class="text-2xl font-bold text-center text-primary">{{ $examSet->name }}</h1>

    <x-card.main title='Test Settings'>
        <form action="{{ route('exam-session.store', $examSet) }}" method="post">
            @csrf

            @php
                $questionCount = ($maxQuestions < 10) ? $maxQuestions : 10;
            @endphp
            <x-form.text name='question_count' label='How Many Questions?' helptext='Available Questions: {{ $maxQuestions }} / Question Pool: {{  $examSet->questions->count() }}' value='{{ $questionCount }}'></x-form.text>
            
            <x-card.buttons submitLabel='Begin Test' />
        </form>
    </x-card.main>

@endsection
