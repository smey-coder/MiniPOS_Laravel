<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                Customers
            </h2>

            @can('create customers')
            <a href="{{route('customers.create')}}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg shadow">
                + Add Customer
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
                                <th class="px-6 py-3">#</th>
                                <th class="px-6 py-3">Client Type</th>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Phone</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Address</th>
                                <th class="px-6 py-3">City</th>
                                <th class="px-6 py-3">Discount</th>
                                <th class="px-6 py-3">Created</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                        @if($customers->isNotEmpty())
                            @foreach ($customers as $customer)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-700">
                                        {{$customer->id}}
                                    </td>

                                    {{-- <!-- Product Image -->
                                    <td class="px-6 py-4">
                                        @if($product->image)
                                            <img src="{{ asset('storage/products/'.$product->image) }}" 
                                                 alt="Product Image" 
                                                 class="w-12 h-12 object-cover rounded-lg">
                                        @else
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        @endif
                                    </td> --}}

                                    <td class="px-6 py-4 font-semibold">
                                        {{$customer->clientType}}
                                    </td>

                                    <td class="px-6 py-4 text-gray-500">
                                        {{$customer->name}}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{$customer->phone}}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{$customer->email}}
                                    </td>

                                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                                        {{$customer->address}}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600 font-medium">
                                        ${{$customer->city}}
                                    </td>

                                    <td class="px-6 py-4 text-green-600 font-semibold">
                                        %{{$customer->discount}}
                                    </td>

                                    <td class="px-6 py-4 text-red-500">
                                        {{\Carbon\Carbon::parse($customer->created_at)->format('d M, Y')}}
                                    </td>

                                    <td class="px-6 py-4 text-center space-x-2">
                                        @can('edit customers')
                                        <a href="{{ route('customers.edit', $customer->id)}}"
                                           class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-2 rounded-md">
                                            Edit
                                        </a>
                                        @endcan

                                        @can('delete customers')
                                        <a href="javascript:void(0)" onclick="deleteCustomer({{ $customer->id }})"
                                           class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-2 rounded-md">
                                            Delete
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="mt-6">
                {{ $customers->links() }}
            </div>

        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
          function deleteCustomer(id){
            if(confirm("Are you sure want to delete?")){
                $.ajax({
                    url : '{{ route("customers.destroy", ":id") }}'.replace(':id', id),
                    type: 'delete',
                    dataType: 'json',
                    headers: {
                        'x-csrf-token' : '{{ csrf_token() }}'
                    },
                    success: function(response){
                        window.location.href = '{{ route("customers.index")}}';
                    }
                });
            }
          }
        </script>
    </x-slot>
</x-app-layout>