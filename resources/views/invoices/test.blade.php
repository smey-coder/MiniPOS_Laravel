<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice #{{ $invoice->id }} - Technology Shop</title>

<style>
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background-color: #f3f4f6;
        color: #111827;
        margin: 0;
        padding: 40px 20px;
        display: flex;
        justify-content: center;
    }

    .invoice-container {
        background: #fff;
        width: 800px;
        padding: 40px;
        border-radius: 4px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        box-sizing: border-box;
    }

    /* Header Layout */
    .header-table {
        width: 100%;
        margin-bottom: 30px;
    }
    .header-table td {
        vertical-align: top;
    }
    .company-name {
        font-size: 24px;
        font-weight: bold;
        color: #111827;
        margin: 0 0 5px 0;
    }
    .company-address, .meta-info p {
        font-size: 13px;
        color: #4b5563;
        margin: 2px 0;
    }
    .invoice-title {
        font-size: 28px;
        font-weight: bold;
        letter-spacing: 1px;
        text-align: right;
        color: #111827;
        margin: 0 0 15px 0;
    }
    .meta-table {
        width: 100%;
        font-size: 13px;
    }
    .meta-table td {
        padding: 3px 0;
    }
    .meta-table td.label {
        font-weight: bold;
        color: #374151;
        text-align: right;
        padding-right: 15px;
    }
    .meta-table td.value {
        text-align: right;
        color: #111827;
    }

    /* Bill To / Ship To Section */
    .address-section {
        width: 100%;
        margin-bottom: 30px;
        font-size: 13px;
    }
    .address-section td {
        vertical-align: top;
        width: 50%;
    }
    .section-title {
        font-weight: bold;
        color: #111827;
        margin-bottom: 5px;
        font-size: 14px;
    }

    /* Line Items Table */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 13px;
    }
    .items-table th {
        background-color: #f9fafb;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        padding: 10px 12px;
        font-weight: bold;
        color: #374151;
    }
    .items-table th.right, .items-table td.right {
        text-align: right;
    }
    .items-table th.center, .items-table td.center {
        text-align: center;
    }
    .items-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
    }

    /* Totals Section */
    .totals-wrapper {
        width: 100%;
        margin-bottom: 40px;
    }
    .totals-table {
        width: 300px;
        margin-left: auto;
        border-collapse: collapse;
        font-size: 13px;
    }
    .totals-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #e5e7eb;
    }
    .totals-table td.label {
        font-weight: bold;
        color: #374151;
    }
    .totals-table td.value {
        text-align: right;
        color: #111827;
    }
    .totals-table tr.total-row td {
        border-top: 2px solid #111827;
        border-bottom: 2px solid #111827;
        font-weight: bold;
        font-size: 15px;
        background-color: #f9fafb;
    }

    /* Footer & Signature */
    .footer-section {
        width: 100%;
        margin-top: 20px;
    }
    .footer-section td {
        vertical-align: top;
        width: 50%;
    }
    .terms-box {
        font-size: 12px;
        color: #4b5563;
        line-height: 1.5;
    }
    .signature-box {
        text-align: right;
    }
    .signature-line {
        margin-top: 40px;
        border-top: 1px solid #9ca3af;
        display: inline-block;
        width: 200px;
        padding-top: 5px;
        font-size: 12px;
        color: #4b5563;
    }

    /* Actions Bar */
    .action-bar {
        margin-top: 30px;
        text-align: center;
        border-top: 1px dashed #e5e7eb;
        padding-top: 20px;
    }
    .btn {
        display: inline-block;
        padding: 8px 16px;
        background: #4f46e5;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-size: 13px;
        margin: 0 5px;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }
    .btn:hover { background: #4338ca; }
    .btn-print { background: #059669; }
    .btn-print:hover { background: #047857; }
    .btn-back { background: #4b5563; }

    @media print {
        body { background: #fff; padding: 0; }
        .invoice-container { box-shadow: none; width: 100%; padding: 10px; }
        .action-bar { display: none; }
    }
</style>
</head>

<body>

@php
$subtotal = $invoice->details->sum('total');
$discount = $invoice->customer->customer_type->discount_rate ?? 0;
$discountAmount = ($subtotal * $discount) / 100;
$grandTotal = $subtotal - $discountAmount;
@endphp

<div class="invoice-container">

    <!-- Header Block -->
    <table class="header-table">
        <tr>
            <td>
                <h2 class="company-name">Technology Shop</h2>
                <p class="company-address">Phnom Penh, Cambodia</p>
                <p class="company-address">Tel: 096 879 324</p>
            </td>
            <td>
                <div class="invoice-title">INVOICE</div>
                <table class="meta-table">
                    <tr>
                        <td class="label">Invoice #</td>
                        <td class="value">US-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Invoice Date</td>
                        <td class="value">{{ $invoice->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Cashier</td>
                        <td class="value">{{ auth()->user()->name ?? 'Admin' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td class="value" style="font-weight: bold; color: {{ $invoice->invoice_status == 'Completed' ? '#065f46' : '#991b1b' }};">
                            {{ $invoice->invoice_status }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Bill To / Ship To Block -->
    <table class="address-section">
        <tr>
            <td>
                <div class="section-title">Bill To:</div>
                <p><strong>{{ $invoice->customer->name ?? 'Walk-in Customer' }}</strong></p>
                <p>{{ $invoice->customer->phone ?? 'N/A' }}</p>
                <p>{{ $invoice->customer->address ?? 'Phnom Penh' }}</p>
            </td>
            <td>
                <div class="section-title">Ship To:</div>
                <p><strong>{{ $invoice->customer->name ?? 'Walk-in Customer' }}</strong></p>
                <p>{{ $invoice->customer->address ?? 'Phnom Penh' }}</p>
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="center" style="width: 10%;">QTY</th>
                <th style="width: 50%;">DESCRIPTION</th>
                <th class="right" style="width: 20%;">UNIT PRICE</th>
                <th class="right" style="width: 20%;">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->details as $item)
            <tr>
                <td class="center">{{ $item->quantity }}</td>
                <td>{{ $item->product->name }}</td>
                <td class="right">${{ number_format($item->price, 2) }}</td>
                <td class="right">${{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Block -->
    <table class="totals-wrapper">
        <tr>
            <td></td>
            <td>
                <table class="totals-table">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="value">${{ number_format($subtotal, 2) }}</td>
                    </tr>
                    @if($discount > 0)
                    <tr>
                        <td class="label">Discount ({{ $discount }}%)</td>
                        <td class="value">-${{ number_format($discountAmount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td class="label">TOTAL</td>
                        <td class="value">${{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer: Terms & Signature -->
    <table class="footer-section">
        <tr>
            <td>
                <div class="section-title">Terms & Conditions</div>
                <div class="terms-box">
                    <p>Payment is due within 15 days.</p>
                    <p>Please make checks payable to: Technology Shop</p>
                </div>
            </td>
            <td class="signature-box">
                <div class="signature-line">
                    Authorized Signature
                </div>
            </td>
        </tr>
    </table>

    <!-- Action Buttons -->
    <div class="action-bar">
        <a href="{{ route('pos.index') }}" class="btn btn-back">← Back to POS</a>
        <button onclick="window.print()" class="btn btn-print">🖨 Print Invoice</button>
        @if($invoice->invoice_status != 'Completed')
        <button onclick="confirmPayment({{ $invoice->id }})" class="btn">
            ✔ Confirm Payment
        </button>
        @endif
    </div>

</div>

<script>
function confirmPayment(id){
    if(confirm('Are you sure you want to mark this payment as completed?')) {
        fetch('/confirm-payment/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Payment confirmed successfully!');
            location.reload();
        })
        .catch(err => {
            alert('Something went wrong!');
            console.error(err);
        });
    }
}
</script>

</body>
</html>