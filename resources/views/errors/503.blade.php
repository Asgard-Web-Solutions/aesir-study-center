@extends('layouts.app2')

@section('content')
    <x-card.main title="ACADEMY MAINTENANCE">
        <x-card.mini>
            <div class="md:flex">
                <div class="w-full md:w-1/4">
                    <img src="{{ asset('images/error503.webp') }}" alt="Barn Owl helps build a mage tower" class="">
                </div>
                <div class="w-full p-3 md:w-3/4">
                    <x-text.main>Good news! We're working on making some really awesome changes to Acolyte Academy!</x-text.main>
                    <x-text.main>Bad News... That means the website is down for just a few minutes.</x-text.main>
                    <x-text.main>If you are curious what is being changed check out the <a href="{{ config('academy.suggestion_url') }}" class="link link-primary">Release Notes</a>! These get updated within a few minutes of new changes rolling out.</x-text.main>
                    <x-text.main>Just sit back and enjoy a short coffee break before you get back into making your tests! Or, you know, just refresh the page, because by the time you read all of this the updates are likely complete!</x-text.main>
                </div>
            </div>
        </x-card.mini>
    </x-card.main>
@endsection
