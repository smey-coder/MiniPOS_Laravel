<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200">
                Products
            </h2>

            @can('create products')
            <a href="{{route('products.create')}}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-3 py-2 sm:px-4 sm:py-2 rounded-lg shadow w-full sm:w-auto text-center">
                + Add Product
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-message></x-message>

            <div class="bg-white rounded-xl shadow overflow-hidden border">

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-2 sm:px-6 py-3">#</th>
                                <th class="px-2 sm:px-6 py-3 hidden md:table-cell">Image</th>
                                <th class="px-2 sm:px-6 py-3">Name</th>
                                <th class="px-2 sm:px-6 py-3 hidden lg:table-cell">Barcode</th>
                                <th class="px-2 sm:px-6 py-3 hidden xl:table-cell">Product Type</th>
                                <th class="px-2 sm:px-6 py-3 hidden xl:table-cell">Supplier</th>
                                <th class="px-2 sm:px-6 py-3 hidden lg:table-cell">Description</th>
                                <th class="px-2 sm:px-6 py-3 hidden sm:table-cell">Cost Price</th>
                                <th class="px-2 sm:px-6 py-3">Sell Price</th>
                                <th class="px-2 sm:px-6 py-3">Stock</th>
                                <th class="px-2 sm:px-6 py-3 hidden md:table-cell">Created</th>
                                <th class="px-2 sm:px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                        @if($products->isNotEmpty())
                            @foreach ($products as $product)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-2 sm:px-6 py-4 font-medium text-gray-700">
                                        {{$product->id}}
                                    </td>

                                    <!-- Product Image -->
                                    <td class="px-2 sm:px-6 py-4 hidden md:table-cell">
                                        @if($product->image)
                                            <img src="{{ asset('storage/products/'.$product->image) }}" 
                                                 alt="Product Image" 
                                                 class="w-8 h-8 sm:w-12 sm:h-12 object-cover rounded-lg">
                                        @else
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        @endif
                                    </td>

                                    <td class="px-2 sm:px-6 py-4 font-semibold">
                                        {{$product->name}}
                                    </td>

                                    <td class="px-2 sm:px-6 py-4 text-gray-500 hidden lg:table-cell">
                                        {{$product->barcode}}
                                    </td>

                                    <td class="px-2 sm:px-6 py-4 hidden xl:table-cell">
                                        {{$product->productType->name ?? '-'}}
                                    </td>

                                    <td class="px-2 sm:px-6 py-4 hidden xl:table-cell">
                                        {{$product->supplier->name ?? '-'}}
                                    </td>

                                    <td class="px-2 sm:px-6 py-4 text-gray-500 max-w-xs truncate hidden lg:table-cell">
                                        {{$product->description}}
                                    </td>

                                    <td class="px-2 sm:px-6 py-4 text-red-600 font-medium hidden sm:table-cell">
                                        ${{$product->cost_price}}
                                    </td>

                                    <td class="px-2 sm:px-6 py-4 text-green-600 font-semibold">
                                        ${{$product->sell_price}}
                                    </td>

                                    <td class="px-2 sm:px-6 py-4">
                                        <span class="px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs font-semibold
                                            {{$product->quantity > 10 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'}}">
                                            {{$product->quantity}}
                                        </span>
                                    </td>

                                    <td class="px-2 sm:px-6 py-4 text-gray-500 hidden md:table-cell">
                                        {{\Carbon\Carbon::parse($product->created_at)->format('d M, Y')}}
                                    </td>

                                    <td class="px-2 py-4 text-center">
                                        <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 justify-center">
                                            @can('edit products')
                                            <a href="{{ route('products.edit', $product->id)}}"
                                               class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-2 py-1 sm:px-3 sm:py-2 rounded-md">
                                                Edit
                                            </a>
                                            @endcan

                                            @can('delete products')
                                            <a href="javascript:void(0)" onclick="deleteProduct({{ $product->id }})"
                                               class="bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 sm:px-3 sm:py-2 rounded-md">
                                                Delete
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>

        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
          function deleteProduct(id){
            if(confirm("Are you sure want to delete?")){
                $.ajax({
                    url : '{{ route("products.destroy", ":id") }}'.replace(':id', id),
                    type: 'delete',
                    dataType: 'json',
                    headers: {
                        'x-csrf-token' : '{{ csrf_token() }}'
                    },
                    success: function(response){
                        window.location.href = '{{ route("products.index")}}';
                    }
                });
            }
          }
        </script>
    </x-slot>
</x-app-layout>