@extends('layouts.app2')

@section('content')
    <x-card.main title="ERROR - PERMISSION DENIED">
        <x-card.mini>
            <div class="md:flex">
                <div class="w-full md:w-1/4">
                    <img src="{{ asset('images/error403.webp') }}" alt="Barn Owl looking for something" class="">
                </div>
                <div class="w-full p-3 md:w-3/4">
                    <x-text.main>We are sorry, Acolyte {{ auth()->user()->name }}! But you do not have permission to view this page.</x-text.main>
                </div>
            </div>        
        </x-card.mini>
    </x-card.main>
@endsection