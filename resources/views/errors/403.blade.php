@extends('layouts.app2')

@section('content')
    <x-card.main title="ERROR - PERMISSION DENIED">
        <x-card.mini>
            <div class="md:flex">
                <div class="w-full md:w-1/4">
                    <img src="{{ asset('images/error403.webp') }}" alt="Barn Owl looking for something" class="">
                </div>
                <div class="w-full p-3 md:w-3/4">
                    <x-text.main>You tried to access a page that you do not have the permssion to access.</x-text.main>
                    <x-text.main>Are you trying to hack us? Please don't...</x-text.main>
                </div>
            </div>        
        </x-card.mini>
    </x-card.main>
@endsection