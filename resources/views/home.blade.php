@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold text-center text-base-content">{{ __('Your Profile') }}</h1>

<div class="hidden w-full m-auto my-10 shadow-xl xl:w-2/3 sm:block card bg-neutral text-neutral-content">
    <div class="w-full card-body">
        <div class="items-center w-full text-center">
            <h2 class="card-title text-accent" style="display: block">{{ __('Your Test History') }}</h2>
        </div>
    
        <div class="overflow-x-auto text-base-content">
            <table class="table w-full my-4 table-zebra table-compact">
                <thead>
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
                </thead>
                <tbody>
                    @foreach ($sets as $set)
                        <tr>
                            <td><a href="{{ route('test-history', $set['id']) }}" class="link link-primary">{{ $set['name'] }}</a></td>
                            <td><a href="{{ route('select-test', $set['id']) }}" class="link link-primary"><i class="far fa-redo" title="Retake Test"></i></a></td>
                            <td>{{ $set['average'] }}%</td>
                            <td>{{ $set['taken'] }}</td>
                            <td>{{ $set['last_time'] }}</td>
                            <td>{{ $set['familiar'] }}%</td>
                            <td>{{ $set['proficient'] }}%</td>
                            <td>{{ $set['mastery'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach ($sets as $set)
<div class="w-full my-5 shadow-xl sm:hidden card bg-neutral text-neutral-content">
    <div class="w-full card-body">
        <div class="items-center w-full text-center">
            <h2 class="card-title text-accent" style="display: block">{{ $set['name'] }}</h2>
        </div>

        <div class="w-full my-2 text-lg">

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

        </div>

        <div class="justify-end w-full my-5 text-right card-action">
            <a href="{{ route('select-test', $set['id']) }}" class="btn btn-primary"><i class="mx-2 far fa-redo"></i>{{ __('Retake Exam') }}</a>
            <a href="{{ route('test-history', $set['id']) }}" class="btn btn-secondary">{{ __('Exam History') }}</a>
        </div>
        
    </div>
</div>
@endforeach


@if ($incomplete->count())
    <h1 class="text-2xl font-bold text-center text-base-content">{{ __('Incomplete Exams') }}</h1>


    @foreach($incomplete as $test)
        <div class="w-1/2 m-auto my-10 shadow-xl card bg-neutral text-neutral-content">
            <div class="w-full card-body">
                <div class="items-center w-full text-center">
                    <h2 class="card-title text-accent" style="display: block">{{ $test->set->name }}</h2>
                </div>

                <p>{{ $test->set->description }}</p>


                <div class="justify-end w-full my-5 text-right card-action">
                    <a href="{{ route('take-test', $test->id) }}" class="btn btn-primary"><i class="mx-2 far fa-redo"></i> {{ __('Continue Exam') }}</a>
                </div>

            </div>
        </div>
    @endforeach

@endif


<div class="justify-end w-1/2 mx-auto my-5 text-right card-action">
    <a href="{{ route('tests') }}" class="btn btn-primary"><i class="mx-2 far fa-redo"></i> {{ __('Take an Exam') }}</a>
</div>

@endsection
