@extends('layouts.app2')

@section('content')

    <x-card.main title="Create A Product">
        
        <form action="{{ route('admin.product.store') }}" method="post">
            @csrf
            <x-card.mini title="Product Details">
                <x-form.text label="Name" name="name" />
                <x-form.text label="Description" name="description" />
            </x-card.mini>

            <x-card.mini title="Product Rewards">
                <x-form.text label="Architect Credits" name="architect_credits" size="half" />
                <x-form.text label="Study Credits" name="study_credits" size="half" />
            </x-card.mini>

            <x-card.mini title="Pricing Details">
                <x-form.text label="Product Price" name="price" size="half" />
                <x-form.checkbox label="Product is Subscription" name="isSubscription" />
                <x-form.text label="Subscription Annaul Price" name="annual_price" size="half" />
            </x-card.mini>

            <x-card.mini title="Stripe IDs">
                <x-form.text label="Product ID" name="stripe_product_id" />
                <x-form.text label="Price ID" name="stripe_price_id" />
                <x-form.text label="Annual Price ID" name="stripe_annual_price_id" />
            </x-card.mini>

            <x-card.buttons submitLabel="Create Product" />
        </form>
    </x-card.main>

@endsection
