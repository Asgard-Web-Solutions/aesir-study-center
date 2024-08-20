<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductDetailsRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::all();

        return view('product.index')->with([
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Product::class);

        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductDetailsRequest $request)
    {
        $this->authorize('create', Product::class);

        $validated = $request->validated();

        $validated['isSubscription'] = (isset($request->isSubmission)) ? 1 : 0;
        $validated['annual_price'] = $validated->annual_price ?? 000.00;
        // dd($validated);
        Product::create($request->validated());

        return redirect()->route('admin.product.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
