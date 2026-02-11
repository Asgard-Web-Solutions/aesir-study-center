@extends('layouts.app2', ['heading' => 'Pricing'])

@section('content')
    <x-card.main title='Purchase Credits' size='grid' cols='3'>
        @foreach ($products as $product)
            <x-card.mini>
                <h2 class="my-2 text-2xl text-center text-blue-600 bg-gray-200 rounded-xl">{{ $product->name }}</h2>

                <div class="w-full h-32 p-2 mt-2 mb-4 rounded-lg bg-base-100">
                    <x-text.dim>{{ $product->description }} &nbsp;</x-text.dim>
                </div>

                <div class="shadow stats stats-vertical">
                    <div class="stat">
                        <div class="stat-figure text-{{ config('color.architect_credit') }}"><i class="{{ config('icon.architect_credit') }} text-2xl"></i> </div>
                        <div class="stat-title">Author Credits</div>
                        <div class="stat-value text-{{ config('color.architect_credit') }}">{{ $product->architect_credits }}</div>
                        <div class="stat-desc">Create Exams</div>
                    </div>

                    <div class="stat">
                        <div class="stat-figure text-{{ config('color.study_credit') }}"><i class="{{ config('icon.study_credit') }} text-2xl"></i> </div>
                        <div class="stat-title">Study Credits</div>
                        <div class="stat-value text-{{ config('color.study_credit') }}">{{ $product->study_credits }}</div>
                        <div class="stat-desc">Take Public Exams</div>
                    </div>
                </div>


                @if ($product->isSubscription)
                    <h2 class="mt-6 text-xl font-bold text-info">Subscription</h2>
                @else
                    <h2 class="mt-6 text-xl font-bold text-info">One Time</h2>
                @endif
                <div class="shadow stats stats-vertical">
                    <div class="stat">
                        <div class="stat-figure text-{{ config('color.usd') }}"><i class="{{ config('icon.usd') }} text-2xl"></i> </div>
                        <div class="stat-title">Price USD</div>
                        @if ($product->isSubscription)
                        <a href="{{ route('checkout', ['product' => $product, 'price' => 'monthly']) }}" class="btn btn-secondary hover:btn-primary text-base-100"><div class="stat-value">${{ $product->price }}</div></a>
                        <div class="stat-desc"><span class="text-warning">Monthly</span></div>
                        @else
                        <a href="{{ route('checkout', ['product' => $product, 'price' => 'one-time']) }}" class="btn btn-secondary hover:btn-primary text-base-100 @if($product->price < 1) btn-disabled @endif" @if($product->price < 1) disabled @endif><div class="stat-value">${{ $product->price }}</div></a>
                        <div class="stat-desc"><span class="text-warning">One Time @if ($product->price < 1) signup credits @endif</span></div>
                        @endif
                    </div>
                    
                    @if ($product->isSubscription && $product->annual_price)
                    <div class="stat">
                        <div class="stat-figure text-{{ config('color.usd') }}"><i class="{{ config('icon.usd') }} text-2xl"></i> </div>
                        <div class="stat-title">Price USD</div>
                        <a href="{{ route('checkout', ['product' => $product, 'price' => 'annual']) }}" class="btn btn-secondary hover:btn-primary text-base-100"><div class="stat-value">${{ $product->annual_price }}</div></a>
                        <div class="stat-desc"><span class="text-warning">Yearly</span></div>
                    </div>

                    @endif
                </div>

            </x-card.mini>
        @endforeach
    </x-card.main>

    <x-card.main title="Free Credits">
        <x-card.mini>
            <text.main>
                All users will get more free credits over time just by using the site. There are two ways to earn more credits.
                <br /><br />
                <ul class="list-disc list-inside">
                    <li class="list-item"> Master exams</li>
                    <li class="list-item"> Create public exams that others master</li>
                </ul>
                <br /><br />
                If you gain mastery in the exams that you are taking then you will be awarded additional credits.
                <br /><br />
                Also you will be given credits if you make a test public that others gain mastery in.
                <br /><br />
                Either way, using Acolyte Academy can be free if you are patient and dedicated to learning.
            </text.main>
        </x-card.mini>
    </x-card.main>

@endsection