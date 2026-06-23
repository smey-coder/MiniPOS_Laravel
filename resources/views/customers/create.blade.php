<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Create Customer
                </h2>
                <p class="text-sm text-gray-500">
                    Add a new customer to your system
                </p>
            </div>

            <a href="{{ route('customers.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-6">

            <x-message />

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
                    <h3 class="text-white text-xl font-bold">
                        Customer Information
                    </h3>
                    <p class="text-indigo-100 text-sm">
                        Fill in the customer details below
                    </p>
                </div>

                <form action="{{ route('customers.store') }}"
                      method="POST"
                      class="p-8">

                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Customer Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Customer Type
                            </label>

                            <select name="customer_type_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                                <option value="">
                                    -- Select Customer Type --
                                </option>

                                @foreach($customerTypes as $type)
                                    <option
                                        value="{{ $type->id }}"
                                        {{ old('customer_type_id') == $type->id ? 'selected' : '' }}>

                                        {{ $type->name }} ({{ $type->discount_rate }}%)

                                    </option>
                                @endforeach

                            </select>

                            @error('customer_type_id')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter customer name"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                            @error('name')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Enter phone number"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Enter email address"
                                autocomplete="off"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                            @error('email')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Address
                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            placeholder="Enter customer address"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('address') }}</textarea>
                    </div>

                    <!-- City -->
                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            value="{{ old('city') }}"
                            placeholder="Enter city"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 mt-8">

                        <a href="{{ route('customers.index') }}"
                           class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow">

                            💾 Save Customer

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
