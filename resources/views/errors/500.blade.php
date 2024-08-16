@extends('layouts.app2')

@section('content')
    <x-card.main title="ERROR - PAGE NOT FOUND">
        <x-card.mini>
            <div class="md:flex">
                <div class="w-full md:w-1/4">
                    <img src="{{ asset('images/error500.webp') }}" alt="Barn Owl flying over a burning forest" class="">
                </div>
                <div class="w-full p-3 md:w-3/4">
                    <x-text.main>There was a server error! Don't worry, our developer owls have been alerted and will be hard at work fixing the problem.</x-text.main>
                </div>
            </div>        
        </x-card.mini>
    </x-card.main>
@endsection