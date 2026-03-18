<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                Technology Shop Dashboard
            </h2>

            <!-- Dark Mode Toggle -->
            {{-- <button onclick="toggleDarkMode()" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">
                🌙 Toggle Dark Mode
            </button> --}}
        </div>
    </x-slot>

    <div class="py-10">

        <!-- Success Message -->
        @if(session('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Time Card -->
            @can('view some dashboards')
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
            @endcan

            <!-- Statistics -->
            @can('view dashboards')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="p-6 rounded-2xl backdrop-blur-xl bg-white/40 dark:bg-gray-800/40 shadow-lg hover:scale-105 hover:shadow-2xl transition">
                    <p class="text-gray-500">Revenue Today</p>
                    <h3 class="text-3xl font-bold text-green-600">
                        ${{ \DB::table('invoice_details')->join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')->whereDate('invoices.created_at', today())->sum('invoice_details.total') }}
                    </h3>
                </div>

                <div class="p-6 rounded-2xl backdrop-blur-xl bg-white/40 dark:bg-gray-800/40 shadow-lg hover:scale-105 hover:shadow-2xl transition">
                    <p class="text-gray-500">Orders Today</p>
                    <h3 class="text-3xl font-bold text-indigo-600">
                        {{ \App\Models\Invoices::whereDate('created_at', today())->count() }}
                    </h3>
                </div>

                <div class="p-6 rounded-2xl backdrop-blur-xl bg-white/40 dark:bg-gray-800/40 shadow-lg hover:scale-105 hover:shadow-2xl transition">
                    <p class="text-gray-500">Products</p>
                    <h3 class="text-3xl font-bold text-blue-600">
                        {{ \App\Models\Products::count() }}
                    </h3>
                </div>

                <div class="p-6 rounded-2xl backdrop-blur-xl bg-white/40 dark:bg-gray-800/40 shadow-lg hover:scale-105 hover:shadow-2xl transition">
                    <p class="text-gray-500">Total Stock</p>
                    <h3 class="text-3xl font-bold text-red-500">
                        {{ \App\Models\Products::sum('quantity') }}
                    </h3>
                </div>
            </div>
            @endcan
            <!-- Sales Chart -->
            @can('view dashboards')
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">
                    Weekly Sales
                </h3>
                <canvas id="salesChart"></canvas>
            </div>
            @endcan

            <!-- Inventory Alerts -->
            @can('view dashboards')
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">
                    Inventory Alerts
                </h3>
                <table class="w-full text-sm">
                    <thead class="border-b text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="text-left py-2">Product</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Products::where('quantity','<=',10)->get() as $product)
                        <tr class="border-b text-gray-700 dark:text-gray-200">
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                @if($product->quantity == 0)
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded">Out of Stock</span>
                                @else
                                <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded">Low Stock</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endcan

            <!-- Recent Orders -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
    
    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">
        All Orders
    </h3>
    <form method="GET" class="mb-4 flex flex-wrap gap-3 items-center">

    <!-- Search by customer -->
    <input type="text" name="search" placeholder="🔍 Search customer..."
        value="{{ request('search') }}"
        class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-400">

    <!-- Filter by date -->
    <input type="date" name="date"
        value="{{ request('date') }}"
        class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-400">

    <!-- Button -->
    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        Filter
    </button>

</form>

<!-- Scroll Container -->
<div class="max-h-96 overflow-y-auto">
    <table class="w-full text-sm">
        <thead class="border-b text-gray-700 dark:text-gray-200 sticky top-0 bg-white dark:bg-gray-800">
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>

            @php
            $orders = \App\Models\Invoices::with(['customer','details'])

                // 🔍 Search by customer name
                ->when(request('search'), function($q){
                    $q->whereHas('customer', function($c){
                        $c->where('name', 'like', '%' . request('search') . '%');
                    });
                })

                // 📅 Filter by date
                ->when(request('date'), function($q){
                    $q->whereDate('created_at', request('date'));
                })

                ->latest()
                ->get();
            @endphp

            @foreach($orders as $order)
            <tr class="border-b text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <td>#{{ $order->id }}</td>
                <td>{{ $order->customer->name }}</td>
                <td class="text-green-600">${{ $order->details->sum('total') }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>

    <x-slot name="script">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Clock
            function updateClock() {
                const now = new Date();
                document.getElementById('clock').innerHTML = now.toLocaleTimeString();
                document.getElementById('date').innerHTML = now.toDateString();
            }
            setInterval(updateClock, 1000);
            updateClock();

            // Dark Mode
            function toggleDarkMode() {
                const html = document.documentElement;
                html.classList.toggle('dark');
                localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
                updateChartTheme();
            }

            // Load saved theme
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }

            // Weekly Sales Data
            @php
            $salesData = \App\Models\Invoice_Details::join('invoices', 'invoice_details.invoice_id', '=', 'invoices.id')
                ->selectRaw('DAYNAME(invoices.created_at) as day, SUM(invoice_details.total) as total')
                ->whereBetween('invoices.created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->groupBy('day')
                ->pluck('total', 'day')
                ->toArray();

            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            $chartData = [];
            foreach($days as $day){
                $chartData[] = $salesData[$day] ?? 0;
            }
            @endphp

            const days = @json($days);
            const chartData = @json($chartData);
            const ctx = document.getElementById('salesChart');

            function getChartColors() {
                const isDark = document.documentElement.classList.contains('dark');
                return {
                    textColor: isDark ? '#e5e7eb' : '#374151',
                    gridColor: isDark ? '#374151' : '#e5e7eb',
                    lineColor: isDark ? '#818cf8' : '#4f46e5'
                };
            }

            function renderChart() {
                const colors = getChartColors();
                return new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: days,
                        datasets: [{
                            label: 'Sales ($)',
                            data: chartData,
                            borderColor: colors.lineColor,
                            backgroundColor: colors.lineColor,
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { labels: { color: colors.textColor } } },
                        scales: {
                            x: { ticks: { color: colors.textColor }, grid: { color: colors.gridColor } },
                            y: { ticks: { color: colors.textColor }, grid: { color: colors.gridColor } }
                        }
                    }
                });
            }

            let chart = renderChart();
            function updateChartTheme() {
                chart.destroy();
                chart = renderChart();
            }
        </script>
    </x-slot>

</x-app-layout>