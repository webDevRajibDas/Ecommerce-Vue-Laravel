<?php

use App\Models\Cart;
use Carbon\Carbon;

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
        return Cart::where('user_id', $user_id)->sum('quantity');
    }
}