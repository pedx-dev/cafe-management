<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\MenuItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    
    public function index()
    {
        $statusCounts = Order::select('status')
            ->selectRaw('count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', Carbon::today())->count(),
            'revenue_today' => Order::whereDate('created_at', Carbon::today())->sum('total_amount'),
            'revenue_month' => Order::whereMonth('created_at', Carbon::now()->month)->sum('total_amount'),
            'revenue_total' => Order::sum('total_amount'),
            'month_orders' => Order::whereMonth('created_at', Carbon::now()->month)->count(),
            'low_stock_items' => MenuItem::where('stock', '<', 5)->count(),
            'out_of_stock_items' => MenuItem::where('stock', '<=', 0)->count(),
            'menu_items_total' => MenuItem::count(),
            'pending_orders' => $statusCounts['pending'] ?? 0,
            'delivered_orders' => $statusCounts['delivered'] ?? 0,
        ];
        
        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $topItems = MenuItem::orderBy('created_at', 'desc')->take(5)->get();
        
        // Chart data
        $ordersByDay = $this->getOrdersByDay();
        
        return view('admin.dashboard', compact('stats', 'recentOrders', 'topItems', 'ordersByDay', 'statusCounts'));
    }

    public function exportCsv()
    {
        $data = $this->buildReportData();
        $filename = 'dashboard-report-' . Carbon::now()->format('Y-m-d') . '.csv';

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Metric', 'Value']);
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportWord()
    {
        $data = $this->buildReportData();
        $title = 'Dashboard Report - ' . Carbon::now()->format('F j, Y');
        $filename = 'dashboard-report-' . Carbon::now()->format('Y-m-d') . '.doc';

        $rows = '';
        foreach ($data as $row) {
            $rows .= '<tr><td style="padding:8px;border:1px solid #ddd;">' . e($row[0]) . '</td>' .
                '<td style="padding:8px;border:1px solid #ddd;">' . e($row[1]) . '</td></tr>';
        }

        $html = '<html><head><meta charset="utf-8"><title>' . e($title) . '</title></head><body>' .
            '<h2 style="font-family: Arial, sans-serif;">' . e($title) . '</h2>' .
            '<table style="border-collapse:collapse;width:100%;font-family: Arial, sans-serif;">' .
            '<thead><tr><th style="text-align:left;padding:8px;border:1px solid #ddd;">Metric</th>' .
            '<th style="text-align:left;padding:8px;border:1px solid #ddd;">Value</th></tr></thead>' .
            '<tbody>' . $rows . '</tbody></table></body></html>';

        return Response::make($html, 200, [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function buildReportData(): array
    {
        $statusCounts = Order::select('status')
            ->selectRaw('count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            ['Date', Carbon::now()->format('F j, Y')],
            ['Total Customers', (string) User::where('role', 'user')->count()],
            ['Total Orders', (string) Order::count()],
            ['Orders Today', (string) Order::whereDate('created_at', Carbon::today())->count()],
            ['Orders This Month', (string) Order::whereMonth('created_at', Carbon::now()->month)->count()],
            ['Pending Orders', (string) ($statusCounts['pending'] ?? 0)],
            ['Delivered Orders', (string) ($statusCounts['delivered'] ?? 0)],
            ['Revenue Today', 'PHP ' . number_format((float) Order::whereDate('created_at', Carbon::today())->sum('total_amount'), 2)],
            ['Revenue This Month', 'PHP ' . number_format((float) Order::whereMonth('created_at', Carbon::now()->month)->sum('total_amount'), 2)],
            ['Total Revenue', 'PHP ' . number_format((float) Order::sum('total_amount'), 2)],
            ['Menu Items Total', (string) MenuItem::count()],
            ['Low Stock Items (<5)', (string) MenuItem::where('stock', '<', 5)->count()],
            ['Out of Stock Items', (string) MenuItem::where('stock', '<=', 0)->count()],
        ];
    }
    
    private function getOrdersByDay()
    {
        $orders = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $orders[$date->format('D')] = Order::whereDate('created_at', $date)->count();
        }
        return $orders;
    }
}