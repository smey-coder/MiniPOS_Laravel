
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Invoice #{{ $invoice->id }} - Technology Shop</title>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
            --surface: #ffffff;
            --background: #f3f4f6;
            --success: #047857;
            --success-bg: #d1fae5;
            --warning: #92400e;
            --warning-bg: #fef3c7;
            --danger: #b91c1c;
            --danger-bg: #fee2e2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 50px;
            padding: 36px 18px;
            font-family: Inter, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Arial, sans-serif;
            background: var(--background);
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .page {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Top action bar */

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .toolbar-left,
        .toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .page-label {
            color: var(--muted);
            font-size: 13px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border: 1px solid transparent;
            border-radius: 9px;
            text-decoration: none;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, border-color .2s;
        }

        .btn:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: #fff;
            border-color: var(--border);
            color: #374151;
        }

        .btn-secondary:hover {
            background: #f9fafb;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-success {
            background: var(--success);
            color: #fff;
        }

        .btn-success:hover {
            background: #065f46;
        }

        /* Invoice paper */

        .invoice {
            background: var(--surface);
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 52px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 32px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border);
        }

        .company {
            min-width: 0;
        }

        .logo {
            display: block;
            max-width: 160px;
            max-height: 55px;
            width: auto;
            object-fit: contain;
            object-position: left center;
            margin-bottom: 16px;
        }

        .company-name {
            font-size: 22px;
            line-height: 1.3;
            font-weight: 800;
            letter-spacing: -.6px;
            margin: 0 0 8px;
        }

        .company-details {
            color: var(--muted);
            font-size: 13px;
            margin: 3px 0;
        }

        .invoice-heading {
            min-width: 245px;
            text-align: right;
        }

        .invoice-title {
            font-size: 34px;
            font-weight: 800;
            letter-spacing: 2px;
            line-height: 1.2;
            margin: 0 0 18px;
        }

        .invoice-number {
            color: var(--primary);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .meta-row {
            display: flex;
            justify-content: flex-end;
            align-items: baseline;
            gap: 16px;
            margin-top: 5px;
            font-size: 12px;
        }

        .meta-label {
            color: var(--muted);
        }

        .meta-value {
            font-weight: 600;
            text-align: right;
        }

        .status {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            white-space: nowrap;
        }

        .status-paid {
            background: var(--success-bg);
            color: var(--success);
        }

        .status-pending {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .status-overdue {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .status-default {
            background: #f3f4f6;
            color: #4b5563;
        }

        /* Billing information */

        .billing-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            padding: 32px 0;
        }

        .section-label {
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 12px;
        }

        .customer-name {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .customer-detail {
            color: var(--muted);
            margin: 3px 0;
            overflow-wrap: anywhere;
        }

        /* Products table */

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .items-table thead {
            background: #f8fafc;
        }

        .items-table th {
            color: #4b5563;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-weight: 700;
            padding: 14px 12px;
            text-align: left;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .items-table td {
            padding: 17px 12px;
            border-bottom: 1px solid var(--border);
            color: #374151;
        }

        .items-table .text-center {
            text-align: center;
        }

        .items-table .text-right {
            text-align: right;
        }

        .product-name {
            color: var(--text);
            font-weight: 600;
        }

        /* Invoice totals */

        .summary-layout {
            display: flex;
            justify-content: flex-end;
            padding-top: 24px;
        }

        .summary {
            width: 100%;
            max-width: 350px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 9px 0;
            color: #4b5563;
        }

        .summary-value {
            color: var(--text);
            font-weight: 600;
            text-align: right;
            white-space: nowrap;
        }

        .discount-value {
            color: var(--success);
        }

        .grand-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-top: 12px;
            padding: 18px 20px;
            border-radius: 12px;
            background: #eef2ff;
            color: #312e81;
        }

        .grand-total-label {
            font-size: 13px;
            font-weight: 700;
        }

        .grand-total-value {
            font-size: 23px;
            line-height: 1.3;
            font-weight: 800;
            white-space: nowrap;
        }

        /* Footer */

        .invoice-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            margin-top: 42px;
            padding-top: 26px;
            border-top: 1px solid var(--border);
        }

        .footer-title {
            font-size: 12px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .footer-text {
            color: var(--muted);
            font-size: 12px;
            margin: 4px 0;
        }

        .signature {
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            width: 190px;
            margin-top: 42px;
            padding-top: 9px;
            border-top: 1px solid #9ca3af;
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        .thank-you {
            margin-top: 30px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
        }

        .notice {
            display: none;
            margin-bottom: 16px;
            padding: 12px 16px;
            border-radius: 9px;
            font-size: 13px;
        }

        .notice-error {
            background: var(--danger-bg);
            color: var(--danger);
        }

        /* Responsive */

        @media (max-width: 700px) {
            body {
                padding: 16px 10px;
            }

            .invoice {
                padding: 24px 18px;
                border-radius: 12px;
            }

            .invoice-header {
                flex-direction: column;
                gap: 24px;
            }

            .invoice-heading {
                width: 100%;
                min-width: 0;
                text-align: left;
            }

            .invoice-title {
                font-size: 28px;
            }

            .meta-row {
                justify-content: flex-start;
            }

            .meta-value {
                text-align: left;
            }

            .billing-section {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .invoice-footer {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .signature {
                text-align: left;
            }

            .toolbar {
                align-items: flex-start;
            }

            .toolbar-right {
                width: 100%;
            }

            .toolbar-right .btn {
                flex: 1;
            }

            .items-table {
                min-width: 560px;
            }

            .grand-total-value {
                font-size: 20px;
            }
        }

        /* Print / Save as PDF */

        @page {
            size: A4;
            margin: 12mm;
        }

        @media print {
            body {
                padding: 0;
                background: #fff;
                font-size: 12px;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .page {
                max-width: none;
                margin: 0;
            }

            .toolbar,
            .notice {
                display: none !important;
            }

            .invoice {
                border: none;
                border-radius: 0;
                box-shadow: none;
                padding: 0;
            }

            .invoice-header,
            .billing-section,
            .summary-layout,
            .invoice-footer {
                break-inside: avoid;
            }

            .items-table thead {
                display: table-header-group;
            }

            .items-table tr {
                break-inside: avoid;
            }

            .table-wrap {
                overflow: visible;
            }

            .items-table {
                min-width: 0;
            }

            .grand-total {
                border: 1px solid #c7d2fe;
            }

            a {
                color: inherit;
                text-decoration: none;
            }
        }
    </style>
</head>

<body>

@php
    $details = $invoice->details;

    $subtotal = $details->sum('total');

    $discount = (float) (
        optional(optional($invoice->customer)->customer_type)
            ->discount_rate ?? 0
    );

    // Keep discount percentage within a valid range.
    $discount = max(0, min(100, $discount));

    $discountAmount = ($subtotal * $discount) / 100;

    $grandTotal = $subtotal - $discountAmount;

    $status = (string) ($invoice->invoice_status ?? 'Unknown');

    $statusClass = match (strtolower($status)) {
        'completed', 'paid' => 'status-paid',
        'pending', 'unpaid', 'partially paid' => 'status-pending',
        'overdue' => 'status-overdue',
        default => 'status-default',
    };

    $customer = $invoice->customer;
@endphp

<div class="page">

    <!-- Action Toolbar -->
    <div class="toolbar">
        <div class="toolbar-left">
            <a href="{{ route('pos.index') }}" class="btn btn-secondary">
                &larr; Back to POS
            </a>

            <span class="page-label">
                Invoice / #{{ $invoice->id }}
            </span>
        </div>

        <div class="toolbar-right">
            <button type="button"
                    class="btn btn-secondary"
                    onclick="window.print()">
                &#128424; Print / PDF
            </button>

            @if($invoice->invoice_status != 'Completed')
                <button type="button"
                        id="confirm-payment-btn"
                        class="btn btn-primary"
                        data-url="{{ url('/confirm-payment/' . $invoice->id) }}">
                    &#10003; Confirm Payment
                </button>
            @endif
        </div>
    </div>

    <div id="payment-error"
         class="notice notice-error"
         role="alert"></div>

    <!-- Invoice Paper -->
    <main class="invoice">

        <!-- Header -->
        <header class="invoice-header">

            <div class="company">
                <img
                    src="{{ asset('images/miniPOS.png') }}"
                    alt="Technology Shop Logo"
                    class="logo"
                    onerror="this.style.display='none'">

                <h1 class="company-name">Technology Shop</h1>

                <p class="company-details">
                    Phnom Penh, Cambodia
                </p>

                <p class="company-details">
                    Tel: 096 879 324
                </p>
            </div>

            <div class="invoice-heading">
                <h2 class="invoice-title">INVOICE</h2>

                <div class="invoice-number">
                    #US-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}
                </div>

                <div class="meta-row">
                    <span class="meta-label">Invoice Date</span>
                    <span class="meta-value">
                        {{ $invoice->created_at
                            ? $invoice->created_at->format('d M Y')
                            : 'N/A' }}
                    </span>
                </div>

                <div class="meta-row">
                    <span class="meta-label">Cashier</span>
                    <span class="meta-value">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </span>
                </div>

                <div class="meta-row">
                    <span class="meta-label">Status</span>
                    <span class="status {{ $statusClass }}">
                        {{ $status }}
                    </span>
                </div>
            </div>

        </header>

        <!-- Customer Information -->
        <section class="billing-section">

            <div>
                <h3 class="section-label">Bill To</h3>

                <p class="customer-name">
                    {{ optional($customer)->name ?? 'Walk-in Customer' }}
                </p>

                <p class="customer-detail">
                    Phone: {{ optional($customer)->phone ?? 'N/A' }}
                </p>

                <p class="customer-detail">
                    Address: {{ optional($customer)->address ?? 'Phnom Penh' }}
                </p>
            </div>

            <div>
                <h3 class="section-label">Payment Information</h3>

                <p class="customer-detail">
                    <strong>Invoice No:</strong>
                    US-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}
                </p>

                <p class="customer-detail">
                    <strong>Currency:</strong> USD
                </p>

                <p class="customer-detail">
                    <strong>Payment Status:</strong> {{ $status }}
                </p>
            </div>

        </section>

        <!-- Items Table -->
        <section>
            <div class="table-wrap">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">
                                QTY
                            </th>

                            <th style="width: 46%;">
                                DESCRIPTION
                            </th>

                            <th class="text-right" style="width: 22%;">
                                UNIT PRICE
                            </th>

                            <th class="text-right" style="width: 22%;">
                                AMOUNT
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($details as $item)
                            <tr>
                                <td class="text-center">
                                    {{ $item->quantity }}
                                </td>

                                <td>
                                    <span class="product-name">
                                        {{ optional($item->product)->name
                                            ?? 'Product unavailable' }}
                                    </span>
                                </td>

                                <td class="text-right">
                                    ${{ number_format($item->price, 2) }}
                                </td>

                                <td class="text-right">
                                    <strong>
                                        ${{ number_format($item->total, 2) }}
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    style="text-align:center; padding:30px;">
                                    No products found for this invoice.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Totals -->
        <section class="summary-layout">
            <div class="summary">

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span class="summary-value">
                        ${{ number_format($subtotal, 2) }}
                    </span>
                </div>

                @if($discount > 0)
                    <div class="summary-row">
                        <span>Discount ({{ $discount }}%)</span>

                        <span class="summary-value discount-value">
                            -${{ number_format($discountAmount, 2) }}
                        </span>
                    </div>
                @endif

                <div class="grand-total">
                    <span class="grand-total-label">TOTAL DUE</span>

                    <span class="grand-total-value">
                        ${{ number_format($grandTotal, 2) }}
                    </span>
                </div>

            </div>
        </section>

        <!-- Footer -->
        <footer class="invoice-footer">

            <div>
                <h3 class="footer-title">Terms & Conditions</h3>

                <p class="footer-text">
                    Payment is due within 15 days.
                </p>

                <p class="footer-text">
                    Please make checks payable to Technology Shop.
                </p>
            </div>

            <div class="signature">
                <h3 class="footer-title">Authorized By</h3>

                <div class="signature-line">
                    Authorized Signature
                </div>
            </div>

        </footer>

        <div class="thank-you">
            Thank you for shopping with Technology Shop.
        </div>

    </main>
</div>

<script>
    const paymentButton = document.getElementById(
        'confirm-payment-btn'
    );

    const paymentError = document.getElementById('payment-error');

    if (paymentButton) {
        paymentButton.addEventListener('click', async function () {
            const confirmed = window.confirm(
                'Are you sure you want to confirm this payment?'
            );

            if (!confirmed) {
                return;
            }

            paymentButton.disabled = true;
            paymentButton.textContent = 'Processing...';
            paymentError.style.display = 'none';

            try {
                const response = await fetch(paymentButton.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.message || 'Unable to confirm payment.'
                    );
                }

                window.alert(
                    data.message || 'Payment confirmed successfully!'
                );

                window.location.reload();

            } catch (error) {
                paymentError.textContent =
                    error.message || 'Something went wrong. Please try again.';

                paymentError.style.display = 'block';
                paymentButton.disabled = false;
                paymentButton.innerHTML = '&#10003; Confirm Payment';
            }
        });
    }
</script>

</body>
</html>
