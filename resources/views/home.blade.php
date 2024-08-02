@extends('layouts.app2')

@section('content')

<div class="w-full p-2 m-2 text-center"><h1 class="mx-auto text-3xl text-primary">Welcome to {{ config('app.name') }}</h1></div>

<x-card.main title="Learn Smarter, Not Harder" size='full'>
    <x-card.mini>
        <div class="md:flex">
            <div class="w-full md:w-1/4">
                <img src="{{ asset('images/CuriousBarnOwl.webp') }}" alt="Curious Barn Owl in a School" class="">
            </div>
            <div class="w-full p-3 md:w-3/4">
                <x-text.main>Are you tired of traditional study methods that don't work for you?</x-text.main>
                <x-text.main>Do you want to master new topics efficiently and effectively?</x-text.main>
                <x-text.main>Welcome to <span class="text-accent">{{ config('app.name') }}</span>, the ultimate online platform designed to revolutionize the way you learn and retain information.</x-text.main>
            </div>
        </div>
    </x-card.mini>
</x-card.main>

<x-card.main title="Why Choose {{ config('app.name') }}?" size="full">
    <img src="{{ asset('images/BarnOwlOutside.webp') }}" alt="Barn Owl enjoying itself outside in a forest at the base of a majestic mountain." class="mx-auto" width="512px" />
    
    <x-card.mini title="Personalized Learning Experience">
        <x-text.main>Our platform allows you to create customized exams based on your unique study needs.</x-text.main>
        <x-text.main>Whether you're preparing for a certification, learning a new language, or mastering a complex subject, <span class="text-accent">{{ config('app.name') }}</span> adapts to your learning pace and style.</x-text.main>
    </x-card.mini>

    <x-card.mini title="Adaptive Learning Algorithm">
        <x-text.main>Using advanced interval memory recall techniques, <span class="text-accent">{{ config('app.name') }}</span> ensures that you focus on the areas where you need the most improvement.</x-text.main>
        <x-text.main>Our algorithm tracks your progress and adjusts the frequency of questions, helping you reinforce knowledge more effectively.</x-text.main>
    </x-card.mini>


    <x-card.mini title="Interactive and Engaging">
        <x-text.main>Say goodbye to boring study sessions!</x-text.main>
        <x-text.main><span class="text-accent">{{ config('app.name') }}</span> makes learning interactive and fun. Shuffle through questions, challenge yourself with tests, and monitor your progress with detailed reports. Learning has never been this engaging.</x-text.main>
        <x-text.main>Review areas you are struggling with using flash-card style practices.</x-text.main>
    </x-card.mini>

    <x-card.mini title="Comprehensive Tracking">
        <x-text.main>Keep track of your mastery level with our detailed analytics.</x-text.main>
        <x-text.main>Monitor your correct and incorrect answers, see your progress over time, and understand your strengths and weaknesses.</x-text.main>
        <x-text.main>Our platform provides all the insights you need to stay on top of your learning journey.</x-text.main>
    </x-card.mini>
</x-card.main>

<x-card.main title="Key Features">
    <x-card.mini>
        <ul class="mx-3 list-disc list-outside">
            <li class="my-2"><span class="font-bold text-secondary">Create Custom Exams</span> Add your own questions and answers to build personalized tests.</li>
            <li class="my-2"><span class="font-bold text-secondary">Adaptive Learning</span> Focuses on questions you struggle with the most.</li>
            <li class="my-2"><span class="font-bold text-secondary">Detailed Analytics</span> Track your performance and mastery levels.</li>
            <li class="my-2"><span class="font-bold text-secondary">Interactive Sessions</span> Engage with shuffled questions and self-paced tests.</li>
            <li class="my-2"><span class="font-bold text-secondary">Flexible Learning</span> Study at your own pace and convenience.</li>
        </ul>
    </x-card.mini>
</x-card.main>

<x-card.main title="Join the Community" size="full">
    <x-card.mini>
        <div class="block w-full md:flex">
            <div class="w-full md:w-1/4">
                <img src="{{ asset('images/BarnOwlFamily.webp') }}" alt="Barn Owl enjoying itself outside in a forest at the base of a majestic mountain." class="mx-auto" width="512px" />
            </div>
            <div class="w-full px-3 md:w-3/4">
                <x-text.main>Join our growing community of learners who are transforming their study habits with <span class="text-secondary">{{ config('app.name') }}.</span></x-text.main>
                <x-text.main>Whether you're a student, professional, or lifelong learner, our platform provides the tools you need to succeed.</x-text.main>
                <x-text.main>Start your journey to better learning today! <a href="https://community.jonzenor.com" class="link link-accent hover:no-underline">Join the Forum Community</a></x-text.main>
            </div>
        </div>
    </x-card.mini>
</x-card.main>

<x-card.main title="Get Started Now!" size='full'>
    Sign up for free and experience the future of learning with <span class="text-secondary">{{ config('app.name') }}.</span> Empower yourself with the knowledge and skills you need to achieve your goals.

    <div class="w-full text-center">
        <a href="{{ route('register') }}" class="mx-auto my-3 btn btn-primary">Get Started</a>
    </div>
</x-card.main>

@endsection
