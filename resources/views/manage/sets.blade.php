@extends('layouts.app')

@section('content')
    <div class="items-center">
        
        <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">Select Exam to Modify</h1>
            </div>

            <div class="w-full my-2 p-2">
                <div class="w-full">
                    @foreach ($sets as $set)
                        <div class="w-full text-center p-2"><a href="{{ route('manage-questions', $set->id) }}" class="px-3 bg-gray-800 rounded-lg text-white">SELECT: {{ $set->name }}</a></div>
                    @endforeach
                </div>
            </div>
        </div>
            
        <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg my-5">
            <div class="w-full bg-gray-700 mt-6 mb-3 text-center">
                <h1 class="text-white text-2xl">Add New Exam</h1>
            </div>

            <div class="w-full my-2 text-center p-2">
                <form action="{{ route('save-exam') }}" method="post">
                    @csrf

                    <label class="p-2 font-bold">Exam Set Name</label><br />
                    <input type="text" name="name" class="w-full sm:w-9/12 md:w-10/12 p-2">
                    @error('name')
                        <p class="text-red-700 p-2 text-xs italic mt-4 text-center">
                            {{ $message }}
                        </p>
                    @enderror

                    <br /><br />
                    <label class="p-2 font-bold">Exam Set Description</label><br />
                    <input type="text" name="description" class="w-full sm:w-9/12 md:w-10/12 p-2">
                    @error('description')
                        <p class="text-red-700 p-2 text-xs italic mt-4 text-center">
                            {{ $message }}
                        </p>
                    @enderror


                    <div class="m-2 w-full text-center">
                        <input type="submit" value="Add Exam" class="px-3 mt-4 bg-gray-800 rounded-lg text-white">
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
