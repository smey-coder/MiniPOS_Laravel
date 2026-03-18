<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                User / Create
            </h2>

            <a href="{{ route('users.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg shadow">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border">

                <div class="p-8 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">
                                    Name
                                </label>

                                <input
                                    value="{{ old('name') }}"
                                    type="text"
                                    name="name"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter Name">

                                @error('name')
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
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter Email">

                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter Password">

                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-semibold mb-2">
                                    Confirm Password
                                </label>

                                <input
                                    type="password"
                                    name="confirm_password"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Confirm Your Password">

                                @error('confirm_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- Role Section -->
                        <div class="mt-8">
                            <label class="block text-sm font-semibold mb-3">
                                Assign Role
                            </label>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

                                @if ($roles->isNotEmpty())
                                    @foreach ($roles as $role)

                                    <label
                                        class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">

                                        <input
                                            type="checkbox"
                                            id="role-{{ $role->id }}"
                                            name="role[]"
                                            value="{{ $role->name }}"
                                            class="rounded text-indigo-600 focus:ring-indigo-500">

                                        <span class="text-sm">
                                            {{ $role->name }}
                                        </span>

                                    </label>

                                    @endforeach
                                @endif

                            </div>
                        </div>

                        <!-- Button -->
                        <div class="mt-8 flex justify-end">
                            <button
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg shadow font-medium">
                                Create User
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>
```
