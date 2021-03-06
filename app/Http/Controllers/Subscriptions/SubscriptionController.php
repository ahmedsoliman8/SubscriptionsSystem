<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Rules\ValidCoupon;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'not.subscribed']);
    }

    public function index(Request $request)
    {
        return view('subscriptions.checkout', [
            'intent' => $request->user()->createSetupIntent()
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'coupon' => ['nullable', new ValidCoupon],
            'plan' => 'required|exists:plans,slug'
        ]);
        // dd($request->coupon);
        $plan = Plan::where('slug', $request->get('plan', 'monthly'))
            //  ->orWhere('slug', 'monthly')
            ->first();
        try {
            $request->user()->newSubscription('default', $plan->stripe_id)
                ->withCoupon($request->coupon)
                ->create($request->token);
        } catch (IncompletePayment $e) {
            // Check specific conditions...
            return redirect()->route(
                'cashier.payment',
                [$e->payment->id, 'redirect' => route('account.subscriptions')]);

        }

        return back();
    }
}
