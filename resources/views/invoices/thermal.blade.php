<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Receipt - Technology Shop</title>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f3f4f6;
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .receipt {
        background: #fff;
        width: 320px;
        padding: 18px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    }

    .center { text-align: center; }

    h3 {
        margin: 4px 0;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 1px;
    }

    p {
        margin: 2px 0;
        font-size: 0.8rem;
        color: #374151;
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
        padding: 4px 0;
    }

    .right { text-align: right; }

    .bold { font-weight: 600; }

    .total-box {
        background: #eef2ff;
        padding: 6px;
        border-radius: 6px;
    }

    .status {
        display: inline-block;
        padding: 2px 8px;
        font-size: 0.7rem;
        border-radius: 5px;
        margin-top: 4px;
    }

    .paid { background: #d1fae5; color: #065f46; }
    .pending { background: #fee2e2; color: #991b1b; }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        background: #4f46e5;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.8rem;
        margin-top: 8px;
        border: none;
        cursor: pointer;
    }

    .btn:hover {
        background: #4338ca;
    }

    .qr-box {
        border: 1px dashed #9ca3af;
        padding: 10px;
        border-radius: 8px;
        margin-top: 10px;
    }

    @media print {
        body { background: #fff; padding: 0; }
        .receipt {
            box-shadow: none;
            border: none;
            width: 320px;
        }
        .btn { display: none; }
    }
</style>
</head>

<body>

@php
$khmerDays = [
    'Sunday' => 'អាទិត្យ','Monday' => 'ច័ន្ទ','Tuesday' => 'អង្គារ',
    'Wednesday' => 'ពុធ','Thursday' => 'ព្រហស្បតិ៍',
    'Friday' => 'សុក្រ','Saturday' => 'សៅរ៍',
];

$khmerMonths = [
    1 => 'មករា',2 => 'កុម្ភៈ',3 => 'មីនា',4 => 'មេសា',
    5 => 'ឧសភា',6 => 'មិថុនា',7 => 'កក្កដា',8 => 'សីហា',
    9 => 'កញ្ញា',10 => 'តុលា',11 => 'វិច្ឆិកា',12 => 'ធ្នូ',
];

$dayName = $khmerDays[$invoice->created_at->format('l')];
$day = $invoice->created_at->format('d');
$month = $khmerMonths[(int)$invoice->created_at->format('m')];
$year = $invoice->created_at->format('Y');
$time = $invoice->created_at->format('h:i A');

$discount = $invoice->customer->discount ?? 0;
@endphp

<div class="receipt">

    <!-- Back -->
    <a href="{{ route('pos.index')}}" class="btn">← Back</a>

    <!-- Header -->
    <div class="center">
        <h3>TECHNOLOGY SHOP</h3>
        <p>Phnom Penh</p>
        <p>Tel: 096 879 324</p>

        <div class="status {{ $invoice->invoice_status == 'Completed' ? 'paid' : 'pending' }}">
            {{ $invoice->invoice_status }}
        </div>
    </div>

    <div class="line"></div>

    <!-- Info -->
    <p><span class="bold">Invoice:</span> #{{ $invoice->id }}</p>
    <p><span class="bold">ថ្ងៃ-ខែ-ឆ្នាំ:</span> {{ $dayName }}, {{ $day }} {{ $month }} {{ $year }} {{ $time }}</p>
    <p><span class="bold">Cashier:</span> {{ auth()->user()->name }}</p>
    <p><span class="bold">Customer:</span> {{ $invoice->customer->name ?? 'Walk-in' }}</p>
    <p><span class="bold">Discount:</span> {{ $discount }}%</p>

    <div class="line"></div>

    <!-- Items -->
    <table>
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

    <div class="line"></div>

    <!-- Total -->
    <div class="total-box">
        <table>
            <tr>
                <td class="bold">TOTAL</td>
                <td class="right bold">${{ number_format($grandTotal,2) }}</td>
            </tr>
        </table>
    </div>

    <!-- QR -->
    <div class="center qr-box">
        <img 
            src="{{ asset('storage/qrcode/QrCode_Aceledajpg.jpg') }}" 
            alt="QR Code Payment"
            style="width:150px; height:150px; object-fit:contain;"
        >
        <p style="font-size:0.75rem;">Scan to pay via ABA / ACLEDA</p>
    </div>

    <!-- Footer -->
    <div class="center">
        <p>🙏 Thank you for shopping</p>
        <p>Visit Again!</p>
    </div>

    <!-- Actions -->
    <div class="center">
        <button onclick="window.print()" class="btn">🖨 Print</button>

        @if($invoice->invoice_status != 'Completed')
        <button onclick="confirmPayment({{ $invoice->id }})" class="btn">
            ✔ Confirm Payment
        </button>
        @endif
    </div>

</div>

<script>
function confirmPayment(id){
    fetch('/confirm-payment/' + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}
</script>

</body>
</html>