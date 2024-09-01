@extends('layouts.app2', ['heading' => 'Your Credits'])

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

    <x-card.main title="Credit Transaction History" size='xl'>
        <x-card.mini>
            <x-table.main>
                <x-table.head>
                    <x-table.hcell>Date</x-table.hcell>
                    <x-table.hcell>Transaction Type</x-table.hcell>
                    <x-table.hcell>Description</x-table.hcell>
                    <x-table.hcell>Author <i class="{{ config('icon.credit') }} text-{{ config('color.credit') }} text-lg"></i> </x-table.hcell>
                    <x-table.hcell>Study <i class="{{ config('icon.credit') }} text-{{ config('color.credit') }} text-lg"></i></x-table.hcell>
                </x-table.head>
                <x-table.body>
                    @forelse ($user->creditHistory as $history)
                        <x-table.row>
                            <x-table.cell>{{ date('d M Y', strtotime($history->created_at)) }}</x-table.cell>
                            <x-table.cell>{{ $history->title }}</x-table.cell>
                            <x-table.cell>{{ $history->reason }}</x-table.cell>
                            <x-table.cell>{{ $history->architect_change }}</x-table.cell>
                            <x-table.cell>{{ $history->study_change }}</x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell>No credit transaction history yet</x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-table.body>
            </x-table.main>
        </x-card.mini>
    </x-card.main>

    <x-card.main>
        <div class="w-full text-center">
            <a href="{{ route('profile.view', $user) }}" class="btn btn-secondary">Back to Transcript</a>
        </div>
    </x-card.main>
@endsection
