<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(4)->get(); // Fetch latest 4 products
        return view('welcome', compact('products'));
    }
}
