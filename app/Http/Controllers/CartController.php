<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {

        $productId = $request->product_id;
        $quantity = $request->quantity;

        if (auth()->check()) {
            $cart = session('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId] += $quantity;
            } else {
                $cart[$productId] = $quantity;
            }
            session(['cart' => $cart]);
        } else {
            $cart = json_decode($request->cookie('cart'), true) ?? [];
            if (isset($cart[$productId])) {
                $cart[$productId] += $quantity;
            } else {
                $cart[$productId] = $quantity;
            }
            $cookie = Cookie::make('cart', json_encode($cart), 60 * 24 * 30);
            return response()->json(['cart' => $cart, 'message'=>'Cart Add Successful'])->withCookie($cookie);
        }

        return response()->json(['cart' => $cart]);
    }


    public function viewCart()
    {
        if (auth()->check()) {
            $user_id = auth()->id();
            $cartItems = Cart::where('user_id', $user_id)->with('products')->get();
        } else {
            $cart = json_decode(request()->cookie('cart'), true) ?? session('cart', []);

            $cartItems = collect($cart)->map(function ($quantity, $product_id) {
                $product = \App\Models\Product::find($product_id);
                return $product ? (object) ['product' => $product, 'quantity' => $quantity] : null;
            })->filter()->values()->all();
        }

        // Fetch districts
        $districts = District::all();
        return view('frontend.shopping.cart', compact('districts', 'cartItems'));
    }

    public function cartCount(){
        if (auth()->check()) {
            $user_id = auth()->id();
            $cart_count = \App\Models\Cart::where('user_id', $user_id)->sum('quantity');
        } else {
            $cart = json_decode(request()->cookie('cart'), true) ?? session('cart', []);
            $cart_count = array_sum($cart);
        }

        return response()->json(['cart_count' => $cart_count]);
    }

    public function removeCartItem(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if ($cart) {
            $cart->delete();
            return response()->json(['message' => 'Item removed successfully']);
        }
        return response()->json(['message' => 'Item not found'], 404);
    }



    public function getCartItems()
    {
        if (auth()->check()) {
            $user_id = auth()->id();
            $cartItems = Cart::where('user_id', $user_id)->with('product')->get();
        } else {
            $cart = json_decode(request()->cookie('cart'), true) ?? session('cart', []);
            $cartItems = [];
            foreach ($cart as $product_id => $quantity) {
                $product = \App\Models\Product::find($product_id);
                if ($product) {
                    $cartItems[] = (object) [
                        'product' => $product,
                        'quantity' => $quantity,
                    ];
                }
            }
        }

        $html = view('partials.mini-cart', compact('cartItems'))->render();
        $cart_count = auth()->check()
            ? $cartItems->sum('quantity')
            : array_sum($cart ?? []);

        return response()->json([
            'html' => $html,
            'cart_count' => $cart_count,
        ]);
    }


    public function checkOuts()
    {
        return view('frontend.shopping.checkout');

    }




}
