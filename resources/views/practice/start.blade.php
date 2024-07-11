@extends('layouts.app2')

@section('content')

    <x-card.main title="Start a Practice Session">
        <x-card.mini>
            <form  method="post">
                @csrf

                <x-card.buttons submitLabel="Start Practice Session" />
            </form>
        </x-card.mini>
    </x-card.main>

@endsection
