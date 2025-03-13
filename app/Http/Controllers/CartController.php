<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $user_id = auth()->id() ?? Session::getId();

        // Insert or update the cart
        $cartItem = Cart::updateOrCreate(
            ['user_id' => $user_id, 'product_id' => $product_id],
            ['quantity' => \DB::raw("quantity + $quantity")]
        );

        return response()->json(['message' => 'Product added to cart successfully!']);

    }


    public function viewCart()
    {
        $user_id = auth()->id() ?? session()->getId();
        $cartItems = Cart::where('user_id', $user_id)->with('products')->get();
        $districts = District::all();
        return view('frontend.shopping.cart', compact('districts', 'cartItems'));
    }

    public function cartCount(){
        $user_id = auth()->id() ?? session()->getId();
        $cart_count = \App\Models\Cart::where('user_id', $user_id)->sum('quantity');

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
        $user_id = auth()->id() ?? session()->getId();
        $cartItems = Cart::where('user_id', $user_id)->with('products')->get();

        $html = view('partials.mini-cart', compact('cartItems'))->render();
        return response()->json([
            'html' => $html,
            'cart_count' => $cartItems->sum('quantity')
        ]);
    }

}
