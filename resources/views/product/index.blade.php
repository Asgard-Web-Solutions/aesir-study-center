@extends('layouts.app2')

@section('content')

    <x-card.main title="Product Manager" size="lg">
        @foreach ($products as $product)
            <x-card.mini title="{{ $product->name }}">
                <div class="block w-full mb-3 lg:flex">
                    <div class="w-full lg:w-3/4">                    
                        <x-text.main>{{ $product->description }}</x-text.main>
                    </div>
                    <div class="w-full lg:w-1/4">
                        <a href="{{ route('admin.product.edit', $product) }}" class="btn btn-secondary btn-sm"><i class="{{ config('icon.edit') }} text-lg"></i> Edit Product</a>
                    </div>
                </div>
                <div class="shadow stats">
                    <div class="stat">
                        <div class="stat-title">Price</div>
                        <div class="stat-figure">${{ $product->price ?? "0.00" }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-title">Annual Price</div>
                        <div class="stat-figure">${{ $product->annual_price ?? "0.00" }}</div>
                    </div>
                </div>
            </x-card.mini>
        @endforeach
    </x-card.main>

    <x-card.main>
        <div class="w-full text-right">
            <a href="{{ route('admin.product.create') }}" class="btn btn-primary">Add Product</a>
        </div>
    </x-card.main>

@endsection
