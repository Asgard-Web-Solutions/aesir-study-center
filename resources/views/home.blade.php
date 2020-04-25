@extends('layouts.app')

@section('content')
    <div class=" items-center">
            
        <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg hidden sm:block">
            <div class="w-full bg-gray-700 rounded-t-lg text-center">
                <h1 class="text-white text-2xl">Your Test History</h1>
            </div>

            <div class="w-full my-2">
                <table class="w-full">
                    <tr>
                        <th>Exam Set</th>
                        <th>Retake</th>
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
                            <td class="text-center p-2"><a href="{{ route('select-test', $set['id']) }}"><i class="far fa-redo text-blue-700 hover:text-blue-500"></i></a></td>
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

        <div class="w-full sm:hidden">
            @foreach ($sets as $set)
                <div class="w-full sm:w-10/12 md:w-8/12 m-auto bg-gray-200 rounded-lg my-5">
                    <div class="w-full bg-gray-700 rounded-t-lg text-center">
                        <h1 class="text-white text-2xl">{{ $set['name'] }}</h1>
                    </div>

                    <div class="w-full my-2 text-xl">

                        <div class="flex">
                            <div class="w-5/12">
                                <strong>Avg Grade Last {{ config('test.count_tests_for_average_score') }}</strong>
                            </div>
                            <div class="w-4/12">
                                {{ $set['average'] }}%
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-5/12">
                                <strong>Times Taken</strong>
                            </div>
                            <div class="w-4/12">
                                {{ $set['taken'] }}
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-5/12">
                                <strong>Since Last Test</strong>
                            </div>
                            <div class="w-4/12">
                                {{ $set['last_time'] }}
                            </div>
                        </div>
                        <br />

                        <div class="flex">
                            <div class="w-5/12">
                                <strong>Familiar</strong>
                            </div>
                            <div class="w-4/12">
                                {{ $set['familiar'] }}%
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-5/12">
                                <strong>Proficiency</strong>
                            </div>
                            <div class="w-4/12">
                                {{ $set['proficient'] }}%
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-5/12">
                                <strong>Mastery</strong>
                            </div>
                            <div class="w-4/12">
                                {{ $set['mastery'] }}%
                            </div>
                        </div>

                        <div class="text-right text-base p-4">
                            <a href="{{ route('select-test', $set['id']) }}" class="px-3 bg-gray-800 rounded-lg text-white"><i class="far fa-redo text-white hover:text-blue-300 mx-2"></i> Retake Exam</a>
                            <a href="{{ route('test-history', $set['id']) }}" class="px-3 bg-gray-800 rounded-lg text-white">Exam History</a>
                        </div>

                    </div>
                </div>
            @endforeach
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

        <div class="w-full sm:w-10/12 md:w-8/12 m-auto rounded-lg mt-5 text-center sm:text-right">
            <a href="{{ route('tests') }}" class="px-3 bg-gray-800 rounded-lg text-white text-lg sm:text-base">Take A Test</a>
        </div>
    </div>

@endsection
