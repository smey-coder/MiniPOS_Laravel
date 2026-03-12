<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                Product / Edit
            </h2>

            <a href="{{ route('products.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg shadow">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border">

                <div class="p-8 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Product Name -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Product Name</label>
                                <input
                                    value="{{ old('name',$product->name) }}"
                                    type="text"
                                    name="name"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Enter Product Name">
                                @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Barcode -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Barcode</label>
                                <input
                                    value="{{ old('barcode',$product->barcode) }}"
                                    type="text"
                                    name="barcode"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Scan or Enter Barcode">
                                @error('barcode')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Product Type -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Product Type</label>
                                <select name="product_type_id"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Select Type</option>
                                    @foreach ($Product_Types as $type)
                                        <option value="{{ $type->id }}"
                                            {{ $product->product_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_type_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Supplier</label>
                                <select name="supplier_id"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cost Price -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Cost Price</label>
                                <input
                                    value="{{ old('cost_price',$product->cost_price) }}"
                                    type="number"
                                    step="0.01"
                                    name="cost_price"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                                @error('cost_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sell Price -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Sell Price</label>
                                <input
                                    value="{{ old('sell_price',$product->sell_price) }}"
                                    type="number"
                                    step="0.01"
                                    name="sell_price"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                                @error('sell_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">Quantity</label>
                                <input
                                    value="{{ old('quantity',$product->quantity) }}"
                                    type="number"
                                    name="quantity"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                                @error('quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Product Image -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold mb-2">Product Image</label>
                                <div class="flex items-center gap-4">
                                    <input type="file" name="image" id="image" 
                                        class="border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
                                        onchange="previewImage(event)">
                                    
                                    @if($product->image)
                                        <img id="preview" src="{{ asset('storage/products/'.$product->image) }}" 
                                             class="w-24 h-24 object-cover rounded border">
                                    @else
                                        <img id="preview" class="w-24 h-24 object-cover rounded border hidden">
                                    @endif
                                </div>
                                @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label class="block text-sm font-semibold mb-2">Description</label>
                            <textarea
                                name="description"
                                rows="4"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
                                placeholder="Enter product description">{{ old('description',$product->description) }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('products.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg shadow">
                                Cancel
                            </a>

                            <button
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg shadow font-medium">
                                Update Product
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <x-slot name="script">
        <script>
            function previewImage(event) {
                const preview = document.getElementById('preview');
                const file = event.target.files[0];
                if(file) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                }
            }
        </script>
    </x-slot>

</x-app-layout>