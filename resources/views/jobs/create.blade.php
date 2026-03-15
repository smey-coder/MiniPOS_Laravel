<x-app-layout>

<x-slot name="header">
<div class="flex justify-between">

<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
{{ __('Create Job') }}
</h2>

<a href="{{ route('jobs.index') }}"
class="bg-slate-700 text-sm rounded-md text-white px-3 py-3">
Back
</a>

</div>
</x-slot>


<div class="py-12">

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<x-message></x-message>

@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
<strong>Whoops!</strong> Please fix the following errors:
<ul class="mt-2 list-disc list-inside">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif


<div class="bg-white shadow rounded-lg p-6">

<form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data">

@csrf


<!-- Title -->
<div class="mb-4">
<label class="block text-gray-700 font-medium mb-2">Title</label>
<input type="text" name="title"
class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
</div>


<!-- Company -->
<div class="mb-4">
<label class="block text-gray-700 font-medium mb-2">Company</label>
<input type="text" name="company"
class="w-full border-gray-300 rounded-md shadow-sm">
</div>


<!-- Location -->
<div class="mb-4">
<label class="block text-gray-700 font-medium mb-2">Location</label>
<input type="text" name="location"
class="w-full border-gray-300 rounded-md shadow-sm">
</div>


<!-- Salary -->
<div class="grid grid-cols-2 gap-4">

<div>
<label class="block text-gray-700 font-medium mb-2">Min Salary</label>
<input type="number" name="min_salary"
class="w-full border-gray-300 rounded-md shadow-sm">
</div>

<div>
<label class="block text-gray-700 font-medium mb-2">Max Salary</label>
<input type="number" name="max_salary"
class="w-full border-gray-300 rounded-md shadow-sm">
</div>

</div>


<!-- Image -->
<div class="mb-4 mt-4">
<label class="block text-gray-700 font-medium mb-2">Image</label>
<input type="file" name="image"
class="w-full border-gray-300 rounded-md shadow-sm">
</div>


<!-- Description -->
<div class="mb-4">
<label class="block text-gray-700 font-medium mb-2">Description</label>
<textarea name="description"
class="w-full border-gray-300 rounded-md shadow-sm"
rows="4"></textarea>
</div>


<!-- Deadline -->
<div class="mb-4">
<label class="block text-gray-700 font-medium mb-2">Deadline</label>
<input type="date" name="deadline"
class="w-full border-gray-300 rounded-md shadow-sm">
</div>


<!-- Status -->
<div class="mb-4">
<label class="block text-gray-700 font-medium mb-2">Status</label>
<select name="status"
class="w-full border-gray-300 rounded-md shadow-sm">

<option value="open">Open</option>
<option value="closed">Closed</option>

</select>
</div>


<!-- Button -->
<div class="mt-6">

<button type="submit"
class="bg-slate-700 hover:bg-slate-600 text-white px-6 py-2 rounded-md">

Save Job

</button>

</div>


</form>

</div>

</div>

</div>

</x-app-layout>