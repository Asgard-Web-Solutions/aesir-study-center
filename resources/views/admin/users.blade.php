@extends('layouts.app2')

@section('content')

    <x-card.main title="User Manager" size="lg">
        <x-card.mini >
            <x-table.main>
                <x-table.body>
                    @foreach ($users as $user)
                        <x-table.row>
                            <x-table.cell><a href="{{ route('profile.view', $user) }}" class="link link-primary">{{ $user->name }}</a></x-table.cell>
                            <x-table.cell>{{ $user->email }}</x-table.cell>
                            <x-table.cell>
                                <div class="dropdown">
                                    <div class="m-1 btn btn-secondary btn-sm btn-outline" tabindex="0" role="button">More Actions...</div>
                                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                                        <li><a href="{{ route('admin.user', $user) }}"><i class="{{ config('icon.latest_summary') }} text-lg"></i> Edit User</a></li>
                                        <li><a href="{{ route('profile.view', $user) }}"><i class="{{ config('icon.latest_summary') }} text-lg"></i> View Transcripts</a></li>
                                    </ul>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-table.body>
            </x-table.main>
        </x-card.mini>
    </x-card.main>

@endsection
