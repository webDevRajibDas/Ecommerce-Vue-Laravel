<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\District;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('frontend.shopping.cart', compact( 'cartItems'));
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


    public function updateCartPage(Request $request)
    {


        // Validate the request
        $request->validate([
            'product_id' => 'required|integer|exists:products,id', // Ensure the product exists
            'quantity' => 'required|integer|min:1', // Ensure quantity is at least 1
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $product = Product::find($productId);
        //dd($product);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $price = $product->price;
        $subtotal = $quantity * $price;
        $cart = json_decode($request->cookie('cart'), true) ?? [];
        if (!is_array($cart)) {
            $cart = [];
        }

        $sessionCart = session('cart', []);
        if (is_array($sessionCart)) {
            $cart = array_merge($cart, $sessionCart);
        }
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            $cart[$productId]['subtotal'] = $subtotal;
        } else {
            // If the product is not in the cart, add it
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        if ($request->hasCookie('cart')) {
            $cookie = Cookie::make('cart', json_encode($cart), 60 * 24 * 30); // 30 days expiration
            dd($cookie);
            return response()->json([
                'success' => true,
                'subtotal' => number_format($subtotal, 2),
            ])->withCookie($cookie);
        } else {
            session(['cart' => $cart]);
            return response()->json([
                'success' => true,
                'subtotal' => number_format($subtotal, 2),
            ]);
        }
    }





    public function checkOuts()
    {
        if (auth()->check()) {
            $user_id = auth()->id();
            $cartItems = Cart::where('user_id', $user_id)->with('products')->get();
        } else {
            $cart = json_decode(request()->cookie('cart'), true) ?? session('cart', []);

            dd($cart);

            $cartItems = collect($cart)->map(function ($quantity, $product_id) {
                $product = \App\Models\Product::find($product_id);
                return $product ? (object) ['product' => $product, 'quantity' => $quantity] : null;
            })->filter()->values()->all();
        }


        return view('frontend.shopping.checkout');

    }




}
