@extends('layouts.app')

@section('content')
    <div class=" items-center">
            
        <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">Your Test History</h1>
            </div>

            <div class="w-full my-2">
                <table class="w-full">
                    <tr>
                        <th>Exam Set</th>
                        <th>Avg Grade Last {{ config('test.count_tests_for_average_score') }}</th>
                        <th>Times Taken</th>
                        <th>Since Last Test</th>
                        <th>Familiar</th>
                        <th>Proficiency</th>
                        <th>Mastery</th>
                    </tr>
                    @foreach ($sets as $set)
                        <tr>
                            <td class="text-center p-2"><a href="{{ route('test-history', $set['id']) }}" class="underline hover:no-underline">{{ $set['name'] }}</a></td>
                            <td class="text-center p-2">{{ $set['average'] }}%</td>
                            <td class="text-center p-2">{{ $set['taken'] }}</td>
                            <td class="text-center p-2">{{ $set['last_time'] }}</td>
                            <td class="text-center p-2">{{ $set['familiar'] }}%</td>
                            <td class="text-center p-2">{{ $set['proficient'] }}%</td>
                            <td class="text-center p-2">{{ $set['mastery'] }}%</td>
                        </tr>
                    @endforeach
                </table>


            </div>
        </div>
        
        @if ($incomplete->count())
            <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg mt-5">
                <div class="w-full bg-gray-700 rounded-t-lg text-center">
                    <h1 class="text-white text-2xl">Incomplete Tests</h1>
                </div>

                <div class="w-full my-2">
                    <ul>
                        @foreach($incomplete as $test)
                            <li class="text-center p-2"><a href="{{ route('take-test', $test->id) }}" class="px-3 bg-gray-800 rounded-lg text-white">CONTINUE {{ $test->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="w-full sm:w-10/12 md:w-8/12 m-auto rounded-lg mt-5 text-right">
            <a href="{{ route('tests') }}" class="px-3 bg-gray-800 rounded-lg text-white">Take A Test</a>
        </div>
    </div>
@endsection
