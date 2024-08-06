@extends('layouts.app2')

@section('content')

    <x-card.main title="Create An Exam">
        <x-card.mini>
            <form action="{{ route('exam.store') }}" method="post">
                @csrf

                <x-form.text label="Name" name="name" />
                <x-form.text label="Description" name="description" />

                @php
                    foreach ($visibility as $status)
                    {
                        $values[$status->value] = str_replace("is", "", $status->name);
                    }
                @endphp

                <x-card.buttons submitLabel="Create Exam" />
            </form>
        </x-card.mini>
    </x-card.main>

@endsection
