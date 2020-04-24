@extends('layouts.app')

@section('content')
    <div class="flex items-center">

        <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">Manage {{ $question->set->name }} Questions</h1>
            </div>

            <div class="w-full my-2">
                <div class="w-full">
                    <p class="p-2 m-2 text-strong text-lg leading-loose text-blue-900"><strong>Question:</strong> {{ $question->text }}</p>
                </div>
            </div>

            <div class="w-full my-2">
                <div class="w-full">
                    <p class="p-2 m-2 text-strong text-lg leading-loose text-blue-900"><strong>Current Answer:</strong> {{ $answer->text }}</p>
                </div>
            </div>

            <form action="{{ route('delete-answer-confirm', $answer->id) }}" method="post">
                @csrf

                <input type="hidden" name="confirm" value="true" />

                <div class="m-6 w-full text-center">
                    <input type="submit" value="Delete Answer" class="px-3 bg-gray-800 rounded-lg text-white cursor-pointer">
                    <a href="{{ route('edit-answer', $answer->id) }}" class="px-3 bg-gray-400 rounded-lg text-black">Cancel</a>
                </div>
            </form>

         </div>
        
    </div>
@endsection
