<?php
namespace App\Http\Controllers;
use App\Models\Purchase;
use App\Models\Suppliers;
use App\Models\Employees;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PurchaseController extends Controller implements HasMiddleware
{

    public static function middleware(): array {
            return [
                new Middleware('permission:view purchase', only:['index']),
                new Middleware('permission:edit purchase', only:['edit']),
                new Middleware('permission:update purchase', only:['update']),
                new Middleware('permission:create purchase', only:['create']),
                new Middleware('permission:delete purchase', only:['destroy']),
            ];
    }
    /**
     * Display all purchases
     */
    public function index(Request $request)
    {
       try {
        $query = Purchase::with(['supplier', 'employee']);

        // Supplier Filter
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Employee Filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('purchase_status', $request->status);
        }

        // Date Range Filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('purchase_date', [
                $request->from_date,
                $request->to_date
            ]);
        }

        $purchases = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $suppliers = Suppliers::all();
        $employees = Employees::all();

        return view('purchases.index', compact(
            'purchases',
            'suppliers',
            'employees'
        ));

    } catch (\Exception $e) {

        return redirect()
            ->route('purchases.index')
            ->with('error', $e->getMessage());
    }
    }

    /**
     * Show create form
     */
    public function create()
    {
        try {

            $suppliers = Suppliers::all();
            $employees = Employees::all();

            return view(
                'purchases.create',
                compact('suppliers', 'employees')
            );

        } catch (Exception $e) {

            return redirect()
                ->route('purchases.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Store purchase
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'     => 'required|exists:supplier,id',
            'employee_id'     => 'required|exists:employees,id',
            'purchase_code'   => 'required|unique:purchases,purchase_code',
            'purchase_date'   => 'required|date',
            'total_amount'    => 'required|numeric|min:0',
            'purchase_status' => 'required',
        ]);

        try {

            Purchase::create([
                'supplier_id'     => $request->supplier_id,
                'employee_id'     => $request->employee_id,
                'purchase_code'   => $request->purchase_code,
                'purchase_date'   => $request->purchase_date,
                'total_amount'    => $request->total_amount,
                'purchase_status' => $request->purchase_status,
                'note'            => $request->note,
            ]);

            return redirect()
                ->route('purchases.index')
                ->with(
                    'success',
                    'Purchase created successfully.'
                );

        } catch (Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Show single purchase
     */
    public function show(string $id)
    {
        try {

            $purchase = Purchase::with([
                'supplier',
                'employee'
            ])->findOrFail($id);

            return view(
                'purchases.show',
                compact('purchase')
            );

        } catch (Exception $e) {

            return redirect()
                ->route('purchases.index')
                ->with(
                    'error',
                    'Purchase not found.'
                );
        }
    }

    /**
     * Show edit form
     */
    public function edit(string $id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            $suppliers = Suppliers::all();
            $employees = Employees::all();
            return view('purchases.edit',compact('purchase','suppliers','employees'));
        } catch (Exception $e) {
            return redirect()->route('purchases.index')->with('error','Purchase not found.');
        }
    }

    /**
     * Update purchase
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_id'     => 'required|exists:supplier,id',
            'employee_id'     => 'required|exists:employees,id',
            'purchase_code'   => 'required|unique:purchases,purchase_code,' . $id,
            'purchase_date'   => 'required|date',
            'total_amount'    => 'required|numeric|min:0',
            'purchase_status' => 'required',
        ]);

        try {

            $purchase = Purchase::findOrFail($id);

            $purchase->update([
                'supplier_id'     => $request->supplier_id,
                'employee_id'     => $request->employee_id,
                'purchase_code'   => $request->purchase_code,
                'purchase_date'   => $request->purchase_date,
                'total_amount'    => $request->total_amount,
                'purchase_status' => $request->purchase_status,
                'note'            => $request->note,
            ]);

            return redirect()
                ->route('purchases.index')
                ->with(
                    'success',
                    'Purchase updated successfully.'
                );

        } catch (Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Delete purchase
     */
    public function destroy(string $id)
    {
        try {

            $purchase = Purchase::findOrFail($id);

            $purchase->delete();

            return redirect()
                ->route('purchases.index')
                ->with(
                    'success',
                    'Purchase deleted successfully.'
                );

        } catch (Exception $e) {

            return redirect()
                ->route('purchases.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}