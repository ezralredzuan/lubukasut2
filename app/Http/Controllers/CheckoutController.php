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
        Log::info('Checkout Request Data:', $request->all());

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Calculate total price after applying discount (if any)
        $totalPrice = $request->input('total_price');
        $discount = session('discount_amount', 0);
        $finalPrice = $totalPrice - $discount;

        // Insert order into database
        $cartItems = json_decode($request->input('cart_items'), true) ?? [];

        if (!is_array($cartItems)) {
            return back()->withErrors(['cart' => 'Invalid cart data. Please try again.']);
        }
        foreach ($cartItems as $item) {
            Order::create([
                'no_phone' => $request->input('no_phone'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'postal_code' => $request->input('postal_code'),
                'order_date' => Carbon::now()->format('Y-m-d'),
                'payment_status' => 0,
                'delivery_status' => 0,
                'shipped_date' => null,
                'price' => $item['price'],  // Now assigns price per item
                'customer_id' => Auth::check() ? Auth::id() : null,
                'product_id' => $item['id'] ?? null,  // Fix product_id

            ]);

        }



        // Clear session cart and discount
        session()->forget(['cart', 'discount_amount']);


        return redirect()->route('order.success')->with('success', 'Your order has been placed successfully!');
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
}
