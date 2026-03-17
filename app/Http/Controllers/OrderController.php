<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\OrderTracking;
use App\Services\Courier\CourierDispatchService;
use App\Services\StripeService;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
            'delivery_type' => 'required|in:pickup,delivery,fasttrack,gometrix',
            'delivery_address' => 'nullable|required_if:delivery_type,delivery,fasttrack,gometrix|string|max:255',
            'delivery_lat' => 'nullable|required_with:delivery_lng|numeric|between:-90,90',
            'delivery_lng' => 'nullable|required_with:delivery_lat|numeric|between:-180,180',
            'payment_method' => 'required|in:cash,card,xendit',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validated['payment_method'] === 'xendit' && $validated['delivery_type'] !== 'gometrix') {
            throw ValidationException::withMessages([
                'payment_method' => 'Pay on Xendit is available only when GoMetrix delivery is selected.',
            ]);
        }

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
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_method'] === 'cash' ? 'paid' : 'unpaid',
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

        // Create initial tracking row. If this is a delivery order, try to geocode address.
        $trackingPayload = [
            'order_id' => $order->id,
            'status' => 'pending',
            'eta' => null,
            'lat' => null,
            'lng' => null,
        ];

        if (isset($validated['delivery_lat'], $validated['delivery_lng'])) {
            $trackingPayload['lat'] = (float) $validated['delivery_lat'];
            $trackingPayload['lng'] = (float) $validated['delivery_lng'];
        } elseif (in_array($order->delivery_type, ['delivery', 'fasttrack', 'gometrix'], true) && ! empty($order->delivery_address)) {
            try {
                $geocode = $this->geocodeWithNominatim($order->delivery_address);
                if ($geocode) {
                    $trackingPayload['lat'] = $geocode['lat'];
                    $trackingPayload['lng'] = $geocode['lng'];
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to geocode delivery address', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        OrderTracking::create($trackingPayload);

        $integrationWarning = null;
        if (in_array($order->delivery_type, ['fasttrack', 'gometrix'], true)) {
            $dispatchResult = app(CourierDispatchService::class)->dispatchOrder($order);

            if (! (bool) ($dispatchResult['success'] ?? false)) {
                $providerLabel = $order->delivery_type === 'gometrix' ? 'GoMetrix' : 'FastTrack';
                $integrationWarning = 'Order saved, but ' . $providerLabel . ' dispatch is pending: ' . ($dispatchResult['message'] ?? 'Unknown integration error.');
            }
        }

        // Card payments are redirected to Stripe Checkout.
        if ($validated['payment_method'] === 'card') {
            try {
                $session = app(StripeService::class)->createCheckoutSession(
                    $order,
                    route('orders.payment.success', ['id' => $order->id]),
                    route('orders.payment.cancel', ['id' => $order->id])
                );

                $order->update(['stripe_session_id' => $session['id']]);

                if ($integrationWarning) {
                    session()->flash('warning', $integrationWarning);
                }

                return redirect()->away($session['url']);
            } catch (\Throwable $e) {
                Log::error('Stripe checkout session creation failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);

                return redirect()->route('orders.show', $order->id)
                    ->with('error', 'Order created, but card payment setup failed. Please contact support or choose cash next time.');
            }
        }

        if ($validated['payment_method'] === 'xendit') {
            $invoiceUrl = (string) ($dispatchResult['data']['xendit_invoice_url'] ?? '');

            if ($invoiceUrl !== '') {
                if ($integrationWarning) {
                    session()->flash('warning', $integrationWarning);
                }

                return redirect()->away($invoiceUrl);
            }

            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Order was sent to GoMetrix, but the Xendit payment link was not created. Please try again later.');
        }

        // Send best-effort SMS for immediate cash orders.
        try {
            app(TwilioService::class)->sendOrderStatus(auth()->user(), $order, 'Pending');
        } catch (\Throwable $e) {
            Log::warning('Failed to send order SMS', ['order_id' => $order->id, 'error' => $e->getMessage()]);
        }

        $redirect = redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');

        if ($integrationWarning) {
            $redirect->with('warning', $integrationWarning);
        }

        return $redirect;
    }

    /**
     * @return array{lat: float, lng: float}|null
     */
    private function geocodeWithNominatim(string $address): ?array
    {
        $response = Http::timeout(8)
            ->withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'CafeManagement/1.0 (integration geocoder)',
            ])
            ->get('https://nominatim.openstreetmap.org/search', [
                'q' => $address,
                'format' => 'json',
                'limit' => 1,
            ]);

        if (! $response->successful()) {
            return null;
        }

        $results = $response->json();
        $topResult = is_array($results) ? ($results[0] ?? null) : null;

        if (! is_array($topResult) || ! isset($topResult['lat'], $topResult['lon'])) {
            return null;
        }

        return [
            'lat' => (float) $topResult['lat'],
            'lng' => (float) $topResult['lon'],
        ];
    }

    public function paymentSuccess(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $sessionId = (string) $request->query('session_id');

        if ($sessionId !== '' && $order->stripe_session_id === $sessionId) {
            $session = app(StripeService::class)->retrieveCheckoutSession($sessionId);

            if (Arr::get($session, 'payment_status') === 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'stripe_payment_intent_id' => Arr::get($session, 'payment_intent'),
                ]);

                try {
                    app(TwilioService::class)->sendOrderStatus(auth()->user(), $order, 'Confirmed');
                } catch (\Throwable $e) {
                    Log::warning('Failed to send payment confirmation SMS', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                return redirect()->route('orders.show', $order->id)
                    ->with('success', 'Payment received. Your order is now confirmed.');
            }
        }

        return redirect()->route('orders.show', $order->id)
            ->with('error', 'Payment was not confirmed yet.');
    }

    public function paymentCancel($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        return redirect()->route('orders.show', $order->id)
            ->with('error', 'Card payment was cancelled. You can retry from your order details.');
    }

    public function retryCardPayment($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($order->payment_method !== 'card') {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'This order was not created with card payment.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'This order is already paid.');
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Cancelled orders cannot be paid.');
        }

        try {
            $session = app(StripeService::class)->createCheckoutSession(
                $order,
                route('orders.payment.success', ['id' => $order->id]),
                route('orders.payment.cancel', ['id' => $order->id])
            );

            $order->update(['stripe_session_id' => $session['id']]);

            return redirect()->away($session['url']);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout session retry failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Unable to retry card payment right now. Please try again later.');
        }
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
