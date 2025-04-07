<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiries;
$customer = session('customer');

class InquiriesController extends Controller
{
    // Show the FAQ page
    public function index()
    {
        return view('faq');
    }

    // Store the inquiry form submission
    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'email' => 'required|email|max:255',
            'inquiries_title' => 'required|max:255',
            'description' => 'required',
        ]);

        // Save inquiry to database
        Inquiries::create([
            'email' => $request->email,
            'inquiries_title' => $request->inquiries_title,
            'description' => $request->description,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Your inquiry has been submitted successfully!');
    }
}
