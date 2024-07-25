@extends('layouts.app2')

@section('content')

    <x-card.main title="User Manager" size="lg">
        <x-card.mini >

            <form action="{{ route('admin.user-update', $user) }}" method="post">
                @csrf
                <x-form.text name="name" value="{{ $user->name }}" />
                <x-form.text name="email" type="email" value="{{ $user->email }}" />
                
                <x-card.buttons submitLabel="Update User" />
            </form>


            <x-table.main>
                <x-table.body>
                        <x-table.row>
                            {{-- <x-table.cell>{{ $user->name }}</x-table.cell>
                            <x-table.cell>{{ $user->email }}</x-table.cell>
                            <x-table.cell><a href="{{ route('admin.user', $user) }}" class="link link-secondary">Edit user</a></x-table.cell> --}}
                        </x-table.row>
                </x-table.body>
            </x-table.main>
        </x-card.mini>
    </x-card.main>

@endsection
