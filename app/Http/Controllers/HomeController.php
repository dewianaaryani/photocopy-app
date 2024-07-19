<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalOrders = Order::where('user_id', auth()->id())->count();
        $pendingOrders = Order::where('user_id', auth()->id())->where('order_status', 0)->count();
        $unpaidOrders = Order::where('user_id', auth()->id())->where('payment_status', 0)->count();
        $cartItems = Cart::where('user_id', auth()->id())->count(); // Assuming you have a Cart model

        
        
        return view('users.dashboard.index', compact('totalOrders', 'pendingOrders', 'unpaidOrders', 'cartItems'));
    } 
    public function adminHome()
    {   
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 0)->count();
        $unpaidOrders = Order::where('payment_status', 0)->count();
        $cartItems = Cart::count(); // Assuming you have a Cart model
        return view('admin.dashboard.index', compact('totalOrders', 'pendingOrders', 'unpaidOrders', 'cartItems'));
    }
}
