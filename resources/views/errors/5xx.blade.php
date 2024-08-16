@extends('layouts.app2')

@section('content')
    <x-card.main title="ERROR">
        <x-card.mini>
            <div class="md:flex">
                <div class="w-full md:w-1/4">
                    <img src="{{ asset('images/error5xx.webp') }}" alt="Barn Owl ducking on the ground while something burns behind him" class="">
                </div>
                <div class="w-full p-3 md:w-3/4">
                    <x-text.main>Something went wrong...</x-text.main>
                </div>
            </div>        
        </x-card.mini>
    </x-card.main>
@endsection