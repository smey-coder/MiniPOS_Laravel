<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Employee') }}
            </h2>
            <a href="{{ route('employees.index')}}" class="bg-slate-700 text-sm rounded-md text-white px-3 py-3">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-md @error('name') border-red-500 @enderror">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-md @error('email') border-red-500 @enderror">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Position</label>
                        <input type="text" name="position" value="{{ old('position') }}" class="w-full px-4 py-2 border rounded-md @error('position') border-red-500 @enderror">
                        @error('position') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    {{-- Select Job --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Jobs
                        </label>
                            <select name="job_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 @error('job_id') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                @foreach ($jobs as $j)
                                    <option value="{{ $j->id }}">
                                            {{ $j->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('job_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                    </div>

                    {{-- Select Users --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            User
                        </label>
                            <select name="user_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 @error('user_id') border-red-500 @enderror">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                            {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Salary</label>
                        <input type="number" name="salary" value="{{ old('salary') }}" class="w-full px-4 py-2 border rounded-md @error('salary') border-red-500 @enderror">
                        @error('salary') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Hire Date</label>
                        <input type="date" name="hire_date" value="{{ old('hire_date') }}" class="w-full px-4 py-2 border rounded-md @error('hire_date') border-red-500 @enderror">
                        @error('hire_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status" class="w-full px-4 py-2 border rounded-md @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status')=='active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Image</label>
                        <input type="file" name="image" class="w-full @error('image') border-red-500 @enderror">
                        @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md">Save Employee</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>