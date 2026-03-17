<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .company h2 {
            margin: 0;
            color: #4f46e5;
        }

        .info {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background: #4f46e5;
            color: #fff;
            padding: 10px;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .total {
            text-align: right;
            margin-top: 20px;
        }

        .total h3 {
            margin: 5px 0;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #888;
        }

        .print-btn {
            margin-top: 20px;
            text-align: right;
        }

        .print-btn button {
            background: #16a34a;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

    </style>
</head>

<body>

<div class="invoice-box">

    <!-- HEADER -->
    <div class="header">
        <div class="company">
            <h2>Technology Shop</h2>
            <div class="info">
                Street 123, Phnom Penh<br>
                Phone: 012 345 678<br>
                Email: techshop@gmail.com
            </div>
        </div>

        <div class="info">
            <strong>Invoice #:</strong> {{ $invoice->id }} <br>
            <strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }} <br>
            <strong>Status:</strong> {{ $invoice->invoice_status }}
        </div>
    </div>

    <!-- CUSTOMER + EMPLOYEE -->
    <div class="header">
        <div class="info">
            <strong>Customer:</strong><br>
            {{ $invoice->customer->name ?? 'Walk-in Customer' }}
        </div>

        <div class="info">
            <strong>Employee:</strong><br>
            {{ auth()->user()->name ?? 'Admin' }}
        </div>
    </div>

    <!-- TABLE -->
    <table>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>

        @php $grandTotal = 0; @endphp

        @foreach($invoice->details as $key => $item)
        @php $grandTotal += $item->total; @endphp
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->product->name ?? '' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>${{ number_format($item->price,2) }}</td>
            <td>${{ number_format($item->total,2) }}</td>
        </tr>
        @endforeach
    </table>

    <!-- TOTAL -->
    <div class="total">
        <h3>Total: ${{ number_format($grandTotal,2) }}</h3>
    </div>

    <!-- PRINT -->
    <div class="print-btn">
        <button onclick="window.print()">🖨 Print</button>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Thank you for your purchase 🙏
    </div>

</div>

</body>
</html>