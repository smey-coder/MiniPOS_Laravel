<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl">
        Purchase Detail
    </h2>
</x-slot>

<div class="py-8">

    <div class="max-w-4xl mx-auto">

        <div class="bg-white shadow rounded-lg p-6">

            <table class="min-w-full">

                <tr>
                    <td><strong>Purchase Code</strong></td>
                    <td>{{ $purchase->purchase_code }}</td>
                </tr>

                <tr>
                    <td><strong>Supplier</strong></td>
                    <td>{{ $purchase->supplier->name ?? '-' }}</td>
                </tr>

                <tr>
                    <td><strong>Employee</strong></td>
                    <td>{{ $purchase->employee->name ?? '-' }}</td>
                </tr>

                <tr>
                    <td><strong>Date</strong></td>
                    <td>{{ $purchase->purchase_date }}</td>
                </tr>

                <tr>
                    <td><strong>Total Amount</strong></td>
                    <td>${{ number_format($purchase->total_amount,2) }}</td>
                </tr>

                <tr>
                    <td><strong>Status</strong></td>
                    <td>{{ $purchase->purchase_status }}</td>
                </tr>

                <tr>
                    <td><strong>Note</strong></td>
                    <td>{{ $purchase->note }}</td>
                </tr>

            </table>

            <div class="mt-6">

                <a href="{{ route('purchases.index') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded">

                    Back

                </a>

            </div>

        </div>

    </div>

</div>

</x-app-layout>
