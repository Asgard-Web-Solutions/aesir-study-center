@extends('layouts.app2')

@section('content')
    <x-card.main title="ERROR - PAGE NOT FOUND">
        <x-card.mini>
            <div class="md:flex">
                <div class="w-full md:w-1/4">
                    <img src="{{ asset('images/error404.webp') }}" alt="Barn Owl blocked from entering a gate" class="">
                </div>
                <div class="w-full p-3 md:w-3/4">
                    <x-text.main>The URL you were looking for was not found. Our owls are searching frantically for the page, but it just isn't here. Please try another!</x-text.main>
                </div>
            </div>        
        </x-card.mini>
    </x-card.main>
@endsection