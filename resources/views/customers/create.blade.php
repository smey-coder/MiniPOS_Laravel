<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">

    <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
    Customer / Create
    </h2>

    <a href="{{ route('customers.index') }}"
    class="bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg shadow">
    Back
    </a>

    </div>
    </x-slot>

    <div class="py-10">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border">

    <div class="p-8 text-gray-900 dark:text-gray-100">

    <form action="{{ route('customers.store') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Client Type -->
    <div>
    <label class="block text-sm font-semibold mb-2">
    Client Type
    </label>

    <select name="clientType"
    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">

    <option value="">Select Client Type</option>
    <option value="Retail" {{ old('clientType')=='Retail'?'selected':'' }}>Retail</option>
    <option value="Wholesale" {{ old('clientType')=='Wholesale'?'selected':'' }}>Wholesale</option>
    <option value="VIP" {{ old('clientType')=='VIP'?'selected':'' }}>VIP</option>

    </select>

    @error('clientType')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    </div>

    <!-- Customer Name -->
    <div>
    <label class="block text-sm font-semibold mb-2">
    Customer Name
    </label>

    <input
    value="{{ old('name') }}"
    type="text"
    name="name"
    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
    placeholder="Enter customer name">

    @error('name')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    </div>

    <!-- Phone -->
    <div>
    <label class="block text-sm font-semibold mb-2">
    Phone Number
    </label>

    <input
    value="{{ old('phone') }}"
    type="text"
    name="phone"
    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
    placeholder="Enter phone number">

    @error('phone')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    </div>

    <!-- Email -->
    <div>
    <label class="block text-sm font-semibold mb-2">
    Email
    </label>

    <input
    value="{{ old('email') }}"
    type="email"
    name="email"
    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
    placeholder="Enter email (optional)">

    @error('email')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    </div>

    <!-- City -->
    <div>
    <label class="block text-sm font-semibold mb-2">
    City
    </label>

    <input
    value="{{ old('city') }}"
    type="text"
    name="city"
    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
    placeholder="Enter city">

    @error('city')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    </div>

    <!-- Discount -->
    <div>
    <label class="block text-sm font-semibold mb-2">
    Discount (%)
    </label>

    <input
    value="{{ old('discount',0) }}"
    type="number"
    step="0.01"
    name="discount"
    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
    placeholder="Enter discount">

    @error('discount')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    </div>

    </div>

    <!-- Address -->
    <div class="mt-6">
    <label class="block text-sm font-semibold mb-2">
    Address
    </label>

    <textarea
    name="address"
    rows="3"
    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
    placeholder="Enter address">{{ old('address') }}</textarea>

    @error('address')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    </div>

    <!-- Buttons -->
    <div class="mt-8 flex justify-end gap-3">

    <a href="{{ route('customers.index') }}"
    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg shadow">
    Cancel
    </a>

    <button
    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg shadow font-medium">
    Create Customer
    </button>

    </div>

    </form>

    </div>
    </div>

    </div>
    </div>

</x-app-layout>