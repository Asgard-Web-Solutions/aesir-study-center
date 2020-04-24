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

                    <table class="w-full m-2">
                        @foreach ($question->answers as $answer)
                            <tr>
                                <td class="p-2">
                                    <a href="{{ route('edit-answer', $answer->id) }}"><i class="far fa-edit text-blue-700 hover:text-blue-500"></i></a>
                                </td>
                                <td class="p-2">
                                    @if ($answer->correct) <i class="far fa-check-circle text-green-400"></i> @else &nbsp; @endif
                                </td>
                                <td class="p-2 mb-4 w-full">
                                    {{ $answer->text }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="w-full my-2">
                <form action="{{ route('save-answers', $question->id) }}" method="post">
                    @csrf

                    <label class="p-2">Answer</label>
                    <input type="text" name="answer" class="w-full sm:w-7/12 md:w-8/12 p-2">
                    <select name="correct" class="w-full sm:w-3/12 md:w-2/12 p-2">
                        <option value="0">WRONG</option>
                        <option value="1">CORRECT</option>
                    </select>
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
                        <input type="submit" value="Add Answer" class="px-3 bg-gray-800 rounded-lg text-white">
                        <a href="{{ route('add-question', $question->set->id) }}" class="px-3 bg-gray-400 rounded-lg text-black">Add New Question</a>
                        <a href="{{ route('manage-questions', $question->set->id) }}" class="px-3 bg-gray-400 rounded-lg text-black">Back to Questions</a>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
@endsection
