<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function show($orderId)
    {
        $tracking = \App\Models\OrderTracking::where('order_id', $orderId)->first();
        if (!$tracking) {
            return response()->json(['error' => 'Tracking not found'], 404);
        }
        return response()->json([
            'status' => $tracking->status,
            'eta' => $tracking->eta,
            'location' => [
                'lat' => $tracking->lat,
                'lng' => $tracking->lng
            ]
        ]);
    }
}
