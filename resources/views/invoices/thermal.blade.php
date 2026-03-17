<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body {
            font-family: monospace;
            width: 300px;
            margin: auto;
        }
        .center {
            text-align: center;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        table {
            width: 100%;
            font-size: 12px;
        }

        td {
            padding: 2px 0;
        }
        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body
    <div class="center">
        <h3>TECHNOLOGY SHOP</h3>
        <p>Phnom Penh</p>
        <p>Tel: 012 345 678</p>
    </div>

    <div class="line"></div>
    <p>Invoice: #{{ $invoice->id }}</p>
    <p>Date: {{ $invoice->created_at->format('d/m/Y H:i') }}</p>
    <p>Cashier: {{ auth()->user()->name}}</p>
    <p>Customer: {{ $invoice->customer->name ?? 'Walk-in' }}</p>
    <div class="line"></div>
    <table>
        <tr class="bold">
            <td>Item</td>
            <td class="right">Total</td>
        </tr>
        @php $grandTotal = 0; @endphp
        @foreach($invoice->details as $item)
            @php $grandTotal += $item->total; @endphp
            <tr>
                <td colspan="2">{{ $item->product->name }}</td>
            </tr>
            <tr>
                <td>{{ $item->quantity }} x ${{ number_format($item->price,2) }}</td>
                <td class="right">${{ number_format($item->total,2) }}</td>
            </tr>
        @endforeach
    </table>
    <div class="line"></div>

    <table>
        <tr>
            <td class="bold">TOTAL</td>
            <td class="right bold">${{ number_format($grandTotal,2) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center">
        <p>Thank you 🙏</p>
        <p>Visit Again!</p>
    </div>

    <br>
    <div class="center">
        <button onclick="window.print()">🖨 Print</button>
    </div>
</body>
</html>