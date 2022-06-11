<?php

namespace App\Http\Controllers\Account\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionSwapController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'subscribed']);
    }

    public function index(Request $request)
    {

        $plans = Plan::where('slug', '!=', $request->user()->plan->slug)->get();
        return view('account.subscriptions.swap', compact('plans'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'plan' => 'required|exists:plans,slug'
        ]);
        $plan = Plan::where('slug', $request->plan)
            ->first();
        $request->user()->subscription('default')
            ->swap($plan->stripe_id);
        return redirect()->route('account.subscriptions');
    }
}
