<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use App\Helpers\PDFHelper;
class CartController extends Controller
{
    public function photocopyAddToCart(Request $request) {
        
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:10240', // Example: Max 10MB PDF file
            'quantity' => 'required|integer|min:1',
            'product-size' => 'required', // Ensure product size ID exists in products table
            'product-color' => 'required', // Ensure product color ID exists in products table
            // 'choose-option' => 'required', // Ensure either laminating or jilid is selected
        ]);
        //PRODUCT FOTO COPY
        $productPhotoCopy = Product::where('category_id', function ($query) {
            $query->select('id')
                ->from('categories')
                ->where('name', 'photocopy');
        })
        ->where('size', $request->input('product-size'))
        ->where('color_type', $request->input('product-color'))
        ->firstOrFail();
        
        $additionalProduct = [];
        $idAddtional = null;
        if(!empty($request->input('choose-option'))){
            $idAddtional = ($request->input('choose-option') == 'jilid' ? $request->input('jilid-type') : $request->input('laminating-type'));
            // dd($idAddtional);
            $additionalProduct = Product::where('id', $idAddtional)
            ->firstOrFail();
        }
        
        if ($request->hasFile('file_pdf')) {
            // Get the original file name
            $originalFileName = $request->file('file_pdf')->getClientOriginalName();
    
            // Store the uploaded file in the public/pdfs directory
            $path = $request->file('file_pdf')->storeAs('public/pdfs', $originalFileName);
    
            // Get the public URL of the uploaded file
            $publicPath = 'storage/pdfs/' . $originalFileName;
    
            // Get the full path of the uploaded file for counting pages
            $filePath = storage_path('app/public/pdfs/' . $originalFileName);
    
            // Count the number of pages in the PDF
            $pageCount = PDFHelper::countPages($filePath);
            
            // Return the result to the view
            // return redirect()->back()->with('status', 'Item added to cart successfully.');
        } else {
            return back()->withErrors(['file_pdf' => 'Please upload a valid PDF file.']);
        }
        
        
        
        
        // Calculate price based on selected product, quantity, and additional service (laminating or jilid)
         // Base price calculation

        // Additional handling for laminating or jilid options
        if ($request->input('choose-option') === 'jilid') {
            // Handle jilid logic
            $price = $productPhotoCopy->price * $request->quantity + $additionalProduct->price * $request->quantity;
        } elseif ($request->input('choose-option') === 'laminating') {
            $price = $productPhotoCopy->price * $request->quantity + $additionalProduct->price * $request->quantity * $pageCount;
        } else{
            $price = $productPhotoCopy->price * $pageCount * $request->quantity;
        }
       
        // Store the cart item in the database
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $productPhotoCopy->id,
            'file_pdf' => $publicPath,
            //    http://127.0.0.1:8000/storage/pdfs/s.pdf
            'quantity' => $request->quantity,
            'number_of_page' => $pageCount,
            'additional_id' => $idAddtional,
            'price' => $price,
        ]);

        // Redirect back with success message or handle as needed
        return redirect()->back()->with('status', 'Item added to cart successfully.');
    }
    
    // public function cetakfotoAddToCart(Request $request) {
        
    //     $request->validate([
    //         'file_pdf' => 'required|file|mimes:pdf|max:10240', // Example: Max 10MB PDF file
    //         'quantity' => 'required|integer|min:1',
    //         'product-size' => 'required', // Ensure product size ID exists in products table
            
    //         // 'choose-option' => 'required', // Ensure either laminating or jilid is selected
    //     ]);
    //     //PRODUCT FOTO COPY
    //     $productCetakfoto = Product::where('category_id', function ($query) {
    //         $query->select('id')
    //             ->from('categories')
    //             ->where('name', 'cetakfoto');
    //     })
    //     ->where('size', $request->input('product-size'))
    //     ->where('color_type', $request->input('product-color'))
    //     ->firstOrFail();
    //     $additionalProduct = [];
    //     $idAddtional = null;
    //     if(!empty($request->input('choose-option'))){
    //         $idAddtional = ($request->input('choose-option') == 'jilid' ? $request->input('jilid-type') : $request->input('laminating-type'));
    //         // dd($idAddtional);
    //         $additionalProduct = Product::where('id', $idAddtional)
    //         ->firstOrFail();
    //     }
        
    //     if ($request->hasFile('file_pdf')) {
    //         // Get the original file name
    //         $originalFileName = $request->file('file_pdf')->getClientOriginalName();
    
    //         // Store the uploaded file in the public/pdfs directory
    //         $path = $request->file('file_pdf')->storeAs('public/pdfs', $originalFileName);
    
    //         // Get the public URL of the uploaded file
    //         $publicPath = 'storage/pdfs/' . $originalFileName;
    
    //         // Get the full path of the uploaded file for counting pages
    //         $filePath = storage_path('app/public/pdfs/' . $originalFileName);
    
    //         // Count the number of pages in the PDF
    //         $pageCount = PDFHelper::countPages($filePath);
            
    //         // Return the result to the view
    //         // return redirect()->back()->with('status', 'Item added to cart successfully.');
    //     } else {
    //         return back()->withErrors(['file_pdf' => 'Please upload a valid PDF file.']);
    //     }
        
        
        
        
    //     // Calculate price based on selected product, quantity, and additional service (laminating or jilid)
    //      // Base price calculation

    //     // Additional handling for laminating or jilid options
    //     if ($request->input('choose-option') === 'jilid') {
    //         // Handle jilid logic
    //         $price = $productCetakfoto->price * $request->quantity + $additionalProduct->price * $request->quantity;
    //     } elseif ($request->input('choose-option') === 'laminating') {
    //         $price = $productCetakfoto->price * $request->quantity + $additionalProduct->price * $request->quantity * $pageCount;
    //     } else{
    //         $price = $productCetakfoto->price * $pageCount * $request->quantity;
    //     }
       
    //     // Store the cart item in the database
    //     Cart::create([
    //         'user_id' => auth()->id(),
    //         'product_id' => $productCetakfoto->id,
    //         'file_pdf' => $publicPath,
    //         //    http://127.0.0.1:8000/storage/pdfs/s.pdf
    //         'quantity' => $request->quantity,
    //         'number_of_page' => $pageCount,
    //         'additional_id' => $idAddtional,
    //         'price' => $price,
    //     ]);

    //     // Redirect back with success message or handle as needed
    //     return redirect()->back()->with('status', 'Item added to cart successfully.');
    // }


    public function cetakfotoAddToCart(Request $request) {
        $request->validate([
            'file_pdf' => 'required|file|mimes:jpeg,png,jpg|max:10240', // Accept image files
            'quantity' => 'required|integer|min:1',
            'product-size' => 'required',
        ]);
    
        $productCetakfoto = Product::where('category_id', function ($query) {
            $query->select('id')
                  ->from('categories')
                  ->where('name', 'cetakfoto');
        })
        ->where('size', $request->input('product-size'))
        ->where('color_type', $request->input('product-color'))
        ->firstOrFail();
    
        if ($request->hasFile('file_pdf')) {
            $originalFileName = $request->file('file_pdf')->getClientOriginalName();
            $path = $request->file('file_pdf')->storeAs('public/pdfs', $originalFileName);
            $publicPath = 'storage/pdfs/' . $originalFileName;
        } else {
            return back()->withErrors(['file_pdf' => 'Please upload a valid image file.']);
        }
    
        $price = $productCetakfoto->price * $request->quantity;
    
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $productCetakfoto->id,
            'file_pdf' => $publicPath,
            'number_of_page' => 1,
            'quantity' => $request->quantity,
            'price' => $price,
        ]);
    
        return redirect()->back()->with('status', 'Item added to cart successfully.');
    }
    
    

    public function printoutAddToCart(Request $request) {
        
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:10240', // Example: Max 10MB PDF file
            'quantity' => 'required|integer|min:1',
            'product-size' => 'required', // Ensure product size ID exists in products table
            
            // 'choose-option' => 'required', // Ensure either laminating or jilid is selected
        ]);
        //PRODUCT Print Out
        $productPrintout = Product::where('category_id', function ($query) {
            $query->select('id')
                ->from('categories')
                ->where('name', 'printout');
        })
        ->where('size', $request->input('product-size'))
        ->where('color_type', $request->input('product-color'))
        ->firstOrFail();
        $additionalProduct = [];
        $idAddtional = null;
        if(!empty($request->input('choose-option'))){
            $idAddtional = ($request->input('choose-option') == 'jilid' ? $request->input('jilid-type') : $request->input('laminating-type'));
            // dd($idAddtional);
            $additionalProduct = Product::where('id', $idAddtional)
            ->firstOrFail();
        }
        
        if ($request->hasFile('file_pdf')) {
            // Get the original file name
            $originalFileName = $request->file('file_pdf')->getClientOriginalName();
    
            // Store the uploaded file in the public/pdfs directory
            $path = $request->file('file_pdf')->storeAs('public/pdfs', $originalFileName);
    
            // Get the public URL of the uploaded file
            $publicPath = 'storage/pdfs/' . $originalFileName;
    
            // Get the full path of the uploaded file for counting pages
            $filePath = storage_path('app/public/pdfs/' . $originalFileName);
    
            // Count the number of pages in the PDF
            $pageCount = PDFHelper::countPages($filePath);
            
            // Return the result to the view
            // return redirect()->back()->with('status', 'Item added to cart successfully.');
        } else {
            return back()->withErrors(['file_pdf' => 'Please upload a valid PDF file.']);
        }
        
        
        
        
        // Calculate price based on selected product, quantity, and additional service (laminating or jilid)
         // Base price calculation

        // Additional handling for laminating or jilid options
        if ($request->input('choose-option') === 'jilid') {
            // Handle jilid logic
            $price = $productPrintout->price * $request->quantity + $additionalProduct->price * $request->quantity;
        } elseif ($request->input('choose-option') === 'laminating') {
            $price = $productPrintout->price * $request->quantity + $additionalProduct->price * $request->quantity * $pageCount;
        } else{
            $price = $productPrintout->price * $pageCount * $request->quantity;
        }
       
        // Store the cart item in the database
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $productPrintout->id,
            'file_pdf' => $publicPath,
            //    http://127.0.0.1:8000/storage/pdfs/s.pdf
            'quantity' => $request->quantity,
            'number_of_page' => $pageCount,
            'additional_id' => $idAddtional,
            'price' => $price,
        ]);

        // Redirect back with success message or handle as needed
        return redirect()->back()->with('status', 'Item added to cart successfully.');
    }
    public function productAddToCart(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        $userId =  auth()->id();
        $price = $product->price * $quantity; // Calculate the price based on your requirements


       
            // Create a new cart entry
            Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);
     
        

        return redirect()->back()->with('status', 'Product added to cart successfully!');

    }
    public function index() {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        return view('users.features.cart.index', compact('cartItems'));
    }

    
    public function deleteCartItem($id) {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();
    
        return redirect()->route('cart.index')->with('status', 'Item removed from cart successfully.');
    }
}
