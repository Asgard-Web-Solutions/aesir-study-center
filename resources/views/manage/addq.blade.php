@extends('layouts.app')

@section('content')
    <div class="flex items-center">

        <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">Add Question to {{ $set->name }}</h1>
            </div>


            <div class="w-full my-2">
                <form action="{{ route('save-question', $set->id) }}" method="post">
                    @csrf

                    <label class="p-2">Question</label>
                    <input type="text" name="question" class="w-full sm:w-9/12 md:w-10/12 p-2">
                    @error('question')
                        <p class="text-red-700 p-2 text-xs italic mt-4 text-center">
                            {{ $message }}
                        </p>
                    @enderror

                    <div class="m-2 w-full text-center">
                        <input type="submit" value="Add Question" class="px-3 bg-gray-800 rounded-lg text-white"> <a href="{{ route('manage-questions', $set->id) }}" class="px-3 bg-gray-400 rounded-lg text-black">Back to Question List</a>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
@endsection
