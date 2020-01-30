@extends('layouts.app')

@section('content')
<div class="flex items-center">
        
    <div class="w-full bg-gray-100 rounded-lg">
        <div class="w-full bg-gray-700 rounded-t-lg text-center">
            <h1 class="text-white text-2xl p-2">{{ $set->name }}</h1>
        </div>

        <div class="w-full my-2">
            <div class="w-full">

                <form action="{{ route('start-test', $set->id) }}" method="post">
                    @csrf
                    <div class="w-full p-2"><strong class="text-gray-800">Question Pool:</strong> {{ $set->questions->count() }}</div>
                    <div class="flex">
                        <div class="w-1/2 p-2"><strong class="text-gray-800">Test Questions?</strong></div>
                        <div class="w-1/2 p-2"><input class="text-gray-800 w-1/3 font-bold" type="text" name="number_questions" value="10"></div>
                    </div>
                    @error('number_questions')
                        <p class="text-red-700 p-2 text-xs italic mt-4 text-center">
                            {{ $message }}
                        </p>
                    @enderror

                    <div class="w-full text-center p-6"><input type="submit" class="px-3 bg-gray-800 rounded-lg text-white" value="START TEST"></div>
                </form>
            </div>
        </div>
    </div>
    
</div>
@endsection
