<?php

use Illuminate\Support\Facades\Cookie;
use App\Models\Cart;
use Carbon\Carbon;



/*
 * Global helpers file with misc functions.
 */
if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('convertMdyToYmd')) {
    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}



if (! function_exists('getCartCount')) {
    function getCartCount()
    {
        $user_id = auth()->id() ?? session()->getId();
        $cart_count = Cookie::get('cart_count');
        if (!$cart_count) {
            $cart_count = Cart::where('user_id', $user_id)->sum('quantity');

            // Store cart count in a cookie (valid for 60 minutes)
            Cookie::queue('cart_count', $cart_count, 60);
        }

        return $cart_count;
    }
}