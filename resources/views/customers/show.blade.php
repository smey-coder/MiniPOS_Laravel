<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                Customer Details
            </h2>
            <a href="{{ route('customers.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow">
                ← Back
            </a>
        </div>
    </x-slot>
    <div class="py-10">
        <div class="max-w-5xl mx-auto px-6">

            <!-- Customer Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white">

                    <div class="flex items-center gap-5">

                        <div
                            class="w-20 h-20 rounded-full bg-white text-indigo-600 flex items-center justify-center text-3xl font-bold shadow">

                            {{ strtoupper(substr($customer->name,0,1)) }}

                        </div>

                        <div>
                            <h1 class="text-3xl font-bold">
                                {{ $customer->name }}
                            </h1>

                            <p class="text-indigo-100">
                                {{ $customer->customerType->name ?? 'Customer' }}
                            </p>
                        </div>

                    </div>

                </div>

                <!-- Body -->
                <div class="p-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="bg-gray-50 p-4 rounded-xl">
                            <p class="text-sm text-gray-500">
                                Customer Type
                            </p>

                            <p class="font-semibold text-lg">
                                {{ $customer->customer_type->name ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl">
                            <p class="text-sm text-gray-500">
                                Phone Number
                            </p>

                            <p class="font-semibold text-lg">
                                {{ $customer->phone }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl">
                            <p class="text-sm text-gray-500">
                                Email Address
                            </p>

                            <p class="font-semibold text-lg">
                                {{ $customer->email ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl">
                            <p class="text-sm text-gray-500">
                                City
                            </p>

                            <p class="font-semibold text-lg">
                                {{ $customer->city ?? 'N/A' }}
                            </p>
                        </div>

                    </div>

                    <!-- Address -->
                    <div class="mt-6 bg-gray-50 p-4 rounded-xl">

                        <p class="text-sm text-gray-500 mb-1">
                            Address
                        </p>

                        <p class="font-medium">
                            {{ $customer->address ?? 'N/A' }}
                        </p>

                    </div>

                    <!-- Discount -->
                    <div class="mt-6">

                        <div
                            class="bg-green-100 border border-green-200 rounded-xl p-5 flex justify-between items-center">

                            <div>
                                <p class="text-sm text-green-700">
                                    Customer Discount
                                </p>

                                <h3 class="text-3xl font-bold text-green-600">
                                    {{ $customer->customer_type->discount_rate ?? 0 }}%
                                </h3>
                            </div>

                            <div class="text-5xl">
                                🎉
                            </div>

                        </div>

                    </div>

                    <!-- Footer -->
                    <div
                        class="mt-8 pt-6 border-t flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">
                                Created Date
                            </p>
                            <p class="font-semibold">
                                {{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('customers.edit',$customer->id) }}"
                               class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow">
                                Edit Customer
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
