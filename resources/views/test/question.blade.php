@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        
        <div class="w-full bg-gray-100 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">{{ $question->set->name }}</h1>
            </div>

            <div class="w-full my-2">
                <div class="w-full">
                    <p class="p-2 text-strong">{{ $question->text }}</p>
                    @foreach ($answers as $answer)
                        <div class="w-full text-center p-2">{{ $answer->text }}</div>
                    @endforeach
                </div>
            </div>
        </div>
        
    </div>
@endsection
