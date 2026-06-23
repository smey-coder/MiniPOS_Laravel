
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-2xl">
            Purchase Detail Information
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">

        <div class="bg-white shadow rounded-xl p-6">

            <table class="table-auto w-full">

                <tr>
                    <th>Purchase Code</th>
                    <td>{{ $purchaseDetail->purchase->purchase_code }}</td>
                </tr>

                <tr>
                    <th>Supplier</th>
                    <td>{{ $purchaseDetail->purchase->supplier->name }}</td>
                </tr>

                <tr>
                    <th>Employee</th>
                    <td>{{ $purchaseDetail->purchase->employee->name }}</td>
                </tr>

                <tr>
                    <th>Product</th>
                    <td>{{ $purchaseDetail->product->name }}</td>
                </tr>

                <tr>
                    <th>Quantity</th>
                    <td>{{ $purchaseDetail->quantity }}</td>
                </tr>

                <tr>
                    <th>Unit Price</th>
                    <td>${{ number_format($purchaseDetail->unit_price,2) }}</td>
                </tr>

                <tr>
                    <th>Subtotal</th>
                    <td>${{ number_format($purchaseDetail->subtotal,2) }}</td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td>{{ $purchaseDetail->description }}</td>
                </tr>

            </table>

            <div class="mt-6">

                <a href="{{ route('purchase_details.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg">

                    Back

                </a>

            </div>

        </div>

    </div>

</x-app-layout>

