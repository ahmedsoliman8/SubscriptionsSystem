<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Stripe\Exception\InvalidRequestException;


class ValidCoupon implements Rule
{
    protected  $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $stripe = new \Stripe\StripeClient(
                config('cashier.secret')
            );
            $coupon = $stripe->coupons->retrieve($value, []);
            if (!$coupon->valid){
                $this->message='Coupon is invalid.';
                return  false;
            }
        } catch (InvalidRequestException $e) {
            $this->message='Coupon does not exists.';
            return  false;
        }
        return  true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
