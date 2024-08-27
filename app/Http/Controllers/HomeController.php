<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Set;
use App\Models\Test;
use App\Models\User;
use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Show the application dashboard.
     */
    public function index(): View
    {

        return view('home');
    }

    public function history($id): View
    {
        $user_id = Auth::id();

        $user = User::find($user_id);
        $set = Set::find($id);

        $tests = Test::where('user_id', '=', $user->id)
            ->where('set_id', '=', $id)
            ->orderBy('end_at', 'desc')
            ->get();

        foreach ($tests as $test) {
            $start = new Carbon($test->start_at);
            $diffTime = $start->diffInMinutes($test->getAttributes()['end_at']);
            $test->duration = $diffTime;
        }

        return view('history', [
            'tests' => $tests,
            'set' => $set,
        ]);
    }

    public function colors(): View
    {
        return view('colors');
    }

    public function privacy(): View
    {
        return view('home.privacy-policy');
    }

    public function tos(): View
    {
        return view('home.terms-of-service');
    }

    public function pricing(): View
    {
        if (! Feature::active('mage-upgrade')) {
            abort(404, 'Not found');
        }

        $products = Product::orderBy('isSubscription', 'asc')->orderBy('price', 'asc')->get();

        return view('home.pricing')->with([
            'products' => $products,
        ]);
    }

    public function checkout(Request $request, Product $product, String $plan = 'one-time') 
    {
        if (! Feature::active('mage-upgrade')) {
            abort(404, 'Not found');
        }

        $priceId = ($plan == 'annual') ? $product->stripe_annual_price_id : $product->stripe_price_id;

        if ($product->isSubscription) {
            return $request->user()
            ->newSubscription($product->stripe_product_id, $priceId)
            ->checkout([
                'success_url' => route('purchase-success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('pricing'),
            ]);
        }
        
        $quantity = 1;
        return $request->user()->checkout([$priceId => $quantity], [
            'success_url' => route('purchase-success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('pricing'),
        ]);
    }

    public function success(Request $request) 
    {
        if (! Feature::active('mage-upgrade')) {
            abort(404, 'Not found');
        }

        $checkoutSession = $request->user()->stripe()->checkout->sessions->retrieve($request->get('session_id'));

        dd($checkoutSession);

        return view('home.purchase-success');
    }
}
