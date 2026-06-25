<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceReport;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
            return [
                new Middleware('permission:view sale_details', only:['index'])
            ];
        }
    /**
     * Display sales report
     */
    public function index(Request $request)
    {
        $reports = InvoiceReport::query()

            // Search Customer or Employee
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('customer_name', 'like', '%' . $request->search . '%')
                      ->orWhere('employee_name', 'like', '%' . $request->search . '%');
                });
            })

            // Filter Date
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })

            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Summary Cards
        $totalSales = InvoiceReport::sum('grand_total');
        $totalOrders = InvoiceReport::count();

        $topEmployee = InvoiceReport::selectRaw(
                'employee_name, SUM(grand_total) as total_sales'
            )
            ->groupBy('employee_name')
            ->orderByDesc('total_sales')
            ->first();
        
        $todaySales = InvoiceReport::whereDate('created_at', today())
            ->sum('grand_total');
        $todayOrders = InvoiceReport::whereDate('created_at', today())
            ->count();
        $thisMonthSales = InvoiceReport::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('grand_total');
        $thisMonthOrders = InvoiceReport::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $averageOrder = $totalOrders > 0? $totalSales / $totalOrders: 0;

        return view('reports.index', compact(
            'reports',
            'totalSales',
            'totalOrders',
            'topEmployee',
            'todaySales',
            'todayOrders',
            'thisMonthSales',
            'thisMonthOrders',
            'averageOrder'
        ));
    }
}