<x-app-layout>

    <div class="py-6">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('products.index') }}"
                   class="px-4 py-2 bg-gray-600 hover:bg-blue-700 text-white rounded-xl">
                    ← Back to Products
                </a>
            </div>

            {{-- Product Card --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Image --}}
                <div class="bg-white rounded-2xl shadow border p-6 flex items-center justify-center">

                    @if($product->image)
                        <img src="{{ asset('storage/products/'.$product->image) }}"
                             class="w-full max-h-[400px] object-contain rounded-xl">
                    @else
                        <div class="text-gray-400 text-lg">
                            No Image Available
                        </div>
                    @endif

                </div>

                {{-- Details --}}
                <div class="bg-white rounded-2xl shadow border p-6 space-y-4">

                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ $product->name }}
                    </h1>

                    <p class="text-gray-500">
                        {{ $product->description ?? 'No description available.' }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 text-sm">

                        <div>
                            <p class="text-gray-500">Barcode</p>
                            <p class="font-semibold">{{ $product->barcode ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-gray-500">Type</p>
                            <p class="font-semibold">
                                {{ $product->productType?->name ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Supplier</p>
                            <p class="font-semibold">
                                {{ $product->supplier?->name ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Quantity</p>
                            <p class="font-semibold">
                                {{ $product->quantity }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Cost Price</p>
                            <p class="font-semibold text-red-600">
                                ${{ number_format($product->cost_price,2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Sell Price</p>
                            <p class="font-semibold text-green-600">
                                ${{ number_format($product->sell_price,2) }}
                            </p>
                        </div>

                    </div>

                    {{-- Status --}}
                    <div>
                        @if($product->quantity > 0)
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                In Stock
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 pt-4">

                        <a href="{{ route('products.edit', $product->id) }}"
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">
                            Edit
                        </a>

                        <button onclick="deleteProduct({{ $product->id }})"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl">
                            Delete
                        </button>

                    </div>

                </div>

            </div>

        </div>
    </div>

    {{-- Delete Script --}}
    <script>
        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                fetch(`/products/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        window.location.href = "{{ route('products.index') }}";
                    } else {
                        alert(data.message);
                    }
                });
            }
        }
    </script>

</x-app-layout>