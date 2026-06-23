<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-2xl">
            Edit Purchase Detail
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">

        <x-message></x-message>

        <div class="bg-white shadow rounded-xl p-6">

            <form action="{{ route('purchase_details.update',$purchaseDetail->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">

                    <div>

                        <label>Purchase</label>

                        <select name="purchase_id"
                                class="w-full border rounded-lg p-2">

                            @foreach($purchases as $purchase)

                            <option value="{{ $purchase->id }}"
                                {{ $purchaseDetail->purchase_id == $purchase->id ? 'selected' : '' }}>

                                {{ $purchase->purchase_code }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label>Product</label>

                        <select name="product_id"
                                class="w-full border rounded-lg p-2">

                            @foreach($products as $product)

                            <option value="{{ $product->id }}"
                                {{ $purchaseDetail->product_id == $product->id ? 'selected' : '' }}>

                                {{ $product->name }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label>Quantity</label>

                        <input type="number"
                               name="quantity"
                               id="quantity"
                               value="{{ $purchaseDetail->quantity }}"
                               class="w-full border rounded-lg p-2">

                    </div>

                    <div>

                        <label>Unit Price</label>

                        <input type="number"
                               step="0.01"
                               name="unit_price"
                               id="unit_price"
                               value="{{ $purchaseDetail->unit_price }}"
                               class="w-full border rounded-lg p-2">

                    </div>

                    <div>

                        <label>Subtotal</label>

                        <input type="text"
                               id="subtotal"
                               value="{{ $purchaseDetail->subtotal }}"
                               class="w-full border rounded-lg p-2 bg-gray-100"
                               readonly>

                    </div>

                </div>

                <div class="mt-4">

                    <label>Description</label>

                    <textarea name="description"
                              class="w-full border rounded-lg p-2">{{ $purchaseDetail->description }}</textarea>

                </div>

                <div class="mt-6">

                    <button class="bg-blue-600 text-white px-5 py-2 rounded-lg">
                        Update
                    </button>

                    <a href="{{ route('purchase_details.index') }}"
                       class="bg-gray-500 text-white px-5 py-2 rounded-lg">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

<script>
document.addEventListener('input', function(){

    let qty =
        document.getElementById('quantity').value || 0;

    let price =
        document.getElementById('unit_price').value || 0;

    document.getElementById('subtotal').value =
        (qty * price).toFixed(2);

});
</script>

</x-app-layout>
