@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold text-center text-base-content">{{ __('Exam History') }}</h1>

    <div class="w-1/2 m-auto my-10 shadow-xl card bg-neutral text-neutral-content">
        <div class="w-full card-body">
            <div class="items-center w-full text-center">
                <h2 class="card-title text-accent" style="display: block">{{ $set->name }}</h2>
            </div>

            <div class="overflow-x-auto text-base-content">
                <table class="table w-full my-4 table-zebra table-compact">
                    <thead>
                        <tr>
                            <th>{{ __('Grade') }}</th>
                            <th>{{ __('Questions') }}</th>
                            <th>{{ __('Test Time') }}</th>
                            <th>{{ __('Completed') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tests as $test)
                            <tr>
                                @if ($test->getAttributes()['end_at'])
                                    <td>{{ $test->result }}%</td>
                                    <td>{{ $test->num_questions }}</td>
                                    <td>{{ $test->duration }} Minutes</td>
                                    <td>{{ $test->end_at }}</td>
                                @else
                                    <td>{{ __('Incomplete') }}</td>
                                    <td>{{ $test->num_questions }}</td>
                                    <td colspan="2"><a href="{{ route('take-test', $test->id) }}" class="btn btn-primary">{{ __('CONTINUE TEST') }}</a></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="justify-end w-1/2 mx-auto my-5 text-right card-action">
        <a href="{{ route('home') }}" class="btn btn-secondary">{{ __('Back to Profile') }}</a>
    </div>

@endsection