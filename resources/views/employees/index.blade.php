
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                    Employee Management
                </h2>
                <p class="text-sm text-gray-500">
                    Manage employee information, accounts and payroll overview
                </p>
            </div>

            @can('create employee')
            <a href="{{ route('employees.create') }}"
               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-sm">
                + Add Employee
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-gray-500 text-sm">Total Employees</p>
                    <h3 class="text-3xl font-bold text-indigo-600 mt-2">
                        {{ $employees->total() }}
                    </h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-gray-500 text-sm">Active Employees</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">
                        {{ \App\Models\Employees::where('status','active')->count() }}
                    </h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-gray-500 text-sm">Linked Accounts</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-2">
                        {{ \App\Models\Employees::whereNotNull('user_id')->count() }}
                    </h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-gray-500 text-sm">Monthly Payroll</p>
                    <h3 class="text-3xl font-bold text-orange-600 mt-2">
                        ${{ number_format(\App\Models\Employees::sum('salary'),2) }}
                    </h3>
                </div>

            </div>

            {{-- Search --}}
            <div class="bg-white rounded-2xl shadow-sm border p-4 mb-6">
                <form method="GET">
                    <div class="flex flex-col md:flex-row gap-4">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search employee..."
                            class="flex-1 rounded-xl border-gray-300">

                        <button
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl">
                            Search
                        </button>

                    </div>
                </form>
            </div>

            {{-- Employee Table --}}
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left">Employee</th>
                                <th class="px-6 py-4 text-left">Email</th>
                                <th class="px-6 py-4 text-left">Job</th>
                                <th class="px-6 py-4 text-left">User Account</th>
                                <th class="px-6 py-4 text-left">Salary</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($employees as $employee)

                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        @if($employee->image)
                                        <img
                                            src="{{ asset('storage/employees/'.$employee->image) }}"
                                            class="w-12 h-12 rounded-full object-cover">
                                        @else
                                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            👤
                                        </div>
                                        @endif

                                        <div>
                                            <div class="font-semibold">
                                                {{ $employee->name }}
                                            </div>

                                            <div class="text-xs text-gray-500">
                                                Employee ID #{{ $employee->id }}
                                            </div>
                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-4">
                                    {{ $employee->email }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $employee->job?->title ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $employee->user?->name ?? 'Not Linked' }}
                                </td>

                                <td class="px-6 py-4 font-medium">
                                    ${{ number_format($employee->salary,2) }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($employee->status == 'active')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('employees.show',$employee->id) }}"
                                           class="px-3 py-2 bg-slate-500 text-white rounded-lg">
                                            View
                                        </a>

                                        <a href="{{ route('employees.edit',$employee->id) }}"
                                           class="px-3 py-2 bg-blue-600 text-white rounded-lg">
                                            Edit
                                        </a>

                                        <button
                                            onclick="deleteEmployee({{ $employee->id }})"
                                            class="px-3 py-2 bg-red-600 text-white rounded-lg">
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500">
                                    No employees found
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="p-4 border-t">
                    {{ $employees->links() }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
