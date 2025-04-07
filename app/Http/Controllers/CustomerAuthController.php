<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('customer.login');
    }

    public function login(Request $request)
    {
        // Validate input data
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->username, // assuming admin uses email to log in
            'password' => $request->password,
        ];

        // Try admin login
        if (Auth::guard('web')->attempt($credentials)) {
            return response()->json([
                'message' => 'Admin login successful',
                'role' => 'admin',
                'redirect' => route('filament.admin.pages.dashboard'),
            ], 200);
        }

        // Try customer login
        $customer = Customer::where('username', $request->username)->first();
        if ($customer && Hash::check($request->password, $customer->password)) {
            Session::put('customer', $customer);
            return response()->json([
                'message' => 'Customer login successful',
                'role' => 'customer',
                'redirect' => route('home'), // or your intended front page
            ], 200);
        }

        // If both fail
        return response()->json(['message' => 'Invalid username or password'], 401);
    }

    public function logout()
    {
        // Forget the customer session and redirect to home page
        Session::forget('customer');
        return redirect()->route('home');
    }

    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:customers',
            'password' => 'required|confirmed|min:6',
            'no_phone' => 'required|string|max:20',
            'email' => 'required|email|unique:customers',
        ]);

        // Create a new customer
        $customer = Customer::create([
            'full_name' => $validated['full_name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'no_phone' => $validated['no_phone'],
            'email' => $validated['email'],
        ]);

        // Put customer data in session after registration
        Session::put('customer', $customer);

        // Return success response after successful registration
        return response()->json(['message' => 'registered'], 200);
    }
}
