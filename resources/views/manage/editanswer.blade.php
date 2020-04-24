@extends('layouts.app')

@section('content')
    <div class="flex items-center">

        <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">Manage {{ $question->set->name }} Questions</h1>
            </div>

            <div class="w-full my-2">
                <div class="w-full">
                    <p class="p-2 m-2 text-strong text-lg leading-loose text-blue-900">{{ $question->text }}</p>
                </div>
            </div>

            <div class="w-full my-2">
                <form action="{{ route('update-answer', $answer->id) }}" method="post">
                    @csrf

                    <label class="p-2">Answer</label>
                    <input type="text" name="answer" class="w-full sm:w-7/12 md:w-8/12 p-2" value="{{ $answer->text }}">
                    <select name="correct" class="w-full sm:w-3/12 md:w-2/12 p-2">
                        <option value="0" @if ($answer->correct == 0) SELECTED @endif >WRONG</option>
                        <option value="1" @if ($answer->correct == 1) SELECTED @endif >CORRECT</option>
                    </select>

                    <a href="{{ route('delete-answer', $answer->id) }}"><i class="far fa-minus-square text-3xl text-red-700 hover:text-red-500 m-4"></i></a>

                    @error('answer')
                        <p class="text-red-700 p-2 text-xs italic mt-4 text-center">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('correct')
                        <p class="text-red-700 p-2 text-xs italic mt-4 text-center">
                            {{ $message }}
                        </p>
                    @enderror


                    <div class="m-2 w-full text-center">
                        <input type="submit" value="Update Answer" class="px-3 bg-gray-800 rounded-lg text-white cursor-pointer">
                        <a href="{{ route('manage-answers', $answer->question_id) }}" class="px-3 bg-gray-400 rounded-lg text-black">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
@endsection
