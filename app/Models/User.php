<?php

namespace App\Models;

use App\Presenters\CustomerPresenter;
use App\Presenters\InvoicePresenter;
use App\Presenters\SubscriptionPresenter;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->hasOneThrough(Plan::class, Subscription::class,
            'user_id', 'stripe_id', 'id', 'stripe_price');
    }

    public function presentSubscription()
    {
        if (!$subscription = $this->subscription('default')) {
            return null;
        }
        return new SubscriptionPresenter($subscription->asStripeSubscription());
    }

    public function presentUpcomingInvoice()
    {
        if (!$invoice = $this->upcomingInvoice()) {
            return null;
        }
        return new InvoicePresenter($invoice->asStripeInvoice());
    }

    public function presentCustomer()
    {
        if (!$this->hasStripeId()) {
            return null;
        }
        return new CustomerPresenter($this->asStripeCustomer());
    }
}
