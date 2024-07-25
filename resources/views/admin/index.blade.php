@extends('layouts.app2')

@section('content')

    <x-card.main title="Admin Control Panel" size="grid">
        <x-card.mini title="Users">
            <a href="{{ route('admin.users') }}" class="btn btn-primary">View All Users</a>
        </x-card.mini>
    </x-card.main>

@endsection
