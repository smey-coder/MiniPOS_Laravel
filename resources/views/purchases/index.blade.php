<x-app-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Purchase Management
        </h2>

        <a href="{{ route('purchases.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            + New Purchase
        </a>
    </div>
</x-slot>
<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"
        <div class="bg-white rounded-xl shadow border">
            <div class="p-4 border-b">
                <form method="GET" action="{{ route('purchases.index') }}">
                    <div class="grid grid-cols-5 gap-3 mb-4">
                        <!-- Supplier -->
                        <select name="supplier_id" class="form-control">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Employee -->
                        <select name="employee_id" class="form-control">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Status -->
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
                            <option value="Received" {{ request('status')=='Received'?'selected':'' }}>Received</option>
                            <option value="Cancelled" {{ request('status')=='Cancelled'?'selected':'' }}>Cancelled</option>
                        </select>

                        <!-- From Date -->
                        <input type="date"
                            name="from_date"
                            value="{{ request('from_date') }}"
                            class="form-control">

                        <!-- To Date -->
                        <input type="date"
                            name="to_date"
                            value="{{ request('to_date') }}"
                            class="form-control">

                    </div>

                    <div class="flex gap-2 mb-4">

                        <button class="px-4 py-2 bg-blue-600 text-white rounded">
                            Filter
                        </button>

                        <a href="{{ route('purchases.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded">
                            Reset
                        </a>

                    </div>

                </form>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="px-4 py-3 text-left">#</th>

                            <th class="px-4 py-3 text-left">
                                Purchase Code
                            </th>

                            <th class="px-4 py-3 text-left">
                                Supplier
                            </th>

                            <th class="px-4 py-3 text-left">
                                Employee
                            </th>

                            <th class="px-4 py-3 text-left">
                                Date
                            </th>

                            <th class="px-4 py-3 text-right">
                                Amount
                            </th>

                            <th class="px-4 py-3 text-center">
                                Status
                            </th>

                            <th class="px-4 py-3 text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($purchases as $purchase)

                            <tr class="hover:bg-gray-50">

                                <td class="px-4 py-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-3 font-medium">
                                    {{ $purchase->purchase_code }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $purchase->supplier->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $purchase->employee->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $purchase->purchase_date }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    $
                                    {{ number_format($purchase->total_amount,2) }}
                                </td>

                                <td class="px-4 py-3 text-center">

                                    @if($purchase->purchase_status == 'Received')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                            Received 
                                        </span>

                                    @elseif($purchase->purchase_status == 'Pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                                            Pending
                                        </span>

                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                                            Cancelled
                                        </span>
                                    @endif

                                </td>

                                <td class="px-4 py-3">

                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('purchases.show',$purchase->id) }}"
                                           class="px-3 py-1 bg-sky-500 text-white rounded">

                                            View

                                        </a>

                                        <a href="{{ route('purchases.edit',$purchase->id) }}"
                                           class="px-3 py-1 bg-amber-500 text-white rounded">

                                            Edit

                                        </a>

                                        <form action="{{ route('purchases.destroy', $purchase->id) }}"
                                            method="POST"
                                            class="inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    class="delete-btn px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm transition">

                                                <i class="fa-solid fa-trash"></i>
                                                Delete

                                            </button>

                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>

                                <td colspan="8"
                                    class="text-center py-8 text-gray-500">

                                    No purchase records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="mt-6">

            {{ $purchases->links() }}

        </div>

    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e){
        e.preventDefault();
        let form = this.closest('form');

        Swal.fire({

            title: 'Delete Student?',

            text: 'This student record will be permanently deleted.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#d33',

            cancelButtonColor: '#3085d6',

            confirmButtonText: 'Yes, Delete',

            cancelButtonText: 'Cancel'

        }).then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});

</script>
</x-app-layout>
