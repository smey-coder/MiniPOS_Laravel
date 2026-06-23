<?php

namespace App\Http\Controllers;

use App\Models\Purchase_Detail;
use App\Models\Purchase;
use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PurchaseDetailController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
            return [
                new Middleware('permission:view purchase_detail', only:['index']),
                new Middleware('permission:edit purchase_detail', only:['edit']),
                new Middleware('permission:update purchase_detail', only:['update']),
                new Middleware('permission:create purchase_detail', only:['create']),
                new Middleware('permission:delete purchase_detail', only:['destroy']),
            ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $search = $request->search;
            $supplierId = $request->supplier_id;
            $employeeId = $request->employee_id;
            $status = $request->status;
            $fromDate = $request->from_date;
            $toDate = $request->to_date;

            $purchaseDetails = Purchase_Detail::with([
                'product',
                'purchase.supplier',
                'purchase.employee'
            ])

            // Search
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    // Product
                    $q->whereHas('product', function ($sub) use ($search) {

                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('barcode', 'like', "%{$search}%");

                    })

                    // Purchase
                    ->orWhereHas('purchase', function ($sub) use ($search) {

                        $sub->where('purchase_code', 'like', "%{$search}%")
                            ->orWhere('purchase_date', 'like', "%{$search}%");

                    })

                    // Supplier
                    ->orWhereHas('purchase.supplier', function ($sub) use ($search) {

                        $sub->where('name', 'like', "%{$search}%");

                    })

                    // Employee
                    ->orWhereHas('purchase.employee', function ($sub) use ($search) {

                        $sub->where('name', 'like', "%{$search}%");

                    });

                });

            })

            // Supplier Filter
            ->when($supplierId, function ($query) use ($supplierId) {

                $query->whereHas('purchase', function ($q) use ($supplierId) {

                    $q->where('supplier_id', $supplierId);

                });

            })

            // Employee Filter
            ->when($employeeId, function ($query) use ($employeeId) {

                $query->whereHas('purchase', function ($q) use ($employeeId) {

                    $q->where('employee_id', $employeeId);

                });

            })

            // Status Filter
            ->when($status, function ($query) use ($status) {

                $query->whereHas('purchase', function ($q) use ($status) {

                    $q->where('purchase_status', $status);

                });

            })

            // From Date
            ->when($fromDate, function ($query) use ($fromDate) {

                $query->whereHas('purchase', function ($q) use ($fromDate) {

                    $q->whereDate(
                        'purchase_date',
                        '>=',
                        $fromDate
                    );

                });

            })

            // To Date
            ->when($toDate, function ($query) use ($toDate) {

                $query->whereHas('purchase', function ($q) use ($toDate) {

                    $q->whereDate(
                        'purchase_date',
                        '<=',
                        $toDate
                    );

                });

            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

            // Dropdown Data
            $suppliers = Suppliers::orderBy('name')->get();

            $employees = Employees::orderBy('name')->get();

            // Statistics
            $receivedCount = Purchase::where(
                'purchase_status',
                'Received'
            )->count();

            $pendingCount = Purchase::where(
                'purchase_status',
                'Pending'
            )->count();

            $cancelledCount = Purchase::where(
                'purchase_status',
                'Cancelled'
            )->count();

            $totalAmount = Purchase_Detail::sum(
                'subtotal'
            );

            return view(
                'purchase_details.index',
                compact(
                    'purchaseDetails',
                    'suppliers',
                    'employees',
                    'receivedCount',
                    'pendingCount',
                    'cancelledCount',
                    'totalAmount'
                )
            );

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Failed to load purchase details. '
                    . $e->getMessage()
                );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {

            $purchases = Purchase::all();
            $products = Products::all();

            return view(
                'purchase_details.create',
                compact(
                    'purchases',
                    'products'
                )
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('purchase-details.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|numeric|min:1',
            'unit_price'  => 'required|numeric|min:0',
        ]);

        // Calculate line total
        $total = $request->quantity * $request->unit_price;

        $purchase = Purchase::findOrFail($request->purchase_id);

        if ($purchase->total_amount <= 0) {
            return back()
                ->withInput()
                ->with('error', 'Cannot add purchase detail because purchase total is zero.');
        }

        if ($total > $purchase->total_amount) {
            return back()
                ->withInput()
                ->with('error', 'Purchase detail subtotal exceeds current purchase total.');
        }

        try {

            Purchase_Detail::create([
                'purchase_id' => $request->purchase_id,
                'product_id'  => $request->product_id,
                'quantity'    => $request->quantity,
                'unit_price'  => $request->unit_price,
                'subtotal'    => $total,
                'description' => $request->description,
            ]);

            return redirect()
                ->route('purchase_details.index')
                ->with(
                    'success',
                    'Purchase detail created successfully.'
                );

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $purchaseDetail = Purchase_Detail::with([
                'product',
                'purchase.supplier',
                'purchase.employee'
            ])->findOrFail($id);

            return view(
                'purchase_details.show',
                compact('purchaseDetail')
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('purchase-details.index')
                ->with(
                    'error',
                    'Purchase detail not found.'
                );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {

            $purchaseDetail = Purchase_Detail::findOrFail($id);

            $purchases = Purchase::all();
            $products = Products::all();

            return view(
                'purchase_details.edit',
                compact(
                    'purchaseDetail',
                    'purchases',
                    'products'
                )
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('purchase-details.index')
                ->with(
                    'error',
                    'Purchase detail not found.'
                );
        }
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|numeric|min:1',
            'unit_price'  => 'required|numeric|min:0',
        ]);

        try {

            $purchaseDetail = Purchase_Detail::findOrFail($id);

            $purchaseDetail->update([
                'purchase_id' => $request->purchase_id,
                'product_id'  => $request->product_id,
                'quantity'    => $request->quantity,
                'unit_price'  => $request->unit_price,
                'subtotal'    => $request->quantity * $request->unit_price,
                'description' => $request->description,
            ]);

            return redirect()
                ->route('purchase-details.index')
                ->with(
                    'success',
                    'Purchase detail updated successfully.'
                );

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        try {

            $purchaseDetail = Purchase_Detail::findOrFail($id);

            $purchaseDetail->delete();

            return redirect()
                ->route('purchase_details.index')
                ->with(
                    'success',
                    'Purchase detail deleted successfully.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->route('purchase_details.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}