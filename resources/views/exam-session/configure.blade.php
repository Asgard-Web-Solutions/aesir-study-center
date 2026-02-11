@extends('layouts.app2', ['heading' => 'Exam - ' . $examSet->name ])

@section('content')

<h1 class="text-2xl font-bold text-center text-primary">{{ $examSet->name }}</h1>

@livewire('exam-configuration', ['examSet' => $examSet, 'maxQuestions' => $maxQuestions])

@endsection
