<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Order;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;


class CheckoutController extends Controller
{
    public function store(Request $request)
    {

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }
        $totalPrice = $request->input('price');
        $discount = session('discount_amount', 0);
        $finalPrice = $totalPrice - $discount;
        $cartItems = json_decode($request->input('cart_items'), true);
        $productIds = collect($cartItems)->pluck('id')->filter()->unique()->toArray(); // Make sure they are clean integers

        // Save one order only — combine cart items if needed
        $order = Order::create([
            'no_phone' => $request->input('no_phone'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'postal_code' => $request->input('postal_code'),
            'order_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'payment_status' => 0,
            'delivery_status' => 0,
            'shipped_date' => null,
            'price' => $finalPrice,
            'customer_id' => Auth::check() ? Auth::id() : null,
            'product_id' => 1, // Store as JSON string
            'reference_no' => strtoupper(uniqid('ORD-'))
        ]);

        // Clean up session
        session(['last_order' => $order]); // Ensure this is after the order creation

        return redirect()->route('order.success')->with('order', 'Your order has been placed successfully!');
    }

    public function applyCoupon(Request $request)
    {
        $code = $request->input('coupon_code');
        $total = $request->input('total_price');

        // Find promotion by code
        $promotion = Promotion::where('promotion_code', $code)
                        ->where('valid_until_date', '>=', Carbon::today())
                        ->first();

        if (!$promotion) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon.']);
        }

        // Calculate discount
        $discountAmount = ($promotion->discount_percentage / 100) * $total;
        $newTotal = $total - $discountAmount;

        return response()->json([
            'success' => true,
            'discount' => number_format($discountAmount, 2),
            'new_total' => number_format($newTotal, 2)
        ]);
    }

    public function orderSuccess()
    {
        // Retrieve the last order from the session
        $order = session('last_order');

        // Check if the order exists
        if (!$order) {
            return view('order/success')->with(['order' => null]);
        }

        $fullName = Auth::check() ? Auth::user()->full_name : 'Guest';

        return view('order/success', compact('order', 'fullName'));
    }

}
