<x-app-layout>

<x-slot name="header">
<div class="flex justify-between items-center">

<h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
MiniPOS Dashboard
</h2>

<button onclick="toggleDark()"
class="bg-gray-800 text-white px-4 py-2 rounded-lg">
Dark Mode
</button>

</div>
</x-slot>

<div class="py-10">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

<!-- Time Card -->
<div class="bg-indigo-600 text-white p-6 rounded-xl flex justify-between">

<div>
<p class="text-lg">Today</p>
<p id="date" class="text-xl font-semibold"></p>
</div>

<div>
<p class="text-lg">Current Time</p>
<p id="clock" class="text-2xl font-bold"></p>
</div>

</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<p class="text-gray-500">Revenue Today</p>
<h3 class="text-3xl font-bold text-green-600">
{{-- ${{ \App\Models\Order::whereDate('created_at',today())->sum('total') ?? 0 }} --}}
</h3>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<p class="text-gray-500">Orders Today</p>
<h3 class="text-3xl font-bold text-indigo-600">
{{-- {{ \App\Models\Order::whereDate('created_at',today())->count() ?? 0 }} --}}
</h3>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<p class="text-gray-500">Products</p>
<h3 class="text-3xl font-bold text-blue-600">
{{ \App\Models\Products::count() }}
</h3>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<p class="text-gray-500">Total Stock</p>
<h3 class="text-3xl font-bold text-red-500">
{{ \App\Models\Products::sum('quantity') }}
</h3>
</div>

</div>

<!-- Sales Chart -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

<h3 class="text-lg font-semibold mb-4">
Weekly Sales
</h3>

<canvas id="salesChart"></canvas>

</div>

<!-- Inventory Alerts -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

<h3 class="text-lg font-semibold mb-4">
Inventory Alerts
</h3>

<table class="w-full text-sm">

<thead class="border-b">
<tr>
<th class="text-left py-2">Product</th>
<th>Stock</th>
<th>Status</th>
</tr>
</thead>

<tbody>

@foreach(\App\Models\Products::where('quantity','<=',10)->get() as $product)

<tr class="border-b">

<td>{{ $product->name }}</td>

<td>{{ $product->quantity }}</td>

<td>

@if($product->quantity == 0)

<span class="bg-red-100 text-red-600 px-2 py-1 rounded">
Out of Stock
</span>

@else

<span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded">
Low Stock
</span>

@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

<!-- Recent Orders -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

<h3 class="text-lg font-semibold mb-4">
Recent Orders
</h3>

<table class="w-full text-sm">

<thead class="border-b">
<tr>
<th>ID</th>
<th>Customer</th>
<th>Total</th>
<th>Date</th>
</tr>
</thead>

<tbody>

{{-- @foreach(\App\Models\Order::latest()->take(5)->get() as $order) --}}

<tr class="border-b">

{{-- <td>#{{ $order->id }}</td>

<td>{{ $order->customer_name }}</td>

<td class="text-green-600">
${{ $order->total }}
</td>

<td>
{{ $order->created_at->format('d M Y') }}
</td> --}}

</tr>

{{-- @endforeach --}}

</tbody>

</table>

</div>

</div>
</div>

<!-- Scripts -->
<x-slot name="script">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// Clock
function updateClock(){

const now = new Date();

document.getElementById('clock').innerHTML =
now.toLocaleTimeString();

document.getElementById('date').innerHTML =
now.toDateString();

}

setInterval(updateClock,1000);
updateClock();

// Dark Mode
function toggleDark(){
document.documentElement.classList.toggle('dark');
}

// Chart

const ctx = document.getElementById('salesChart');

new Chart(ctx, {

type:'line',

data:{
labels:['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],

datasets:[{

label:'Sales',

data:[120,200,150,300,250,400,350],

borderWidth:3,

tension:0.4

}]
},

options:{
responsive:true
}

});

</script>

</x-slot>

</x-app-layout>
