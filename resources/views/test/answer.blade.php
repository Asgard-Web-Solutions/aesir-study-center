@extends('layouts.app')

@section('content')
    <div class="flex items-center">
        
        <div class="w-full sm:w-11/12 md:w-9/12 lg:w-8/12 m-auto bg-gray-200 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">{{ $question->set->name }}</h1>
            </div>

            <div class="w-full my-2">
                <div class="w-full">
                    <p class="p-2 m-2 text-strong text-lg leading-loose text-blue-900">{{ $question->text }}</p>
                        <table class="w-full mt-5">
                            <tr>
                                <th>Selected</th>
                                <th>Text</th>
                            </tr>
                            @foreach ($answers as $answer)
                                <tr>
                                    <td class="p-2 m-2 mb-4 w-2">
                                        @if ($normalizedAnswer[$answer['id']])
                                            X
                                        @endif                                 
                                    </td>
                                    <td class="p-2 m-2 mb-4 w-full">
                                        @if ($answer['correct'])
                                            <span class="text-green-600 font-bold">{{ $answer['text'] }}</span>
                                        @else 
                                            <span class="text-gray-500 line-through">{{ $answer['text'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="w-full text-center p-4">
                            <a href="{{ route('take-test', $test->id) }}" class="px-3 bg-gray-800 rounded-lg text-white">Next Question</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
@endsection
