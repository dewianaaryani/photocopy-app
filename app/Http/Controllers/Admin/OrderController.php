<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->input('search');

        // Fetch orders with user relation, filtered if search is provided, and order by latest
        $orders = Order::with('user') // Eager load the related User model
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%"); // Search based on user name
                })
                ->orWhere('order_status', 'like', "%{$search}%")
                ->orWhere('payment_status', 'like', "%{$search}%")
                ->orWhere('total_price', 'like', "%{$search}%")
                ->orWhere('created_at', 'like', "%{$search}%");
            })
            ->latest() // Order by the latest (created_at)
            ->get();

        return view('admin.features.order.index', compact('orders'));
    }
    public function view($id){
        
        // Mengambil order berdasarkan ID
        $order = Order::findOrFail($id);
        $user = User::findOrFail($order->user_id);
        // Mengambil semua item yang terkait dengan order ini
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        $subTotal = $orderItems->sum('price');
        $shipping = $order->total_price - $subTotal - 2000;
        
       
        return view('admin.features.order.show', compact('order', 'orderItems', 'user', 'subTotal', 'shipping'));
    }
    public function pembayaranDiterima($id){
        $order = Order::findOrFail($id);
        $order->payment_status = 2;
        $order->order_status = 1;
        $order->save();
        return redirect()->back()->with('status', 'payment accept successfully.');
    }
    // public function pesananJadi(Request $request, $id) {
    //     $order = Order::findOrFail($id);
        
    //     // Validate the track link if needed
    //     $request->validate([
    //         'track_link' => 'required|url',
    //     ]);
    
    //     // Save the track link if necessary
    //     $order->track_link = $request->input('track_link');
    //     $order->order_status = 2;
    //     $order->save();
        
    //     return redirect()->back()->with('status', 'Order updated successfully, ready to pickup!');
    // }
    public function pesananJadi(Request $request, $id) {
        $order = Order::findOrFail($id);
    
        // Check if track_link input is present
        if ($request->has('track_link')) {
            // Validate the track link
            $request->validate([
                'track_link' => 'required|url',
            ]);
    
            // Save the track link
            $order->track_link = $request->input('track_link');
        }
    
        // Update the order status
        $order->order_status = 2;
        $order->save();
    
        return redirect()->back()->with('status', 'Order updated successfully, ready to pickup!');
    }
    public function pesananSelesai($id){
        $order = Order::findOrFail($id);
        
        $order->order_status = 3;
        $order->save();
        return redirect()->back()->with('status', 'orders is a wrap!!');
    }
}
