<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl">
        Create Purchase
    </h2>
</x-slot>
<div class="py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('purchases.store') }}"
                  method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label>Purchase Code</label>
                        <input type="text"
                               name="purchase_code"
                               value="{{ old('purchase_code') }}"
                               class="w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label>Purchase Date</label>
                        <input type="date"
                               name="purchase_date"
                               class="w-full rounded border-gray-300">
                    </div>
                    <div>
                        <label>Supplier</label>
                        <select name="supplier_id"
                                class="w-full rounded border-gray-300">

                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
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
                                <option value="{{ $employee->id }}">
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
                               class="w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label>Status</label>

                        <select name="purchase_status"
                                class="w-full rounded border-gray-300">

                            <option value="Pending">
                                Pending
                            </option>

                            <option value="Received">
                                Received
                            </option>

                            <option value="Cancelled">
                                Cancelled
                            </option>

                        </select>

                    </div>

                </div>

                <div class="mt-4">

                    <label>Note</label>

                    <textarea name="note"
                              rows="4"
                              class="w-full rounded border-gray-300"></textarea>

                </div>
                <div class="mt-6 flex gap-2">
                    <button
                        class="px-4 py-2 bg-green-600 text-white rounded">
                        Save
                    </button>

                    <a href="{{ route('purchases.index') }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
