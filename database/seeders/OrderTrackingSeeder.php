<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = \App\Models\Order::take(3)->get();
        $statuses = ['Preparing', 'On the way', 'Delivered'];
        foreach ($orders as $i => $order) {
            \App\Models\OrderTracking::create([
                'order_id' => $order->id,
                'status' => $statuses[$i % 3],
                'eta' => ($statuses[$i % 3] === 'Delivered') ? null : rand(10, 30) . ' mins',
                'lat' => 40.7128 + $i * 0.01,
                'lng' => -74.0060 + $i * 0.01,
            ]);
        }
    }
}
