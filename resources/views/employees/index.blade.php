<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Employees') }}
            </h2>
            <a href="{{ route('employees.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white text-sm px-4 py-2 rounded-md">
                Create Employee
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message> {{-- Alert component --}}

            <div class="overflow-x-auto bg-white shadow-sm sm:rounded-lg">
                <table class="w-full table-auto border-collapse border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 border-b">#</th>
                            <th class="px-6 py-3 border-b">Image</th>
                            <th class="px-6 py-3 border-b">Name</th>
                            <th class="px-6 py-3 border-b">Email</th>
                            <th class="px-6 py-3 border-b">Job</th>
                            <th class="px-6 py-3 border-b">User</th>
                            <th class="px-6 py-3 border-b">Salary</th>
                            <th class="px-6 py-3 border-b text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($employees as $employee)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $employee->id }}</td>
                            <td class="px-6 py-3">
                                @if($employee->image)
                                    <img src="{{ asset('storage/employees/'.$employee->image) }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">{{ $employee->name }}</td>
                            <td class="px-6 py-3">{{ $employee->email }}</td>
                            <td class="px-6 py-3">{{ $employee->job?->title ?? 'N/A' }}</td>
                            <td class="px-6 py-3">{{ $employee->user?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3">
                                {{ $employee->salary ? '$'.$employee->salary : '-' }}
                            </td>
                            <td class="px-6 py-3 text-center space-x-2">
                                <a href="{{ route('employees.edit', $employee->id) }}" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded-md text-sm">Edit</a>
                                <button onclick="deleteEmployee({{ $employee->id }})" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded-md text-sm">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">No employees found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4 px-6">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            function deleteEmployee(id){
                if(confirm("Are you sure you want to delete this employee?")){
                    $.ajax({
                        url: '{{ route("employees.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        success: function(response){
                            if(response.status){
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>