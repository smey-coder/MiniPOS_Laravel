<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Customers;
use App\Models\Invoice_Details;
use App\Models\Invoices;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class POSControllers extends Controller implements HasMiddleware
{

    public static function middleware(): array {
        return [
            new Middleware('permission:view sales', only:['index']),
            new Middleware('permission:show thermals', only:['thermal']),
            new Middleware('permission:sale shows', only:['shows']),
            new Middleware('permission:create invoices', only:['checkout']),
            // new Middleware('permission:delete articles', only:['destroy']),
        ];
    }
    // Show POS page
    public function index()
    {
        return view('sales.index', [
            'products' => Products::latest()->get(),
            'customers' => Customers::all()
        ]);
    }
    public function thermal($id)
    {
        $invoice = Invoices::with('details.product','customer')->findOrFail($id);
        return view('invoices.thermal', compact('invoice'));
    }

    // Checkout (SAVE INVOICE)
    public function checkout(Request $request)
    {
            try {
            $request->validate([
                'items' => 'required|array|min:1',
                'customer_id' => 'required|exists:customers,id'
            ]);

            // Get employee linked to current user
            $employee = \App\Models\Employees::where('user_id', auth()->id())->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current user is not linked to an employee record.'
                ]);
            }

            // Create Invoice
            $invoice = \App\Models\Invoices::create([
                'customer_id' => $request->customer_id,
                'employee_id' => $employee->id,  // use employee ID
                'invoice_status' => 'Completed',
                'memo' => $request->memo
            ]);

            // Save items and update stock
            foreach ($request->items as $item) {
                $total = $item['qty'] * $item['price'];

                \App\Models\Invoice_Details::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $total
                ]);

                // Update product stock
                $product = \App\Models\Products::find($item['id']);
                if ($product) {
                    $product->quantity -= $item['qty'];
                    $product->save();
                }
            }

            return response()->json([
                'success' => true,
                'invoice_id' => $invoice->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors()['items'] ?? $e->errors()['customer_id'] ?? ['Unknown validation error'])
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Show Invoice
    public function show($id)
    {
        $invoice = Invoices::with('details.product','customer')->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }
}
