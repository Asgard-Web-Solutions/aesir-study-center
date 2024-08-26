@extends('layouts.app2', ['heading' => 'Exam - ' . $set->name ])

@section('content')

<h1 class="text-2xl font-bold text-center text-primary">{{ $set->name }}</h1>

    <x-card.main title='Configure Test'>
        <form action="{{ route('start-test', $set->id) }}" method="post">
            @csrf
            @php
                $questionCount = ($set->questions->count() < 10) ? $set->questions->count() : 10;
            @endphp
            <x-form.text name='number_questions' label='How Many Questions?' helptext='Question Pool: {{  $set->questions->count() }}' value='{{ $questionCount }}'></x-form.text>
            <x-card.buttons submitLabel='Begin Test' /> 
        </form>
    </x-card.main>

@endsection
