<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-5">
            {{-- Dashboard Header --}}
            <div class="mb-8">
                <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-3xl p-8 text-white shadow-xl">

                    <div class="flex flex-col lg:flex-row justify-between items-center">

                        <div>
                            <h1 class="text-4xl font-bold">
                                Sales Dashboard
                            </h1>

                            <p class="mt-2 opacity-90">
                                Monitor revenue, employee performance and sales activity.
                            </p>
                        </div>

                        <div class="mt-4 lg:mt-0 text-right">
                            <div class="text-sm opacity-80">
                                Report Date
                            </div>

                            <div class="text-xl font-semibold">
                                {{ now()->format('d M Y') }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Top Employee --}}
            @if($topEmployee)
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 text-white shadow-lg">

                    <div class="flex justify-between items-center">

                        <div>
                            <p class="uppercase tracking-wider text-blue-100">
                                Top Sales Employee
                            </p>

                            <h2 class="text-3xl font-bold mt-2">
                                🏆 {{ $topEmployee->employee_name }}
                            </h2>

                            <p class="mt-2 text-blue-100">
                                Generated Revenue:
                                ${{ number_format($topEmployee->total_sales,2) }}
                            </p>
                        </div>

                        <div class="text-7xl opacity-20">
                            🏅
                        </div>

                    </div>

                </div>
            </div>
            @endif

            {{-- Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-8">

                {{-- Revenue --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Revenue</p>

                            <h2 class="text-3xl font-bold text-green-600 mt-2">
                                ${{ number_format($totalSales,2) }}
                            </h2>
                        </div>

                        <div class="text-4xl">
                            💰
                        </div>
                    </div>
                </div>

                {{-- Orders --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Orders</p>

                            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                                {{ number_format($totalOrders) }}
                            </h2>
                        </div>

                        <div class="text-4xl">
                            🛒
                        </div>
                    </div>
                </div>

                {{-- Today --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Today's Sales</p>

                            <h2 class="text-3xl font-bold text-orange-600 mt-2">
                                ${{ number_format($todaySales,2) }}
                            </h2>

                            <p class="text-xs text-gray-500 mt-2">
                                {{ $todayOrders }} Orders
                            </p>
                        </div>

                        <div class="text-4xl">
                            🔥
                        </div>
                    </div>
                </div>

                {{-- Monthly --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Monthly Sales</p>

                            <h2 class="text-3xl font-bold text-purple-600 mt-2">
                                ${{ number_format($thisMonthSales,2) }}
                            </h2>

                            <p class="text-xs text-gray-500 mt-2">
                                {{ $thisMonthOrders }} Orders
                            </p>
                        </div>

                        <div class="text-4xl">
                            📈
                        </div>
                    </div>
                </div>

                {{-- Average --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Average Order</p>

                            <h2 class="text-3xl font-bold text-pink-600 mt-2">
                                ${{ number_format($averageOrder,2) }}
                            </h2>
                        </div>

                        <div class="text-4xl">
                            📊
                        </div>
                    </div>
                </div>

            </div>

            {{-- Search Toolbar --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-5 mb-8">

                <form method="GET">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search customer or employee..."
                            class="rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                        <input
                            type="date"
                            name="date"
                            value="{{ request('date') }}"
                            class="rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                        <button
                            type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl px-5 py-2 font-medium">
                            🔍 Search
                        </button>

                        <a
                            href="{{ route('reports.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white rounded-xl px-5 py-2 text-center font-medium">
                            Reset
                        </a>

                    </div>

                </form>

            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden">

                <div class="p-5 border-b dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Recent Transactions
                    </h2>
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="sticky top-0 bg-gray-100 dark:bg-gray-700 z-10">

                            <tr class="text-gray-700 dark:text-gray-200">
                                <th class="px-4 py-4 text-left">Invoice</th>
                                <th class="px-4 py-4 text-left">Customer</th>
                                <th class="px-4 py-4 text-left">Employee</th>
                                <th class="px-4 py-4 text-center">Items</th>
                                <th class="px-4 py-4 text-right">Amount</th>
                                <th class="px-4 py-4 text-center">Date</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($reports as $report)

                            <tr class="border-b dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-all duration-200">

                                <td class="px-4 py-4 font-medium">
                                    #{{ $report->id }}
                                </td>

                                <td class="px-4 py-4">
                                    {{ $report->customer_name }}
                                </td>

                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-medium">
                                        {{ $report->employee_name }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    {{ $report->total_items }}
                                </td>

                                <td class="px-4 py-4 text-right">
                                    <span class="font-bold text-green-600 text-base">
                                        ${{ number_format($report->grand_total,2) }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center">
                                    {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}
                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="6">

                                    <div class="py-16 text-center">

                                        <div class="text-6xl mb-4">
                                            📊
                                        </div>

                                        <h3 class="text-xl font-semibold text-gray-700 dark:text-white">
                                            No Sales Data Found
                                        </h3>

                                        <p class="text-gray-500 mt-2">
                                            No transactions match your filters.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="p-5 border-t dark:border-gray-700">
                    {{ $reports->links() }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
