<?php

namespace App\Presenters;


use Carbon\Carbon;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

class SubscriptionPresenter
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function interval()
    {
        return $this->model->plan->interval;
    }

    public function cancelAt(){
       return (new Carbon($this->model->cancel_at))->toDateString();
    }

    public function amount()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter(config('cashier.currency_local'), NumberFormatter::CURRENCY),
            new ISOCurrencies()
        );
        $money = new Money(
            $this->model->plan->amount,
            new Currency(strtoupper(config('cashier.currency')))
        );
        return $formatter->format($money);
    }

    public  function  coupon(){
        if(!$discount=$this->model->discount){
            return  null;
        }
        return new CouponPresenter($discount->coupon);
    }
}
