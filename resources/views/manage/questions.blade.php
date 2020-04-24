@extends('layouts.app')

@section('content')
    <div class="flex items-center">

        <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">Select Question to Modify</h1>
            </div>


            <div class="w-full my-2">
                <div class="w-full text-center m-2 p-3">
                    <a href="{{ route('add-question', $set->id) }}" class="px-3 bg-gray-800 rounded-lg text-white">Add Question</a>
                </div>
                <table>
                    @foreach ($set->questions as $question)
                        <tr>
                            <td class="p-1"><a href="{{ route('manage-answers', $question->id) }}"><i class="far fa-list text-blue-700 hover:text-blue-500"></i></a></td>
                            <td class="p-3">{{ $question->text }}</td>
                            <td class="p-3">{{ $question->answers->count() }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        
    </div>
@endsection
