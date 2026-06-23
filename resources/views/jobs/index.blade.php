<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Jobs Management
            </h2>

            <a href="{{ route('jobs.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow">
                + Create Job
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-message />

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">

                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">#</th>
                                <th class="px-6 py-4">Image</th>
                                <th class="px-6 py-4">Title</th>
                                <th class="px-6 py-4">Salary</th>
                                <th class="px-6 py-4">Created</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                            @forelse ($jobs as $j)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $j->id }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($j->image)
                                            <img src="{{ asset('storage/jobs/'.$j->image) }}"
                                                 class="w-12 h-12 rounded-lg object-cover border"
                                                 alt="Job Image">
                                        @else
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-100">
                                        {{ $j->title }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                            ${{ $j->min_salary }} - ${{ $j->max_salary }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($j->created_at)->format('d M Y') }}
                                    </td>

                                    <td class="px-6 py-4 text-center space-x-2">

                                        <a href="{{ route('jobs.edit', $j->id) }}"
                                           class="inline-block px-3 py-1 text-xs bg-yellow-500 hover:bg-yellow-600 text-white rounded-md">
                                            Edit
                                        </a>

                                        <button onclick="deleteJob({{ $j->id }})"
                                                class="inline-block px-3 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded-md">
                                            Delete
                                        </button>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 text-gray-500">
                                        No jobs found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $jobs->links() }}
            </div>

        </div>
    </div>

    <x-slot name="script">
        <script>
            function deleteJob(id) {
                if (!confirm("Are you sure you want to delete this job?")) return;

                $.ajax({
                    url: '{{ route("jobs.destroy", ":id") }}'.replace(':id', id),
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function () {
                        location.reload();
                    },
                    error: function () {
                        alert("Something went wrong!");
                    }
                });
            }
        </script>
    </x-slot>

</x-app-layout>