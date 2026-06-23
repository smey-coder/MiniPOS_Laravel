<x-app-layout>

<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">
            Create Purchase Detail
        </h2>

        <a href="{{ route('purchase_details.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            Back
        </a>
    </div>
</x-slot>

<div class="max-w-5xl mx-auto py-8">

    <x-message></x-message>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6">
            <h3 class="text-white text-xl font-bold">
                Purchase Detail Information
            </h3>
            <p class="text-green-100">
                Add products to purchase invoice
            </p>
        </div>

        <form action="{{ route('purchase_details.store') }}"
              method="POST"
              class="p-6">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Purchase -->
                <div>
                    <label class="block mb-2 font-semibold">
                        Purchase
                    </label>

                    <select
                        name="purchase_id"
                        id="purchase_id"
                        class="w-full border rounded-lg p-3">

                        @foreach($purchases as $purchase)
                            <option
                                value="{{ $purchase->id }}"
                                data-total="{{ $purchase->total_amount ?? 0 }}">

                                {{ $purchase->purchase_code }}

                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- Current Total -->
                <div>
                    <label class="block mb-2 font-semibold">
                        Current Purchase Total
                    </label>

                    <input
                        type="text"
                        id="current_purchase_total"
                        class="w-full bg-gray-100 border rounded-lg p-3"
                        readonly>
                </div>

                <!-- Product -->
                <div>
                    <label class="block mb-2 font-semibold">
                        Product
                    </label>

                    <select
                        name="product_id"
                        class="w-full border rounded-lg p-3">

                        @foreach($products as $product)
                            <option
                                value="{{ $product->id }}"
                                data-price="{{ $product->cost_price ?? 0 }}">

                                {{ $product->name }}

                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block mb-2 font-semibold">
                        Quantity
                    </label>

                    <input
                        type="number"
                        min="1"
                        value="1"
                        name="quantity"
                        id="quantity"
                        class="w-full border rounded-lg p-3">
                </div>

                <!-- Unit Price -->
                <div>
                    <label class="block mb-2 font-semibold">
                        Unit Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="unit_price"
                        id="unit_price"
                        class="w-full border rounded-lg p-3">
                </div>

                <!-- Subtotal -->
                <div>
                    <label class="block mb-2 font-semibold">
                        Subtotal
                    </label>

                    <input
                        type="text"
                        id="subtotal"
                        class="w-full bg-green-50 border border-green-300 rounded-lg p-3 font-bold text-green-700"
                        readonly>
                </div>

            </div>

            <!-- Grand Total Preview -->
            <div class="mt-6">

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">

                    <h4 class="font-bold text-blue-700 mb-3">
                        Purchase Summary
                    </h4>

                    <div class="flex justify-between">
                        <span>Current Total:</span>
                        <span id="purchase_total">$0.00</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Subtract Item:</span>
                        <span id="new_item_total">$0.00</span>
                    </div>

                    <hr class="my-2">

                    <div class="flex justify-between text-lg font-bold text-green-600">
                        <span>Remaining Total:</span>
                        <span id="grand_total">$0.00</span>
                    </div>

                </div>

            </div>

            <!-- Description -->
            <div class="mt-6">

                <label class="block mb-2 font-semibold">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full border rounded-lg p-3"></textarea>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-8">

                <a href="{{ route('purchase_details.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

                    Save Purchase Detail

                </button>

            </div>

        </form>

    </div>

</div>

<script>

function calculateTotal()
{
    let qty = parseFloat(document.getElementById('quantity').value || 0);
    let price = parseFloat(document.getElementById('unit_price').value || 0);

    let subtotal = qty * price;

    document.getElementById('subtotal').value = subtotal.toFixed(2);

    let purchaseSelect = document.getElementById('purchase_id');

    let currentTotal = parseFloat(
        purchaseSelect.options[purchaseSelect.selectedIndex].dataset.total || 0
    );

    document.getElementById('current_purchase_total').value =
        currentTotal.toFixed(2);

    document.getElementById('purchase_total').innerText =
        '$' + currentTotal.toFixed(2);

    document.getElementById('new_item_total').innerText =
        '$' + subtotal.toFixed(2);

    let grandTotal = currentTotal - subtotal;

    document.getElementById('grand_total').innerText =
        '$' + grandTotal.toFixed(2);
}

document.addEventListener('input', calculateTotal);

document.getElementById('purchase_id')
    .addEventListener('change', calculateTotal);

window.onload = calculateTotal;

</script>
</x-app-layout>
