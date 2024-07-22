@extends('layouts.app2')

@section('content')

    <x-card.main title="Configure your practice settings">
        <x-card.mini>
            <form action="{{ route('practice.begin', $exam) }}" method="post">
                @csrf

                <x-form.text label="Name" name="name" />
                <x-form.text label="Description" name="description" />

                <x-card.buttons submitLabel="Update your configuation" />
            </form>
        </x-card.mini>
    </x-card.main>

@endsection
