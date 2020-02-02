@extends('layouts.app')

@section('content')
    <div class="items-center">

        @foreach ($sets as $set)
            <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg my-5">
                <div class="w-full bg-gray-700 rounded-t-lg text-center">
                    <h1 class="text-white text-2xl">{{ $set->name }}</h1>
                </div>

                <div class="w-full my-2">
                    <div class="w-full p-3">
                        <div>
                            <strong>Question Pool:</strong> {{ $set->questions->count() }}
                        </div>

                        <p>{{ $set->description }}</p>

                        <div class="w-full text-right p-2"><a href="{{ route('select-test', $set->id) }}" class="px-3 bg-gray-800 rounded-lg text-white">SELECT TEST</a></div>

                    </div>
                </div>
            </div>
        @endforeach
        
    </div>
@endsection
