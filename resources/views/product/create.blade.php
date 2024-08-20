@extends('layouts.app2')

@section('content')

    <x-card.main title="Create A Product">
        
        <form action="{{ route('admin.product.store') }}" method="post">
            @csrf
            <x-card.mini title="Product Details">
                <x-form.text label="Name" name="name" value="{{ old('name') }}" />
                <x-form.text label="Description" name="description" value="{{ old('name') }}" />
            </x-card.mini>

            <x-card.mini title="Product Rewards">
                <x-form.text label="Architect Credits" name="architect_credits" size="half" value="{{ old('name') }}" />
                <x-form.text label="Study Credits" name="study_credits" size="half" value="{{ old('name') }}" />
            </x-card.mini>

            <x-card.mini title="Pricing Details">
                <x-form.text label="Product Price" name="price" size="half" value="{{ old('name') }}" />
                <x-form.checkbox label="Product is Subscription" name="isSubscription" style="toggle" checked="{{ old('isSubscription') }}" />
                <x-form.text label="Subscription Annaul Price" name="annual_price" size="half" value="{{ old('name') }}" />
            </x-card.mini>

            <x-card.mini title="Stripe IDs">
                <x-form.text label="Product ID" name="stripe_product_id" value="{{ old('name') }}" />
                <x-form.text label="Price ID" name="stripe_price_id" value="{{ old('name') }}" />
                <x-form.text label="Annual Price ID" name="stripe_annual_price_id" value="{{ old('name') }}" />
            </x-card.mini>


            <div class="block w-full lg:flex">
                <div class="w-full mx-3 lg:w-1/2">
                    <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
                <div class="w-full mx-3 lg:w-1/2">
                    <x-card.buttons submitLabel="Create Product" />
                </div>
            </div>
        </form>
    </x-card.main>

@endsection
