<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of user's orders
     */
    public function index()
    {
        $orders = auth()->user()->orders()->orderBy('created_at', 'desc')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the checkout page
     */
    public function checkout()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('menuItem')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }
        
        $total = $cartItems->sum(fn($item) => $item->menuItem->price * $item->quantity);
        
        return view('orders.checkout', compact('cartItems', 'total'));
    }

    /**
     * Place a new order
     */
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'delivery_type' => 'required|in:pickup,delivery',
            'delivery_address' => 'nullable|required_if:delivery_type,delivery|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = Cart::where('user_id', auth()->id())->with('menuItem')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Calculate total
        $totalAmount = $cartItems->sum(fn($item) => $item->menuItem->price * $item->quantity);

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'delivery_type' => $validated['delivery_type'],
            'delivery_address' => $validated['delivery_address'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create order items and decrease stock
        foreach ($cartItems as $cartItem) {
            $menuItem = $cartItem->menuItem;

            if (!$menuItem->is_available) {
                return redirect()->route('cart.index')->with('error', "{$menuItem->name} is currently unavailable.");
            }
            
            // Check if enough stock is available
            if ($menuItem->stock < $cartItem->quantity) {
                return redirect()->route('cart.index')->with('error', "Insufficient stock for {$menuItem->name}. Only {$menuItem->stock} available.");
            }
            
            // Decrease stock
            $menuItem->decrement('stock', $cartItem->quantity);
            
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $cartItem->menu_item_id,
                'item_name' => $menuItem->name,
                'quantity' => $cartItem->quantity,
                'price' => $menuItem->price,
            ]);
        }

        // Clear cart
        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with('items.menuItem')->findOrFail($id);

        // Ensure user can only view their own orders, unless they're admin
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        return view('orders.show', compact('order'));
    }
}
