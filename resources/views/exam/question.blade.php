@extends('layouts.app2')

@section('content')
<x-page.title>{!! $exam->name !!}</x-page.title>

<x-card.main title="{!! $question->text !!}">
    <form action="{{ route('exam.questionUpdate', ['exam' => $exam, 'question' => $question]) }}" method="post">
        @csrf
        
        <x-card.mini title="Quesiton">
            <x-form.text name="question" value="{{ old('question', $question->text) }}" />
        </x-card.mini>

        <x-card.mini title="Answers">
            @foreach ($question->answers as $answer)
                <div class="block lg:flex">
                    <div class="w-full px-4 lg:w-1/4">
                        @if ($answer->correct)
                            <x-form.checkbox name="correct[{{ $answer->id }}]" label="Answer is correct?" checked="yes" />
                        @else
                            <x-form.checkbox name="correct[{{ $answer->id }}]" label="Answer is correct?"  />
                        @endif
                    </div>

                    <div class="w-full lg:w-3/4">
                        <x-form.text name="answers[{{ $answer->id }}]" value="{{ old('answers[' . $answer->id . ']', $answer->text) }}" />
                    </div>
                </div>
            @endforeach

            <div class="w-full my-2 text-right">
                <input type="submit" value="Update Question & Answers" class="btn btn-primary">
            </div>
        </x-card.mini>
    </form>

    <x-card.mini title="Add Answer">
        <form action="{{ route('exam.addAnswer', ['exam' => $exam, 'question' => $question]) }}" method="post">
            @csrf
            
            <div class="block lg:flex">
                <div class="w-full px-4 lg:w-1/4">
                    <x-form.checkbox name="correct" label="Answer is correct?" />
                </div>

                <div class="w-full lg:w-3/4">
                    <x-form.text name="answer" value="{{ old('answer') }}" />
                </div>
            </div>

            <div class="w-full my-2 text-right">
                <input type="submit" value="Add New Answer" class="btn btn-primary">
            </div>

        </form>
    </x-card.mini>
</x-card.main>


    <x-card.main>
        <div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
            <a href="{{ route('exam.edit', $exam) }}" class="btn btn-secondary">{{ __('Back to Exam Editor') }}</a>
        </div>
    </x-card.main>
@endsection
