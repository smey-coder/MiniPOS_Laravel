<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                Purchase Management
            </h2>

            <a href="{{ route('purchase_details.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                + New Purchase
            </a>
        </div>
    </x-slot>

    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-message></x-message>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                <div class="bg-white shadow rounded-xl p-5">
                    <h5 class="text-gray-500">Total Purchases</h5>
                    <h2 class="text-3xl font-bold text-indigo-600">
                        {{ $purchaseDetails->total() }}
                    </h2>
                </div>

                <div class="bg-green-50 shadow rounded-xl p-5">
                    <h5 class="text-green-600">Received</h5>
                    <h2 class="text-3xl font-bold text-green-700">
                        {{ $receivedCount ?? 0 }}
                    </h2>
                </div>

                <div class="bg-yellow-50 shadow rounded-xl p-5">
                    <h5 class="text-yellow-600">Pending</h5>
                    <h2 class="text-3xl font-bold text-yellow-700">
                        {{ $pendingCount ?? 0 }}
                    </h2>
                </div>

                <div class="bg-red-50 shadow rounded-xl p-5">
                    <h5 class="text-red-600">Cancelled</h5>
                    <h2 class="text-3xl font-bold text-red-700">
                        {{ $cancelledCount ?? 0 }}
                    </h2>
                </div>

            </div>

            <!-- Filter Card -->
            <div class="bg-white shadow rounded-xl border mb-6">

                <div class="p-5 border-b">
                    <h3 class="font-semibold text-lg">
                        Advanced Filter
                    </h3>
                </div>

                <div class="p-5">

                    <form method="GET"
                          action="{{ route('purchase_details.index') }}">

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">

                            <!-- Search -->
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search purchases, suppliers, products..."
                                   class="border rounded-lg px-3 py-2 w-full" />

                            <!-- Supplier -->
                            <select name="supplier_id"
                                    class="border rounded-lg px-3 py-2">

                                <option value="">
                                    All Suppliers
                                </option>

                                @foreach($suppliers as $supplier)

                                    <option value="{{ $supplier->id }}"
                                        {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>

                                @endforeach

                            </select>

                            <!-- Employee -->
                            <select name="employee_id"
                                    class="border rounded-lg px-3 py-2">

                                <option value="">
                                    All Employees
                                </option>

                                @foreach($employees as $employee)

                                    <option value="{{ $employee->id }}"
                                        {{ request('employee_id') == $employee->id ? 'selected' : '' }}>

                                        {{ $employee->name }}

                                    </option>

                                @endforeach

                            </select>

                            <!-- Status -->
                            <select name="status"
                                    class="border rounded-lg px-3 py-2">

                                <option value="">
                                    All Status
                                </option>

                                <option value="Pending"
                                    {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="Completed"
                                    {{ request('status') == 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                                <option value="Cancelled"
                                    {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>

                            </select>

                            <!-- From Date -->
                            <input type="date"
                                   name="from_date"
                                   value="{{ request('from_date') }}"
                                   class="border rounded-lg px-3 py-2">

                            <!-- To Date -->
                            <input type="date"
                                   name="to_date"
                                   value="{{ request('to_date') }}"
                                   class="border rounded-lg px-3 py-2">

                        </div>

                        <div class="mt-4 flex gap-2">

                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">

                                Filter

                            </button>

                            <a href="{{ route('purchase_details.index') }}"
                               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">

                                Reset

                            </a>

                        </div>

                    </form>

                </div>

            </div>

            <!-- Table -->
            <div class="bg-white shadow rounded-xl border overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-indigo-600 text-white">

                            <tr>

                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Purchase Code</th>
                                <th class="px-4 py-3 text-left">Supplier</th>
                                <th class="px-4 py-3 text-left">Employee</th>
                                <th class="px-4 py-3 text-left">Product</th>
                                <th class="px-4 py-3 text-left">Quantity</th>
                                <th class="px-4 py-3 text-left">Unit Price</th>
                                <th class="px-4 py-3 text-left">Sub Total</th>
                                <th class="px-4 py-3 text-left">Date</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($purchaseDetails as $data)

                                <tr class="border-b hover:bg-gray-50">

                                    <td class="px-4 py-3">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-4 py-3 font-medium">
                                        {{ $data->purchase->purchase_code ?? '-'}}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $data->purchase->supplier->name ?? '-'}}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $data->purchase->employee->name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $data->product->name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $data->quantity ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $data->unit_price ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                            ${{ number_format($data->subtotal, 2) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $data->purchase->purchase_date ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">

                                        @if($data->purchase->purchase_status == 'Received')

                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                                Received
                                            </span>

                                        @elseif($data->purchase->purchase_status == 'Pending')

                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                                Pending
                                            </span>

                                        @else

                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                                Cancelled
                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-4 py-3">

                                        <div class="flex justify-center gap-2">

                                            <a href="{{ route('purchase_details.show',$data->id) }}"
                                               class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg text-sm">

                                                View

                                            </a>

                                            <a href="{{ route('purchase_details.edit',$data->id) }}"
                                               class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg text-sm">

                                                Edit

                                            </a>

                                            <form action="{{ route('purchase_details.destroy',$data->id) }}"
                                                  method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                        class="delete-btn bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm">

                                                    Delete

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="text-center py-12">

                                        <div class="text-5xl mb-3">
                                            📦
                                        </div>

                                        <h4 class="font-semibold text-gray-600">
                                            No Purchase Records Found
                                        </h4>

                                        <p class="text-gray-400">
                                            Create your first purchase record.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="mt-6">
                {{ $purchaseDetails->links() }}
            </div>

        </div>

    </div>

</x-app-layout>

