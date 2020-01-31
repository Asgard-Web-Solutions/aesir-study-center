@extends('layouts.app')

@section('content')
    <div class="flex items-center">
            
        <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">Your Test History</h1>
            </div>

            <div class="w-full my-2">
                <table class="w-full">
                    <tr>
                        <th>Exam Set</th>
                        <th>Grade</th>
                        <th>Exam Questions</th>
                        <th>Ended</th>
                    </tr>
                    @foreach ($tests as $test)
                        <tr>
                            <td class="text-center p-2">{{ $test->set->name }}</td>
                            @if ($test->getOriginal('end_at'))
                                <td class="text-center p-2">{{ $test->result }}%</td>
                                <td class="text-center p-2">{{ $test->num_questions }}</td>
                                <td class="text-center p-2">{{ $test->end_at }}</td>
                            @else
                                <td colspan="3" class="text-center p-2"><a href="{{ route('take-test', $test->id) }}" class="px-3 bg-gray-800 rounded-lg text-white">CONTINUE</a></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        
    </div>
@endsection
