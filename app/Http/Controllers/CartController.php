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
    public function photocopyAddToSelected(Request $request) {
        // Validate the incoming request
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:10240', // Example: Max 10MB PDF file
            'product-size' => 'required', // Ensure product size ID exists in products table
            'product-color' => 'required', // Ensure product color ID exists in products table
            // 'choose-option' => 'required', // Ensure either laminating or jilid is selected
        ]);
    
        // Get the photocopy product based on the category, size, and color
        $productPhotoCopy = Product::where('category_id', function ($query) {
                $query->select('id')
                      ->from('categories')
                      ->where('name', 'photocopy');
            })
            ->where('size', $request->input('product-size'))
            ->where('color_type', $request->input('product-color'))
            ->firstOrFail();
    
        // Initialize additional product details if provided
        $additionalProduct = [];
        $idAdditional = null;
        
        if (!empty($request->input('choose-option'))) {
            $idAdditional = $request->input('choose-option') == 'jilid' 
                ? $request->input('jilid-type') 
                : $request->input('laminating-type');
            
            $additionalProduct = Product::where('id', $idAdditional)->firstOrFail();
        }
    
        if ($request->hasFile('file_pdf')) {
            // Get the original file name without the extension
            $originalFileName = pathinfo($request->file('file_pdf')->getClientOriginalName(), PATHINFO_FILENAME);
        
            // Limit the length of the original file name to ensure $publicPath doesn't exceed 100 characters
            $truncatedFileName = substr($originalFileName, 0, 50); // Truncate to 50 characters to leave room for the rest of the path
        
            // Generate a unique ID and append it to the truncated file name
            $uniqueId = uniqid();
        
            // Concatenate the truncated file name, unique ID, and extension
            $fileName = $truncatedFileName . '_' . $uniqueId . '.pdf';
        
            // Store the file in the 'public/pdfs' directory with the generated name
            $path = $request->file('file_pdf')->storeAs('public/pdfs', $fileName);
        
            // Generate the public path, ensuring it doesn't exceed 100 characters
            $publicPath = 'storage/pdfs/' . $fileName;
        
            // Ensure $publicPath doesn't exceed 100 characters
            if (strlen($publicPath) > 100) {
                return back()->withErrors(['file_pdf' => 'The file path is too long. Please rename the file to a shorter name.']);
            }
        
            // Get the full path of the uploaded file for counting pages
            $filePath = storage_path('app/public/pdfs/' . $fileName);
        
            // Count the number of pages in the PDF
            $pageCount = PDFHelper::countPages($filePath);
        
        } else {
            return back()->withErrors(['file_pdf' => 'Please upload a valid PDF file.']);
        }
        
    
        // Store the cart item in the database and capture the cart instance
        $cart = Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $productPhotoCopy->id,
            'file_pdf' => $publicPath,
            'number_of_page' => $pageCount,
            'additional_id' => $idAdditional,
        ]);
    
        // Redirect to the 'photocopyChoose' route with the cart ID
        return redirect()->route('photocopyChoose', $cart->id)->with('status', 'Item added to cart successfully, now fill the form bellow');
    }

    public function photocopyChoose($cartId){
        $cart = Cart::with('product')->find($cartId);
        return view('users.features.choosePage', compact('cart'));
    }
    public function photocopyChoosed(Request $request, $cartId) {
        // Get selected pages as an array
        $selectedPages = $request->input('selected_pages', []);
        $numberOfSelectedPages = count($selectedPages);
        $cart = Cart::with('product')->find($cartId);
        
        if (!$cart) {
            // Handle the case where the cart was not found
            return redirect()->route('cart.index')->with('error', 'Cart not found for ID: ' . $cartId);
        }
        
        // Calculate the price
        $additionalProduct = $cart->additionalProduct;
        $product = $cart->product;
        if ($additionalProduct && $additionalProduct->category) {
            if ($additionalProduct->category->name === 'jilid') {
                $price = $product->price * $request->quantity * $numberOfSelectedPages + $additionalProduct->price * $request->quantity;
            } elseif ($additionalProduct->category->name === 'laminating') {
                $price = $product->price * $request->quantity * $numberOfSelectedPages + $additionalProduct->price * $request->quantity * $numberOfSelectedPages;
            } else {
                $price = $product->price * $numberOfSelectedPages * $request->quantity;
            }
        } else {
            $price = $product->price * $numberOfSelectedPages * $request->quantity;
        }
    
        // Update the cart
        $cart->quantity = $request->quantity; // Update quantity
        $cart->number_of_page = $numberOfSelectedPages;
        $cart->selected_number_of_page = implode(',', $selectedPages); // Convert array to comma-separated string
        $cart->price = $price; // Set price
        $cart->save(); // Save changes
    
        // Redirect with success message
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    
        // //penjumlahan ada berapoa array
        // $numberOfSelectedPages = count($selectedPages);
        // //membuat objek array ke string
        // $selectedPagesString = implode(' ', $selectedPages);
        // dd($numberOfSelectedPages);
        // // // Output for display
        // $outputMessage = 'Halaman yang dipilih adalah ' . $selectedPagesString;
        // dd($outputMessage);
    }


    public function printoutAddToSelected(Request $request) {
        // Validate the incoming request
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:10240', // Example: Max 10MB PDF file
            'product-size' => 'required', // Ensure product size ID exists in products table
            'product-color' => 'required', // Ensure product color ID exists in products table
            // 'choose-option' => 'required', // Ensure either laminating or jilid is selected
        ]);
    
        // Get the photocopy product based on the category, size, and color
        $productPrintout = Product::where('category_id', function ($query) {
                $query->select('id')
                      ->from('categories')
                      ->where('name', 'printout');
            })
            ->where('size', $request->input('product-size'))
            ->where('color_type', $request->input('product-color'))
            ->firstOrFail();
    
        // Initialize additional product details if provided
        $additionalProduct = [];
        $idAdditional = null;
        
        if (!empty($request->input('choose-option'))) {
            $idAdditional = $request->input('choose-option') == 'jilid' 
                ? $request->input('jilid-type') 
                : $request->input('laminating-type');
            
            $additionalProduct = Product::where('id', $idAdditional)->firstOrFail();
        }
    
        if ($request->hasFile('file_pdf')) {
            // Get the original file name without the extension
            $originalFileName = pathinfo($request->file('file_pdf')->getClientOriginalName(), PATHINFO_FILENAME);
        
            // Limit the length of the original file name to ensure $publicPath doesn't exceed 100 characters
            $truncatedFileName = substr($originalFileName, 0, 50); // Truncate to 50 characters to leave room for the rest of the path
        
            // Generate a unique ID and append it to the truncated file name
            $uniqueId = uniqid();
        
            // Concatenate the truncated file name, unique ID, and extension
            $fileName = $truncatedFileName . '_' . $uniqueId . '.pdf';
        
            // Store the file in the 'public/pdfs' directory with the generated name
            $path = $request->file('file_pdf')->storeAs('public/pdfs', $fileName);
        
            // Generate the public path, ensuring it doesn't exceed 100 characters
            $publicPath = 'storage/pdfs/' . $fileName;
        
            // Ensure $publicPath doesn't exceed 100 characters
            if (strlen($publicPath) > 100) {
                return back()->withErrors(['file_pdf' => 'The file path is too long. Please rename the file to a shorter name.']);
            }
        
            // Get the full path of the uploaded file for counting pages
            $filePath = storage_path('app/public/pdfs/' . $fileName);
        
            // Count the number of pages in the PDF
            $pageCount = PDFHelper::countPages($filePath);
        
        } else {
            return back()->withErrors(['file_pdf' => 'Please upload a valid PDF file.']);
        }
        
    
        // Store the cart item in the database and capture the cart instance
        $cart = Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $productPrintout->id,
            'file_pdf' => $publicPath,
            'number_of_page' => $pageCount,
            'additional_id' => $idAdditional,
        ]);
    
        // Redirect to the 'photocopyChoose' route with the cart ID
        return redirect()->route('printoutChoose', $cart->id)->with('status', 'Item added to cart successfully, now fill the form bellow');
    }
    public function printoutChoose($cartId){
        $cart = Cart::with('product')->find($cartId);
        return view('users.features.choosePage', compact('cart'));
    }

    public function printoutChoosed(Request $request, $cartId) {
        // Get selected pages as an array
        $selectedPages = $request->input('selected_pages', []);
        $numberOfSelectedPages = count($selectedPages);
        $cart = Cart::with('product')->find($cartId);
        
        if (!$cart) {
            // Handle the case where the cart was not found
            return redirect()->route('cart.index')->with('error', 'Cart not found for ID: ' . $cartId);
        }
        
        // Calculate the price
        $additionalProduct = $cart->additionalProduct;
        $product = $cart->product;
        if ($additionalProduct && $additionalProduct->category) {
            if ($additionalProduct->category->name === 'jilid') {
                $price = $product->price * $request->quantity * $numberOfSelectedPages + $additionalProduct->price * $request->quantity;
            } elseif ($additionalProduct->category->name === 'laminating') {
                $price = $product->price * $request->quantity * $numberOfSelectedPages + $additionalProduct->price * $request->quantity * $numberOfSelectedPages;
            } else {
                $price = $product->price * $numberOfSelectedPages * $request->quantity;
            }
        } else {
            $price = $product->price * $numberOfSelectedPages * $request->quantity;
        }
    
        // Update the cart
        $cart->quantity = $request->quantity; // Update quantity
        $cart->number_of_page = $numberOfSelectedPages;
        $cart->selected_number_of_page = implode(',', $selectedPages); // Convert array to comma-separated string
        $cart->price = $price; // Set price
        $cart->save(); // Save changes
    
        // Redirect with success message
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    
        
    }



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

    public function printoutCancel(Request $request, $id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $cart->delete();
            return redirect()->route('printout.add')->with('status', 'Cart deleted successfully!');
        } 
    }
    public function photocopyCancel(Request $request, $id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $cart->delete();
            return redirect()->route('photocopy.add')->with('status', 'Cart deleted successfully!');
        } 
    }

    
    public function deleteCartItem($id) {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();
    
        return redirect()->route('cart.index')->with('status', 'Item removed from cart successfully.');
    }
}
