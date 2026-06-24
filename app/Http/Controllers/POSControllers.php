<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Customers;
use App\Models\Product_Types;
use App\Models\Invoice_Details;
use App\Models\Invoices;
use App\Models\Employees;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class POSControllers extends Controller implements HasMiddleware
{

    public static function middleware(): array {
            return [
                new Middleware('permission:view sale', only:['index']),
                new Middleware('permission:checkout sale', only:['checkout']),
                new Middleware('permission:thermal sale', only:['thermal']),
                new Middleware('permission:confirmPayment sale', only:['confirmPayment']),
            ];
        }
    // Show POS page
    public function index(Request $request)
    {
        $products = Products::with('productType')
            ->when($request->product_type_id, function ($query) use ($request) {
                $query->where('product_type_id', $request->product_type_id);
            })
            ->latest()
            ->get();

        return view('sales.index', [
            'products'      => $products,
            'customers'     => Customers::with('customer_type')->get(),
            'productTypes'  => Product_Types::orderBy('name')->get(),
        ]);
    }

    // Checkout (Save Invoice + Apply Discount)
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

            $customer = Customers::with('customer_type')->findOrFail($request->customer_id);
            // Get discount from Customer Type
            $discountRate = $customer->customer_type->discount_rate ?? 0;
            $invoice = Invoices::create([
                'customer_id' => $customer->id,
                'employee_id' => $employee->id,
                'invoice_status' => 'Completed',
                'memo' => $request->memo
            ]);

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
            }
            $discountAmount = ($grandTotal * $discountRate) / 100;
            $finalTotal = $grandTotal - $discountAmount;
            $amount = number_format($finalTotal,2,'.','');
            $merchantAccount = '0968789324';
            $merchantName = 'TECHNOLOGY SHOP';
            $invoiceRef = 'INV' . $invoice->id;

            $qrText =
                "BANK:ACLEDA;" .
                "ACC:{$merchantAccount};" .
                "AMOUNT:{$amount};" .
                "REF:{$invoiceRef};" .
                "NAME:{$merchantName}";

            $qrCodeSvg = QrCode::size(200)
                ->generate($qrText);

            return response()->json([
                'success' => true,
                'invoice_id' => $invoice->id,
                'subtotal' => $grandTotal,
                'discount_rate' => $discountRate,
                'discount_amount' => $discountAmount,
                'grand_total' => $finalTotal,
                'qr_code' => $qrCodeSvg
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Thermal Print
    public function thermal($id)
    {
        $invoice = Invoices::with([
            'details.product',
            'customer.customer_type'
        ])->findOrFail($id);

        //Calculate
        $subtotal = $invoice->details->sum('total');
        $discountRate = $invoice->customer->customer_type->discount_rate ?? 0;
        $discountAmount = ($subtotal * $discountRate) / 100;
        $grandTotal = $subtotal - $discountAmount;
        $amount = number_format($grandTotal,2,'.','');

        $merchantAccount = '0968789324';
        $merchantName = 'TECHNOLOGY SHOP';
        $invoiceRef = 'INV' . $invoice->id;

        $qrText =
            "BANK:ACLEDA;" .
            "ACC:{$merchantAccount};" .
            "AMOUNT:{$amount};" .
            "REF:{$invoiceRef};" .
            "NAME:{$merchantName}";

        $qrCodeSvg = QrCode::size(200)
            ->generate($qrText);

        return view('invoices.thermal', compact(
            'invoice',
            'subtotal',
            'discountRate',
            'discountAmount',
            'grandTotal',
            'qrCodeSvg'
        ));
    }

    // Confirm Payment
    public function confirmPayment($id)
    {
        $invoice = Invoices::findOrFail($id);

        $invoice->invoice_status = 'Completed';

        $invoice->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed for Invoice #' . $invoice->id
        ]);
    }
}