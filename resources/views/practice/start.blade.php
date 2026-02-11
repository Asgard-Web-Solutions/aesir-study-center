@extends('layouts.app2', ['heading' => 'Practice - ' . $exam->name ])

@section('content')

    <x-card.main title="Start a Practice Session">
        <x-card.mini>
            <form action="{{ route('practice.begin', $exam) }}" method="post">
                @csrf

                @php
                    $options = [ 'all' => "All Questions", 'recentIncorrect' => 'Recently Incorrect Answers', 'flagged' => "Review Book", 'weak' => "Weak/Low Mastery", 'strong' => "Strong/High Mastery"];
                @endphp

                <div class="my-4 space-y-4">
                    @foreach ($options as $key => $option)
                        <div class="flex items-center">
                            <input type="radio" id="{{ $key }}" class="max-w-lg mx-2 text-primary-content radio radio-primary" name="filter" value="{{ $key }}" @if ($key == "recentIncorrect") checked @endif>
                            <label class="label" for="{{ $key }}">
                                <span class="text-primary">{{ $option }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <x-card.buttons submitLabel="Start Practice Session" />
            </form>
        </x-card.mini>
    </x-card.main>

@endsection
