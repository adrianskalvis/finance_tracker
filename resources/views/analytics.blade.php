@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-6">
        
        <!-- Header -->
        <div>
            <h2 class="text-4xl font-bold text-white mb-2">Analytics</h2>
            <p class="text-slate-400">Year {{ $year }} financial overview</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-12">
            
            <!-- Left Panel: Expense Breakdown -->
            <div class="lg:col-span-1">
                <div class="glass-dark rounded-2xl p-6 h-full border-purple-500/30">
                    <h3 class="text-lg font-bold text-purple-400 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Expenses Breakdown
                    </h3>
                    <div class="flex justify-center mb-8" style="height: 250px;">
                        <canvas id="expenseChart"></canvas>
                    </div>
                    
                    <div class="space-y-3 border-t border-slate-700/50 pt-6">
                        @foreach ($expenseByTag as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $item['color'] }}"></div>
                                    <span class="text-sm font-medium text-slate-300">{{ $item['tag'] }}</span>
                                </div>
                                <span class="text-sm font-bold text-slate-200">${{ number_format($item['amount'], 0) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Panel: Monthly Grid -->
            <div class="lg:col-span-3">
                <h3 class="text-lg font-bold text-emerald-400 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Total Savings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($monthsData as $month)
                        <div class="glass-dark rounded-2xl p-5 border-l-4 {{ $month['isCurrentMonth'] ? 'border-l-emerald-500 ring-2 ring-emerald-500/50' : 'border-l-slate-600' }} transition hover:shadow-lg hover:shadow-emerald-500/20">
                            <h4 class="font-bold text-white text-sm mb-4 flex items-center gap-2">
                                <span class="text-emerald-400">•</span> {{ $month['name'] }}
                            </h4>
                            
                            <div class="space-y-3">
                                <div class="bg-slate-900/50 rounded-lg p-3">
                                    <p class="text-xs text-slate-400 mb-1">Income</p>
                                    <p class="font-bold text-emerald-400 text-lg">${{ number_format($month['income'], 0) }}</p>
                                </div>
                                <div class="bg-slate-900/50 rounded-lg p-3">
                                    <p class="text-xs text-slate-400 mb-1">Expenses</p>
                                    <p class="font-bold text-red-400 text-lg">${{ number_format($month['expense'], 0) }}</p>
                                </div>
                                <div class="bg-gradient-to-r from-emerald-600/20 to-teal-600/20 rounded-lg p-3 border border-emerald-500/30">
                                    <p class="text-xs text-slate-400 mb-1">Net Savings</p>
                                    <p class="font-bold text-emerald-300 text-xl">${{ number_format($month['net'], 0) }}</p>
                                </div>
                            </div>
                            
                            @if ($month['isCurrentMonth'])
                                <div class="mt-4 px-3 py-2 bg-emerald-500/20 text-emerald-300 rounded-lg text-xs font-bold text-center border border-emerald-500/50">
                                    Current Month
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart.js - Expense Doughnut Chart
    const ctx = document.getElementById('expenseChart').getContext('2d');
    const labels = {!! $chartLabels !!};
    const data = {!! $chartData !!};
    const colors = {!! $chartColors !!};
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderColor: '#1e293b',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endsection
