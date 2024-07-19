<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
class OrderController extends Controller
{
    public function index(){
        $orders = Order::latest()->get();
   
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
    public function pesananJadi($id){
        $order = Order::findOrFail($id);
        
        $order->order_status = 2;
        $order->save();
        return redirect()->back()->with('status', 'orders create successfully, ready to pickup!');
    }
    public function pesananSelesai($id){
        $order = Order::findOrFail($id);
        
        $order->order_status = 3;
        $order->save();
        return redirect()->back()->with('status', 'orders is a wrap!!');
    }
}
