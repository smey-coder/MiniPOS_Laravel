<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Jobs') }}
            </h2>

            <a href="{{ route('jobs.create')}}" 
               class="bg-slate-700 text-sm rounded-md text-white px-3 py-3 hover:bg-slate-600">
                Create
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-message></x-message>

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr class="border-b">

                        <th class="px-6 py-3 text-left" width="60">#</th>

                        <th class="px-6 py-3 text-left">Image</th>

                        <th class="px-6 py-3 text-left">Title</th>

                        <th class="px-6 py-3 text-left">Company</th>

                        <th class="px-6 py-3 text-left">Salary</th>

                        <th class="px-6 py-3 text-left" width="180">Created</th>

                        <th class="px-6 py-3 text-center" width="180">Action</th>

                    </tr>

                </thead>


                <tbody class="bg-white">

                    @if($jobs->isNotEmpty())

                    @foreach ($jobs as $j)

                    <tr class="border-b">

                        <td class="px-6 py-3 text-left">
                            {{ $j->id }}
                        </td>

                        <td class="px-6 py-4">
                            @if($j->image)
                                    <img src="{{ asset('storage/jobs/'.$j->image) }}" 
                                        alt="Job Image" 
                                        class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <span class="text-gray-400 text-xs">No Image</span>
                            @endif
                        </td>

                        <td class="px-6 py-3 text-left">
                            {{ $j->title }}
                        </td>

                        <td class="px-6 py-3 text-left">
                            {{ $j->company }}
                        </td>


                        <td class="px-6 py-3 text-left text-green-600 font-semibold">

                            ${{ $j->min_salary }} - ${{ $j->max_salary }}

                        </td>


                        <td class="px-6 py-3 text-left">

                            {{ \Carbon\Carbon::parse($j->created_at)->format('d M, Y') }}

                        </td>


                        <td class="px-6 py-3 text-center">

                            <a href="{{ route('jobs.edit', $j->id)}}"
                               class="bg-slate-700 text-sm rounded-md text-white px-3 py-2 hover:bg-slate-600">

                                Edit

                            </a>


                            <a href="javascript:void(0)"
                               onclick="deleteJob({{ $j->id }})"
                               class="bg-red-700 text-sm rounded-md text-white px-3 py-2 hover:bg-red-600">

                                Delete

                            </a>

                        </td>

                    </tr>

                    @endforeach

                    @endif

                </tbody>

            </table>


            <div class="my-3">

                {{ $jobs->links() }}

            </div>

        </div>
    </div>



<x-slot name="script">

<script type="text/javascript">

function deleteJob(id){

if(confirm("Are you sure want to delete?")){

$.ajax({

url : '{{ route("jobs.destroy", ":id") }}'.replace(':id', id),

type: 'delete',

dataType: 'json',

headers: {
'x-csrf-token' : '{{ csrf_token() }}'
},

success: function(response){

window.location.href = '{{ route("jobs.index") }}';

}

});

}

}

</script>

</x-slot>

</x-app-layout>