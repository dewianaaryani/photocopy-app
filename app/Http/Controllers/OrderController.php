<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class OrderController extends Controller
{
    // public function checkout(Request $request) {
    //     $cartItems = Cart::with('product')->get();
    //     $totalPrice = $cartItems->sum(function($cartItem) {
    //         return $cartItem->product->price * $cartItem->quantity;
    //     });
    
    //     $order = new Order();
    //     $order->total_price = $totalPrice;
    //     $order->save();
    
    //     foreach ($cartItems as $cartItem) {
    //         $orderItem = new OrderItem();
    //         $orderItem->order_id = $order->id;
    //         $orderItem->product_id = $cartItem->product_id;
    //         $orderItem->quantity = $cartItem->quantity;
    //         $orderItem->price = $cartItem->product->price;
    //         $orderItem->save();
    //     }
    
    //     Cart::truncate();
    
    //     return redirect('/products')->with('success', 'Order placed successfully!');
    // }

    public function checkoutForm(Request $request)
    {
        $selectedItems = $request->input('selected_items', []); // Retrieve selected_items as an array

        // Check if selected items are empty
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout.');
        }

        $userCartItems = Cart::whereIn('id', $selectedItems)
                    ->where('user_id', auth()->id())
                    ->get();
        $totalPrice = $userCartItems->sum('price');

        //if the type order is 1(delivery)
        
        $origin = [
            'lat' => -6.6487692,
            'lng' => 106.8374509,
            
            
        ];
        
        $originAddress = 'Toko Dewiana, Bogor'; // Replace with your actual origin address
        $apiKey = env('GOOGLE_MAPS_API_KEY');
    
        return view('users.features.cart.checkout', compact('origin', 'originAddress', 'apiKey', 'userCartItems'));
        
    }
    // public function checkout(Request $request) {
        
    //     dd($request->items);
    //     $cartItems = $request->input('items', []);

    //     // Fetch cart items from database
    //     $cartItemIds = collect($cartItems)->pluck('id')->toArray();

    // // Fetch cart items from database
    //     $cartItemCheckout = Cart::whereIn('id', $cartItemIds)
    //                         ->where('user_id', auth()->id())
    //                         ->get();
      
    
    //     // Create a new order and save total price
    //     $order = new Order();
    //     // $order->user_id = auth()->id(); // Assign the authenticated user's ID
    //     // //if 0 = pickup, 1 = delivery
    //     // $order->type_delivery
    //     // //longitude dan latitude lokasi pengantaran, apabila pickup = null
    //     // $order->lt
    //     // $order->ld
    //     // //jarak km dari lokasi awal ke lokasi pengantaran
    //     // $order->distance
    //     // $order->notes
    //     // $order->total_price =
    //     // $order->save();
    
    //     // Prepare selected items for order items
    //     foreach ($userCartItems as $userCartItem) {
    //         $orderItem = new OrderItem();
    //         $orderItem->order_id = $order->id;
    //         $orderItem->product_id = $userCartItem->product_id; // Assuming this is how product_id is accessed
    //         $orderItem->file_pdf = $userCartItem->file_pdf; // Assuming this is how file_pdf is accessed
    //         $orderItem->quantity = $userCartItem->quantity;
    //         $orderItem->number_of_page = $userCartItem->number_of_page;
    //         $orderItem->additional_id = $userCartItem->additional_id;
    //         $orderItem->price = $userCartItem->price;
    //         $orderItem->save();
    //     }
    
    //     Cart::whereIn('id', $selectedItems)
    //         ->where('user_id', auth()->id())
    //         ->delete();
    
    //         return redirect()->route('orders.show', $order->id)->with('status', 'Checkout successful.');
    // }
    public function checkout(Request $request)
    {
        // Validate the request
        $request->validate([
            'deliveryOption' => 'required|in:pickup,delivery',
            'destination' => 'required_if:deliveryOption,delivery',
            'distance_input' => 'nullable|numeric|min:1',
            'notes' => 'nullable|string',
            'total_price' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.additional_id' => 'nullable|exists:products,id',
            'items.*.file_pdf' => 'nullable|string', // Adjust as per your validation rules
            'items.*.number_of_page' => 'nullable|integer|min:1', // Adjust as per your validation rules
            'items.*.quantity' => 'required|integer|min:1', // Adjust as per your validation rules
            'items.*.price' => 'required|numeric|min:0', // Adjust as per your validation rules
            'items.*.id' => 'required|exists:carts,id',
        ]);
       
       
        // Process the order
        $order = new Order();
        $order->user_id = auth()->id();
        $order->type_delivery = $request->deliveryOption;
        $order->destination = $request->destination ?? null;
        $order->distance = $request->distance_input ?? null; // Only set if delivery option is 'delivery'
        $order->notes = $request->notes ?? null; // Only set if delivery option is 'delivery'
        $order->total_price = $request->total_price; // Other order details as needed
        
        // Save the order
        $order->save();

        // Process order items
        foreach ($request->items as $itemData) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $itemData['product_id'];
            $orderItem->additional_id = $itemData['additional_id'] ?? null;
            $orderItem->file_pdf = $itemData['file_pdf'];
            $orderItem->number_of_page = $itemData['number_of_page'];
            $orderItem->quantity = $itemData['quantity'];
            $orderItem->price = $itemData['price'];
            // Other item details as needed

            $orderItem->save();
        }
        $itemIds = collect($request->items)->pluck('id')->toArray();
        
        // Lakukan penghapusan dengan whereIn
        Cart::whereIn('id', $itemIds)
            ->where('user_id', auth()->id())
            ->delete();
        // Optionally, you might want to send a confirmation email, update inventory, etc.

        // Redirect or respond with success message
        return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');
    }
    public function view($id){
        $user = Auth::user();
        // Mengambil order berdasarkan ID
        $order = Order::findOrFail($id);

        // Mengambil semua item yang terkait dengan order ini
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        $subTotal = $orderItems->sum('price');
        $shipping = $order->total_price - $subTotal - 2000;
        return view('users.features.order.show', compact('order', 'orderItems','user', 'subTotal', 'shipping'));
    }
    public function index(Request $request){
        $search = $request->input('search');

        $orders = Order::query()
            ->where('id', 'LIKE', "%{$search}%")
            ->orWhere('created_at', 'LIKE', "%{$search}%")
            ->orWhere('order_status', 'LIKE', "%{$search}%")
            ->orWhere('payment_status', 'LIKE', "%{$search}%")
            ->orWhere('total_price', 'LIKE', "%{$search}%")
            ->latest()->get();
    
        return view('users.features.order.index', compact('orders'));
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $request->validate([
            'payment_prove' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $order = Order::findOrFail($id);

        if ($request->hasFile('payment_prove')) {
            // Store the new payment proof
            $filePath = $request->file('payment_prove')->store('payment_proves', 'public');

            // Delete the old payment proof if exists
            if ($order->payment_prove) {
                Storage::disk('public')->delete($order->payment_prove);
            }
        
            // Update the order with the new payment proof
            $order->payment_status = 1;
            $order->payment_prove = $filePath;
            $order->save();
        }

        return redirect()->route('orders.show', $order->id)->with('status', 'Payment proof uploaded successfully!');
    }
}
