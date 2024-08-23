<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
class ProductController extends Controller
{
    
    
    public function photocopyAdd() {
        $photocopyProducts = Product::whereHas('category', function ($query) {
            $query->where('name', 'photocopy');
        })->get();
        $laminatings = Product::whereHas('category', function ($query) {
            $query->where('name', 'laminating');
        })->get();
        $jilids = Product::whereHas('category', function ($query) {
            $query->where('name', 'jilid');
        })->get();
        
        // Get unique sizes
        $sizes = $photocopyProducts->unique('size')->values();
        $colors = $photocopyProducts->unique('color_type')->values();
        $additionalLaminating = $laminatings->unique('size')->values();
       
        return view('users.features.add', compact('photocopyProducts', 'sizes', 'colors', 'additionalLaminating', 'jilids'));
    }
    public function cetakfotoAdd() {
        $cetakfotoProducts = Product::whereHas('category', function ($query) {
            $query->where('name', 'cetakfoto');
        })->get();
        $laminatings = Product::whereHas('category', function ($query) {
            $query->where('name', 'laminating');
        })->get();
        $jilids = Product::whereHas('category', function ($query) {
            $query->where('name', 'jilid');
        })->get();
        
        // Get unique sizes
        $sizes = $cetakfotoProducts->unique('size')->values();
        $colors = $cetakfotoProducts->unique('color_type')->values();
        $additionalLaminating = $laminatings->unique('size')->values();
       
        return view('users.features.addPhoto', compact('cetakfotoProducts', 'sizes', 'colors', 'additionalLaminating', 'jilids'));
    }
    public function printoutAdd() {
        $printoutProducts = Product::whereHas('category', function ($query) {
            $query->where('name', 'printout');
        })->get();
        $laminatings = Product::whereHas('category', function ($query) {
            $query->where('name', 'laminating');
        })->get();
        $jilids = Product::whereHas('category', function ($query) {
            $query->where('name', 'jilid');
        })->get();
        
        // Get unique sizes
        $sizes = $printoutProducts->unique('size')->values();
        $colors = $printoutProducts->unique('color_type')->values();
        $additionalLaminating = $laminatings->unique('size')->values();
       
        return view('users.features.add', compact('printoutProducts', 'sizes', 'colors', 'additionalLaminating', 'jilids'));
    }
    public function index(Request $request)
    {
        // Fetch selected category from request
        $excludedCategories = ['cetakfoto', 'printout', 'laminating', 'jilid', 'photocopy'];

        // Query to retrieve products
        $query = Product::query();

        // Apply exclusion filter
        $query->whereNotIn('category_id', function ($query) use ($excludedCategories) {
            $query->select('id')
                ->from('categories')
                ->whereIn('name', $excludedCategories);
        });

        // Check if search parameter is present
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('size', 'like', "%$search%")
                    ->orWhere('color_type', 'like', "%$search%");
            });
        }

        // Retrieve products based on query
        $products = $query->get();
        return view('users.features.product', compact('products'));
    }

}