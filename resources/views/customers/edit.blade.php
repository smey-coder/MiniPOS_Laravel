<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Edit Customer
                </h2>
                <p class="text-sm text-gray-500">
                    Update customer information
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
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">

                    <div class="flex items-center gap-4">

                        <div
                            class="w-16 h-16 rounded-full bg-white text-indigo-600 flex items-center justify-center text-2xl font-bold shadow">

                            {{ strtoupper(substr($customer->name,0,1)) }}

                        </div>

                        <div>
                            <h3 class="text-white text-xl font-bold">
                                {{ $customer->name }}
                            </h3>

                            <p class="text-blue-100 text-sm">
                                Customer ID: #{{ $customer->id }}
                            </p>
                        </div>

                    </div>

                </div>

                <!-- Form -->
                <form action="{{ route('customers.update', $customer->id) }}"
                      method="POST"
                      class="p-8">

                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Customer Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Customer Type
                            </label>

                            <select
                                name="customer_type_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                                @foreach($customerTypes as $type)

                                    <option
                                        value="{{ $type->id }}"
                                        {{ old('customer_type_id', $customer->customer_type_id) == $type->id ? 'selected' : '' }}>

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
                                value="{{ old('name', $customer->name) }}"
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
                                value="{{ old('phone', $customer->phone) }}"
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
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $customer->email) }}"
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
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $customer->address) }}</textarea>
                    </div>

                    <!-- City -->
                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            value="{{ old('city', $customer->city) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Customer Info -->
                    <div class="mt-6 bg-gray-50 rounded-xl p-4 border">
                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Created Date
                                </p>

                                <p class="font-semibold">
                                    {{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Current Discount
                                </p>

                                <p class="font-bold text-green-600 text-lg">
                                    {{ $customer->customer_type->discount_rate ?? 0 }}%
                                </p>
                            </div>

                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 mt-8">

                        <a href="{{ route('customers.index') }}"
                           class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">

                            ✏️ Update Customer

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
