<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Invoices;
use App\Models\Invoice_Details;
use App\Models\Products;

class DashboardControllers extends Controller
{
    public function dashboard(Request $request)
    {
        // FILTER ORDERS
        $query = Invoices::with(['customer', 'details']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('id', $search);
        }
        // Date Filter
        $filter = $request->get('filter', 'today');
        if ($filter == 'today') {
            $query->whereDate('created_at', today());
        } elseif ($filter == 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter == 'month') {
            $query->whereMonth('created_at', now()->month);
        }

        $invoices = $query->latest()->get();

        // Revenue today
        $revenue = Invoice_Details::join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->when($filter == 'today', fn($q) => $q->whereDate('invoices.created_at', today()))
            ->when($filter == 'week', fn($q) => $q->whereBetween('invoices.created_at', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($filter == 'month', fn($q) => $q->whereMonth('invoices.created_at', now()->month))
            ->sum('invoice_details.total');

        // Top products
        $topProducts = Invoice_Details::join('products', 'invoice_details.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(invoice_details.quantity) as total_qty'))
            ->groupBy('products.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Weekly sales chart
        $salesData = Invoice_Details::join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
            ->selectRaw('DAYNAME(invoices.created_at) as day, SUM(invoice_details.total) as total')
            ->whereBetween('invoices.created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $chartData = [];
        foreach ($days as $day) {
            $chartData[] = $salesData[$day] ?? 0;
        }

        // Low stock products
        $lowStockProducts = Products::where('quantity', '<=', 10)->get();

        return view('dashboard', compact(
            'invoices',
            'revenue',
            'topProducts',
            'days',
            'chartData',
            'lowStockProducts'
        ));
    }
}