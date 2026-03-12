<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\MenuItem;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $cartItems = auth()->user()->carts()->with('menuItem')->get();
        $total = $cartItems->sum(function ($cartItem) {
            return $cartItem->menuItem->price * $cartItem->quantity;
        });
        
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    public function add(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'special_instructions' => 'nullable|string|max:500'
        ]);
        
        $menuItem = MenuItem::findOrFail($itemId);
        $requestedQty = $request->quantity ?? 1;

        if (!$menuItem->is_available) {
            return redirect()->back()->with('error', 'This item is currently unavailable.');
        }

        if ($menuItem->stock < $requestedQty) {
            return redirect()->back()->with('error', "Insufficient stock for {$menuItem->name}.");
        }
        
        // Check if item already in cart
        $cartItem = Cart::where('user_id', auth()->id())
                       ->where('menu_item_id', $itemId)
                       ->first();
        
        if ($cartItem) {
            $newQty = $cartItem->quantity + $requestedQty;
            if ($menuItem->stock < $newQty) {
                return redirect()->back()->with('error', "Only {$menuItem->stock} in stock for {$menuItem->name}.");
            }

            $cartItem->quantity = $newQty;
            $cartItem->special_instructions = $request->special_instructions;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'menu_item_id' => $itemId,
                'quantity' => $requestedQty,
                'special_instructions' => $request->special_instructions
            ]);
        }
        
        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cartItem = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);
        
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully'
        ]);
    }
    
    public function remove($id)
    {
        $cartItem = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cartItem->delete();
        
        return redirect()->back()->with('success', 'Item removed from cart!');
    }
    
    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();
        
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }
}