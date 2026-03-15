<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Employee') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="w-full px-4 py-2 border rounded-md @error('name') border-red-500 @enderror">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="w-full px-4 py-2 border rounded-md @error('email') border-red-500 @enderror">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Position</label>
                        <input type="text" name="position" value="{{ old('position', $employee->position) }}" class="w-full px-4 py-2 border rounded-md">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Job</label>
                        <select name="job_id" class="w-full px-4 py-2 border rounded-md">
                            <option value="">Select Job</option>
                            @foreach($jobs as $id => $title)
                                <option value="{{ $id }}" {{ old('job_id', $employee->job_id)==$id ? 'selected' : '' }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">User</label>
                        <select name="user_id" class="w-full px-4 py-2 border rounded-md">
                            <option value="">Select User</option>
                            @foreach($users as $id => $name)
                                <option value="{{ $id }}" {{ old('user_id', $employee->user_id)==$id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Salary</label>
                        <input type="number" name="salary" value="{{ old('salary', $employee->salary) }}" class="w-full px-4 py-2 border rounded-md @error('salary') border-red-500 @enderror">
                        @error('salary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Hire Date</label>
                        <input type="date" name="hire_date" value="{{ old('hire_date', $employee->hire_date) }}" class="w-full px-4 py-2 border rounded-md">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status" class="w-full px-4 py-2 border rounded-md">
                            <option value="active" {{ old('status', $employee->status)=='active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $employee->status)=='inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Current Image</label>
                        @if($employee->image)
                            <img src="{{ asset('storage/employees/'.$employee->image) }}" class="w-20 h-20 rounded-full object-cover mb-2">
                        @endif
                        <input type="file" name="image" class="w-full">
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md">Update Employee</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>