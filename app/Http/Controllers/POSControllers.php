<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Customers;
use App\Models\Invoice_Details;
use App\Models\Invoices;
use App\Models\Employees;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    // Thermal (Invoice with QR)
    public function thermal($id)
    {
        // Get invoice with details and customer
        $invoice = Invoices::with('details.product', 'customer')->findOrFail($id);

        // Calculate grand total
        $grandTotal = $invoice->details->sum('total');

        // Format amount for QR
        $amount = number_format($grandTotal, 2, '.', '');

        // Bank info (example ABA/ACLEDA)
        $merchantAccount = '0968789324'; // Your bank account
        $merchantName = 'TECHNOLOGY SHOP';
        $invoiceRef = 'INV' . $invoice->id;

        // QR code string format
        $qrText = "BANK:ACLEDA;ACC:{$merchantAccount};AMOUNT:{$amount};REF:{$invoiceRef};NAME:{$merchantName}";

        // Generate QR code SVG
        $qrCodeSvg = QrCode::size(200)->generate($qrText);

        return view('invoices.thermal', compact('invoice', 'grandTotal', 'qrCodeSvg'));
    }

    // Checkout (Save Invoice + Generate QR for Payment)
    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'customer_id' => 'required|exists:customers,id'
            ]);

            $employee = Employees::where('user_id', auth()->id())->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current user is not linked to an employee record.'
                ]);
            }

            // Create Invoice
            $invoice = Invoices::create([
                'customer_id' => $request->customer_id,
                'employee_id' => $employee->id,
                'invoice_status' => 'Completed', // start as Pending
                'memo' => $request->memo ?? null
            ]);

            // Save items and update stock
            $grandTotal = 0;
            foreach ($request->items as $item) {
                $total = $item['qty'] * $item['price'];
                $grandTotal += $total;

                Invoice_Details::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $total
                ]);

                $product = Products::find($item['id']);
                if ($product) {
                    $product->quantity -= $item['qty'];
                    $product->save();
                }
            }

            // Generate QR code for payment
            $amount = number_format($grandTotal, 2, '.', '');
            $merchantAccount = '0968789324'; // Your bank account
            $merchantName = 'TECHNOLOGY SHOP';
            $invoiceRef = 'INV' . $invoice->id;
            $qrText = "BANK:ACLEDA;ACC:{$merchantAccount};AMOUNT:{$amount};REF:{$invoiceRef};NAME:{$merchantName}";
            $qrCodeSvg = QrCode::size(200)->generate($qrText);

            // Return invoice with QR for payment
            return response()->json([
                'success' => true,
                'invoice_id' => $invoice->id,
                'grand_total' => $grandTotal,
                'qr_code' => $qrCodeSvg
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

    // Confirm Payment (Optional endpoint to mark invoice as Paid)
    public function confirmPayment($id)
    {
        $invoice = Invoices::findOrFail($id);

        // Here you can call bank API to confirm payment or mark manually
        $invoice->invoice_status = 'Completed';
        $invoice->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed for Invoice #' . $invoice->id
        ]);
    }
}