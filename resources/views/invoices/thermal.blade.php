<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - Technology Shop</title>
    <style>
        /* General Body */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* Receipt Container */
        .receipt {
            background-color: #fff;
            width: 320px; /* compact width */
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .center {
            text-align: center;
        }

        h3 {
            margin: 5px 0;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        p {
            margin: 2px 0;
            font-size: 0.85rem;
        }

        .line {
            border-top: 1px dashed #9ca3af;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        td {
            padding: 3px 0;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: 600;
        }

        .total-row {
            border-top: 2px solid #4f46e5;
            margin-top: 5px;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 6px 12px;
            background-color: #4f46e5;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.8rem;
            margin-bottom: 10px;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #4338ca;
        }

        /* Print Styles */
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .receipt {
                box-shadow: none;
                border: none;
                width: 320px;
                margin: auto;
            }
            .btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    @php
    $khmerDays = [
        'Sunday' => 'អាទិត្យ',
        'Monday' => 'ច័ន្ទ',
        'Tuesday' => 'អង្គារ',
        'Wednesday' => 'ពុធ',
        'Thursday' => 'ព្រហស្បតិ៍',
        'Friday' => 'សុក្រ',
        'Saturday' => 'សៅរ៍',
    ];

    $khmerMonths = [
        1 => 'មករា',
        2 => 'កុម្ភៈ',
        3 => 'មីនា',
        4 => 'មេសា',
        5 => 'ឧសភា',
        6 => 'មិថុនា',
        7 => 'កក្កដា',
        8 => 'សីហា',
        9 => 'កញ្ញា',
        10 => 'តុលា',
        11 => 'វិច្ឆិកា',
        12 => 'ធ្នូ',
    ];

    $dayName = $khmerDays[$invoice->created_at->format('l')]; // Day name
    $day = $invoice->created_at->format('d'); // day number
    $month = $khmerMonths[(int)$invoice->created_at->format('m')]; // month in Khmer
    $year = $invoice->created_at->format('Y'); // year
    $time = $invoice->created_at->format('h:i A'); // 12-hour format with AM/PM
    @endphp

    <div class="receipt">
        <!-- Back Button -->
        <a href="{{ route('pos.index')}}" class="btn">← Back</a>

        <!-- Header -->
        <div class="center">
            <h3>TECHNOLOGY SHOP</h3>
            <p>Phnom Penh</p>
            <p>Tel: 096 879 324</p>
        </div>

        <div class="line"></div>

        <!-- Invoice Info -->
        <p><span class="bold">Invoice:</span> #{{ $invoice->id }}</p>
        <p><span class="bold">ថ្ងៃ-ខែ-ឆ្នាំ:</span> {{ $dayName }}, {{ $day }} {{ $month }} {{ $year }} {{ $time }}</p>
        <p><span class="bold">Cashier:</span> {{ auth()->user()->name }}</p>
        <p><span class="bold">Customer:</span> {{ $invoice->customer->name ?? 'Walk-in' }}</p>
        <p><span class="bold">Discount:</span> {{$invoice->customer->discount}} %</p>

        <div class="line"></div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr class="bold">
                    <td>Item</td>
                    <td class="right">Total</td>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($invoice->details as $item)
                    @php $grandTotal += $item->total; @endphp
                    <tr>
                        <td colspan="2" class="bold">{{ $item->product->name }}</td>
                    </tr>
                    <tr>
                        <td>{{ $item->quantity }} x ${{ number_format($item->price,2) }}</td>
                        <td class="right">${{ number_format($item->total,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="line total-row"></div>

        <!-- Total -->
        <table>
            <tr>
                <td class="bold">TOTAL</td>
                <td class="right bold">${{ number_format($grandTotal,2) }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <!-- Footer -->
        <div class="center">
            <p>Thank you for shopping with us 🙏</p>
            <p>Visit Again!</p>
        </div>

        <!-- Print Button -->
        <div class="center">
            <button onclick="window.print()" class="btn">🖨 Print</button>
        </div>
    </div>

</body>
</html>