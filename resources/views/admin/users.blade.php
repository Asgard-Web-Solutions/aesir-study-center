@extends('layouts.app2')

@section('content')

    <x-card.main title="User Manager" size="lg">
        <x-card.mini >
            <x-table.main>
                <x-table.body>
                    @foreach ($users as $user)
                        <x-table.row>
                            <x-table.cell>{{ $user->name }}</x-table.cell>
                            <x-table.cell>{{ $user->email }}</x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-table.body>
            </x-table.main>
        </x-card.mini>
    </x-card.main>

@endsection
