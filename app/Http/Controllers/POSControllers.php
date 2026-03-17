<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Customers;
use App\Models\Invoice_Details;
use App\Models\Invoices;

class POSControllers extends Controller
{
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

            // Create Invoice
            $invoice = Invoices::create([
                'customer_id' => $request->customer_id,
                'employee_id' => auth()->id() ?? 1,
                'invoice_status' => 'Completed',
                'memo' => $request->memo
            ]);

            // Save items
            foreach ($request->items as $item) {
                $total = $item['qty'] * $item['price'];

                Invoice_Details::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $total
                ]);

                // Update stock
                $product = Products::find($item['id']);
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
