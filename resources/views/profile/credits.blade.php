@extends('layouts.app2')

@section('content')
    <x-page.title>Acolyte Credit History</x-page.title>
    
    @can ('view', $user->credit)
        <x-card.main size="lg">
            <x-card.mini title="Account Credits">
                <div class="shadow stats stats-vertical lg:stats-horizontal">
                    <div class="stat">
                        <div class="stat-title">Author Credits</div>
                        <div class="stat-value">{{ $user->credit->architect }}</div>
                        <div class="stat-desc"># of Exams you can Create</div>
                    </div>

                    <div class="stat">
                        <div class="stat-title">Study Credits</div>
                        <div class="stat-value">{{ $user->credit->study }}</div>
                        <div class="stat-desc"># of Public Exams you can Take</div>
                    </div>
                </div>
            </x-card.mini>
        </x-card.main>
    @endcan

    @can('gift', $user->credit)
        <x-card.main title="Gift Credits to Acolyte">
            <x-card.mini>
                <form action="{{ route('profile.gift', $user) }}" method="POST">
                    @csrf
                    
                    @php
                        $options = array();
                        foreach($products as $product) {
                            $options[$product->id] = "{$product->name} || +{$product->architect_credits} Author, +{$product->study_credits} Study";
                        }
                    @endphp

                    <x-form.dropdown name="package" :values=$options label="Gift Package" />
                    <x-form.text name="reason" label="Gift Reason Message" helptext="This will be displayed to the user" />
                    
                    <div class="w-full text-center lg:text-right">
                        <input type="submit" value="Gift Credits" class="btn btn-primary" />
                    </div>
                </form>
            </x-card.mini>
        </x-card.main>
    @endcan

    <x-card.main>
        <div class="w-full text-center">
            <a href="{{ route('profile.view', $user) }}" class="btn btn-secondary">Back to Transcript</a>
        </div>
    </x-card.main>
@endsection
