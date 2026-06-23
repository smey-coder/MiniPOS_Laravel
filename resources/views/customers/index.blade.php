<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Customer Management
                </h2>
                <p class="text-sm text-gray-500">
                    Manage customer information and discounts
                </p>
            </div>

            @can('create customers')
            <a href="{{ route('customers.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow">
                + Add Customer
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message />
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

                <div class="bg-white rounded-xl shadow p-5 border">
                    <p class="text-gray-500 text-sm">Total Customers</p>
                    <h3 class="text-3xl font-bold text-indigo-600">
                        {{ $customers->total() }}
                    </h3>
                </div>

                <div class="bg-white rounded-xl shadow p-5 border">
                    <p class="text-gray-500 text-sm">VIP Customers</p>
                    <h3 class="text-3xl font-bold text-green-600">
                        {{ $customers->where('customer_type.name','==','VIP')->count() }}
                    </h3>
                </div>

                <div class="bg-white rounded-xl shadow p-5 border">
                    <p class="text-gray-500 text-sm">Customer Types</p>
                    <h3 class="text-3xl font-bold text-orange-500">
                        {{ \App\Models\Customer_Type::count() }}
                    </h3>
                </div>

            </div>

            <!-- Search -->
            <div class="bg-white rounded-xl shadow border p-4 mb-5">
                <form method="GET">
                    <div class="flex gap-3">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name, phone, email..."
                            class="w-full border rounded-lg px-4 py-2">

                        <button
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 rounded-lg">
                            Search
                        </button>

                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow border overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left">#</th>
                                <th class="px-6 py-4 text-left">Customer</th>
                                <th class="px-6 py-4 text-left">Type</th>
                                <th class="px-6 py-4 text-left">Phone</th>
                                <th class="px-6 py-4 text-left">City</th>
                                <th class="px-6 py-4 text-left">Discount</th>
                                <th class="px-6 py-4 text-left">Created</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                        @forelse($customers as $customer)

                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    {{ $customer->id }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">

                                            {{ strtoupper(substr($customer->name,0,1)) }}

                                        </div>

                                        <div>
                                            <p class="font-semibold">
                                                {{ $customer->name }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ $customer->email ?? 'No Email' }}
                                            </p>
                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-4">

                                    <span
                                        class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">

                                        {{ $customer->customer_type->name ?? 'N/A' }}

                                    </span>

                                </td>

                                <td class="px-6 py-4">
                                    {{ $customer->phone }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $customer->city ?? '-' }}
                                </td>

                                <td class="px-6 py-4">

                                    <span
                                        class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">

                                        {{ $customer->customer_type->discount_rate ?? 0 }}%

                                    </span>

                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $customer->created_at->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('customers.show',$customer->id) }}"
                                            class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded">
                                            View
                                        </a>

                                        @can('edit customers')
                                        <a href="{{ route('customers.edit',$customer->id) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded">
                                            Edit
                                        </a>
                                        @endcan

                                        @can('delete customers')
                                        <button
                                            onclick="deleteCustomer({{ $customer->id }})"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">
                                            Delete
                                        </button>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="py-12 text-center">

                                    <div class="text-gray-400">

                                        <div class="text-5xl mb-3">
                                            👥
                                        </div>

                                        <h3 class="text-lg font-semibold">
                                            No Customers Found
                                        </h3>

                                        <p>
                                            Start by creating your first customer.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

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
        <script>
            function deleteCustomer(id) {
                if(confirm('Are you sure you want to delete this customer?')) {

                    $.ajax({
                        url: '{{ route("customers.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            location.reload();
                        }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>