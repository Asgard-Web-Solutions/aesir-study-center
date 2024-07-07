@extends('layouts.app2')

@section('content')
    <x-card.main title="Exam: {{ $set->name }}">

        <x-card.mini title="Create Question Group">
            <form action="{{ route('group-store', $set->id) }}" method="post">
                @csrf

                <x-form.text name="name" label="Name" />

                <x-card.buttons submitLabel="Add Group" />
            </form>
        </x-card.mini>
    </x-card.main>

    <x-card.buttons secondaryLabel='Back' secondaryAction="{{ route('manage-questions', $set->id) }}" />
@endsection
