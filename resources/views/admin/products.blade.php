@extends('layouts.app2')

@section('content')

    <x-card.main title="Product Manager" size="lg">
        @foreach ($products as $product)
            <x-card.mini title="{{ $product->name }}">
                ${{ $product->price }}
                <div class="dropdown">
                    <div class="m-1 btn btn-secondary btn-sm btn-outline" tabindex="0" role="button">More Actions...</div>
                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                        {{-- <li><a href="{{ route('admin.product', $product) }}"><i class="{{ config('icon.latest_summary') }} text-lg"></i> Edit Product</a></li> --}}
                    </ul>
                </div>
            </x-card.mini>
        @endforeach
    </x-card.main>

    <x-card.main>
        <div class="w-full text-right">
            <a href="{{ route('admin.product.new') }}" class="btn btn-primary">Add Product</a>
        </div>
    </x-card.main>

@endsection
