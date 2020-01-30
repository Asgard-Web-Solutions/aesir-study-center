@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        
        <div class="w-full bg-gray-100 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">Select Test</h1>
            </div>

            <div class="w-full my-2">
                <div class="w-full">
                    @foreach ($sets as $set)
                        <div class="w-full text-center p-2"><h2 class="text-xl">{{ $set->name }}</h2></div>
                        <div class="w-full p-2">{{ $set->description }}</div>
                        <div class="w-full text-right p-2"><a href="{{ route('select-test', $set->id) }}" class="px-3 bg-gray-800 rounded-lg text-white">SELECT TEST</a></div>
                    @endforeach
                </div>
            </div>
        </div>
        
    </div>
@endsection
