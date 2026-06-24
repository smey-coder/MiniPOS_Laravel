<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Employee Details
            </h2>

            <a href="{{ route('employees.index') }}"
               class="bg-gray-600 hover:bg-gray-500 text-white text-sm px-4 py-2 rounded-md">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center space-x-6 mb-6">
                    @if($employee->image)
                        <img src="{{ asset('storage/employees/'.$employee->image) }}"
                             class="w-24 h-24 rounded-full object-cover border">
                    @else
                        <div class="w-24 h-24 flex items-center justify-center bg-gray-200 rounded-full">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">
                            {{ $employee->name }}
                        </h3>
                        <p class="text-gray-500">{{ $employee->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="border p-4 rounded-md">
                        <p class="text-gray-500 text-sm">Job Title</p>
                        <p class="font-semibold">
                            {{ $employee->job?->title ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="border p-4 rounded-md">
                        <p class="text-gray-500 text-sm">Assigned By (User)</p>
                        <p class="font-semibold">
                            {{ $employee->user?->name ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="border p-4 rounded-md">
                        <p class="text-gray-500 text-sm">Salary</p>
                        <p class="font-semibold">
                            {{ $employee->salary ? '$'.$employee->salary : '-' }}
                        </p>
                    </div>

                    <div class="border p-4 rounded-md">
                        <p class="text-gray-500 text-sm">Employee ID</p>
                        <p class="font-semibold">
                            #{{ $employee->id }}
                        </p>
                    </div>

                    <div class="border p-4 rounded-md col-span-2">
                        <p class="text-gray-500 text-sm">Created At</p>
                        <p class="font-semibold">
                            {{ $employee->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>