<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Get all brands for the filter dropdown
        $brands = Brand::all();

        // Query products based on filters
        $query = Product::query();

        // Search Filter
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Brand Filter
        if ($request->has('brand') && $request->brand != 'all') {
            $query->where('brand_id', $request->brand);
        }

        // Price Range Filter (assuming a 'price' range is sent, for example 50 to 200)
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Size Filter (assuming sizes are a comma-separated string like "S,M,L")
        if ($request->has('size') && $request->size != 'all') {
            $query->whereIn('size', explode(',', $request->size));
        }

        // Gender Filter
        if ($request->has('gender') && $request->gender != 'all') {
            $query->where('gender', $request->gender);
        }

        // Paginate the products
        $products = $query->paginate(12); // You can adjust the pagination as per your need

        return view('product', compact('products', 'brands'));
    }
}
