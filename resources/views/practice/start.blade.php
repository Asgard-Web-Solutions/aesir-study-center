@extends('layouts.app2', ['heading' => 'Practice - ' . $exam->name ])

@section('content')

    <x-card.main title="Start a Practice Session">
        <x-card.mini>
            <form action="{{ route('practice.begin', $exam) }}" method="post">
                @csrf

                @php
                    $options = [ 'all' => "All Questions", 'recentIncorrect' => 'Recently Incorrect Answers', 'flagged' => "Review Book", 'weak' => "Weak/Low Mastery", 'strong' => "Strong/High Mastery"];
                @endphp
                <x-form.dropdown :values=$options label="Select Review Set" name="filter" selected='recentIncorrect' />

                <x-card.buttons submitLabel="Start Practice Session" />
            </form>
        </x-card.mini>
    </x-card.main>

@endsection
