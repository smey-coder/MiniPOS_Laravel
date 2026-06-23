<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl">
        Edit Purchase
    </h2>
</x-slot>

<div class="py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('purchases.update',$purchase->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label>Purchase Code</label>

                        <input type="text"
                               name="purchase_code"
                               value="{{ $purchase->purchase_code }}"
                               class="w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label>Purchase Date</label>

                        <input type="date"
                               name="purchase_date"
                               value="{{ $purchase->purchase_date }}"
                               class="w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label>Supplier</label>

                        <select name="supplier_id"
                                class="w-full rounded border-gray-300">

                            @foreach($suppliers as $supplier)

                            <option value="{{ $supplier->id }}"
                                {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>

                                {{ $supplier->name }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>
                        <label>Employee</label>

                        <select name="employee_id"
                                class="w-full rounded border-gray-300">

                            @foreach($employees as $employee)

                            <option value="{{ $employee->id }}"
                                {{ $purchase->employee_id == $employee->id ? 'selected' : '' }}>

                                {{ $employee->name }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>
                        <label>Total Amount</label>

                        <input type="number"
                               step="0.01"
                               name="total_amount"
                               value="{{ $purchase->total_amount }}"
                               class="w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label>Status</label>

                        <select name="purchase_status"
                                class="w-full rounded border-gray-300">

                            <option value="Pending"
                                {{ $purchase->purchase_status == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Received"
                                {{ $purchase->purchase_status == 'Received' ? 'selected' : '' }}>
                                Received
                            </option>

                            <option value="Cancelled"
                                {{ $purchase->purchase_status == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>

                </div>

                <div class="mt-4">

                    <label>Note</label>

                    <textarea name="note"
                              rows="4"
                              class="w-full rounded border-gray-300">{{ $purchase->note }}</textarea>

                </div>

                <div class="mt-6 flex gap-2">

                    <button
                        class="px-4 py-2 bg-blue-600 text-white rounded">

                        Update

                    </button>

                    <a href="{{ route('purchases.index') }}"
                       class="px-4 py-2 bg-gray-600 text-white rounded">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>
