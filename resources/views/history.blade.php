@extends('layouts.app')

@section('content')
    <div class="flex items-center">
            
        <div class="w-full sm:w-8/12 md:w-6/12 lg:w-5/12 xl:w-4/12 m-auto bg-gray-200 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">{{ $set->name }} History</h1>
            </div>

            <div class="w-full my-2">
                <table class="w-full">
                    <tr>
                        <th>Grade</th>
                        <th>Exam Questions</th>
                        <th>Test Time</th>
                        <th>Ended</th>
                    </tr>
                    @foreach ($tests as $test)
                        <tr>
                            @if ($test->getOriginal('end_at'))
                                <td class="text-center p-2">{{ $test->result }}%</td>
                                <td class="text-center p-2">{{ $test->num_questions }}</td>
                                <td class="text-center p-2">{{ $test->duration }} Minutes</td>
                                <td class="text-center p-2">{{ $test->end_at }}</td>
                            @else
                                <td colspan="4" class="text-center p-2"><a href="{{ route('take-test', $test->id) }}" class="px-3 bg-gray-800 rounded-lg text-white">CONTINUE</a></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
                <br /><br />
                <div class="w-full text-right">
                    <a href="{{ route('home') }}" class="m-5 px-3 bg-gray-400 rounded-lg text-black">Back</a>
                </div>
            </div>
        </div>
        
    </div>
@endsection