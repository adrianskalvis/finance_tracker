@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-6">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-4xl font-bold text-white mb-2">Dashboard</h2>
                <p class="text-slate-400">Track your monthly income and expenses</p>
            </div>
            
            <!-- Month/Year Selector -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex gap-3">
                <select name="month" class="glass px-4 py-2 rounded-lg text-slate-900 font-semibold">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(2000, $m, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
                
                <select name="year" class="glass px-4 py-2 rounded-lg text-slate-900 font-semibold">
                    @for ($y = 2020; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                
                <button type="submit" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                    Load
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Left Panel: Chart -->
            <div class="lg:col-span-1">
                <div class="glass-dark rounded-2xl p-6 h-full min-h-96 flex flex-col border-emerald-500/30">
                    <h3 class="text-lg font-bold text-emerald-400 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7H5v12h8V7z"></path>
                        </svg>
                        Savings Trend
                    </h3>
                    <div class="flex-1">
                        <canvas id="savingsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Tables -->
            <div class="lg:col-span-3 space-y-6">
                
                <!-- Income Table -->
                <div class="glass-dark rounded-2xl overflow-hidden border-emerald-500/30">
                    <div class="bg-gradient-to-r from-emerald-600/20 to-teal-600/20 border-b border-emerald-500/20 px-8 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Monthly Income
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-700/50">
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Source</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Amount</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Tag</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($incomeEntries as $entry)
                                    <tr class="border-b border-slate-700/30 hover:bg-emerald-900/20 transition">
                                        <td class="px-8 py-4 text-slate-300">{{ $entry->source }}</td>
                                        <td class="px-8 py-4 font-bold text-emerald-400">${{ number_format($entry->amount, 2) }}</td>
                                        <td class="px-8 py-4">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-600/30 text-emerald-200 border border-emerald-500/50">
                                                {{ $entry->tag }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-4 text-slate-400 text-sm">{{ $entry->date->format('M j, Y') }}</td>
                                        <td class="px-8 py-4 space-x-3">
                                            <a href="#" class="text-blue-400 hover:text-blue-300 text-sm font-semibold">Edit</a>
                                            <form method="POST" action="{{ route('income.destroy', $entry) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-semibold">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-12 text-center text-slate-500">No income entries</td>
                                    </tr>
                                @endforelse
                                
                                <!-- Total Row -->
                                <tr class="bg-gradient-to-r from-emerald-600/20 to-teal-600/20 border-t border-emerald-500/30 font-bold">
                                    <td colspan="1" class="px-8 py-4 text-emerald-400">Total</td>
                                    <td class="px-8 py-4 text-emerald-300 text-lg">${{ number_format($incomeTotal, 2) }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Add Income Button -->
                    <div class="border-t border-slate-700/50 p-6 bg-slate-900/50">
                        <button onclick="toggleIncomeForm()" class="bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Income
                        </button>
                        
                        <form id="incomeForm" method="POST" action="{{ route('income.store') }}" class="mt-4 hidden grid grid-cols-2 gap-4">
                            @csrf
                            <input type="hidden" name="month" value="{{ $currentMonth }}">
                            <input type="hidden" name="year" value="{{ $currentYear }}">
                            
                            <input type="text" name="source" placeholder="Source" required class="glass px-4 py-2 rounded-lg text-slate-900">
                            <input type="number" name="amount" placeholder="Amount" step="0.01" required class="glass px-4 py-2 rounded-lg text-slate-900">
                            <select name="tag" required class="glass px-4 py-2 rounded-lg text-slate-900">
                                <option value="">Select Tag</option>
                                <option value="Salary">Salary</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="date" name="date" required class="glass px-4 py-2 rounded-lg text-slate-900">
                            
                            <button type="submit" class="col-span-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:shadow-blue-500/50 transition">
                                Save Income
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Expense Table -->
                <div class="glass-dark rounded-2xl overflow-hidden border-red-500/30">
                    <div class="bg-gradient-to-r from-red-600/20 to-orange-600/20 border-b border-red-500/20 px-8 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Monthly Expenses
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-700/50">
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Source</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Amount</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Tag</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                                    <th class="px-8 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expenseEntries as $entry)
                                    <tr class="border-b border-slate-700/30 hover:bg-red-900/20 transition">
                                        <td class="px-8 py-4 text-slate-300">{{ $entry->source }}</td>
                                        <td class="px-8 py-4 font-bold text-red-400">${{ number_format($entry->amount, 2) }}</td>
                                        <td class="px-8 py-4">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                                {{ $entry->tag == 'Rent/Mortgage' ? 'bg-pink-600/30 text-pink-200 border border-pink-500/50' : ($entry->tag == 'Utilities' ? 'bg-yellow-600/30 text-yellow-200 border border-yellow-500/50' : ($entry->tag == 'Miscellaneous' ? 'bg-blue-600/30 text-blue-200 border border-blue-500/50' : ($entry->tag == 'Retail' ? 'bg-purple-600/30 text-purple-200 border border-purple-500/50' : 'bg-slate-600/30 text-slate-200 border border-slate-500/50'))) }}">
                                                {{ $entry->tag }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-4 text-slate-400 text-sm">{{ $entry->date->format('M j, Y') }}</td>
                                        <td class="px-8 py-4 space-x-3">
                                            <a href="#" class="text-blue-400 hover:text-blue-300 text-sm font-semibold">Edit</a>
                                            <form method="POST" action="{{ route('expense.destroy', $entry) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-semibold">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-12 text-center text-slate-500">No expense entries</td>
                                    </tr>
                                @endforelse
                                
                                <!-- Total Row -->
                                <tr class="bg-gradient-to-r from-red-600/20 to-orange-600/20 border-t border-red-500/30 font-bold">
                                    <td colspan="1" class="px-8 py-4 text-red-400">Total</td>
                                    <td class="px-8 py-4 text-red-300 text-lg">${{ number_format($expenseTotal, 2) }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Add Expense Button -->
                    <div class="border-t border-slate-700/50 p-6 bg-slate-900/50">
                        <button onclick="toggleExpenseForm()" class="bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Expense
                        </button>
                        
                        <form id="expenseForm" method="POST" action="{{ route('expense.store') }}" class="mt-4 hidden grid grid-cols-2 gap-4">
                            @csrf
                            <input type="hidden" name="month" value="{{ $currentMonth }}">
                            <input type="hidden" name="year" value="{{ $currentYear }}">
                            
                            <input type="text" name="source" placeholder="Source" required class="glass px-4 py-2 rounded-lg text-slate-900">
                            <input type="number" name="amount" placeholder="Amount" step="0.01" required class="glass px-4 py-2 rounded-lg text-slate-900">
                            <select name="tag" required class="glass px-4 py-2 rounded-lg text-slate-900">
                                <option value="">Select Tag</option>
                                <option value="Rent/Mortgage">Rent/Mortgage</option>
                                <option value="Utilities">Utilities</option>
                                <option value="Miscellaneous">Miscellaneous</option>
                                <option value="Retail">Retail</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="date" name="date" required class="glass px-4 py-2 rounded-lg text-slate-900">
                            
                            <button type="submit" class="col-span-2 bg-gradient-to-r from-blue-600 to-blue-500 text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:shadow-blue-500/50 transition">
                                Save Expense
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleIncomeForm() {
        document.getElementById('incomeForm').classList.toggle('hidden');
    }
    
    function toggleExpenseForm() {
        document.getElementById('expenseForm').classList.toggle('hidden');
    }
    
    // Chart.js - Savings Line Chart
    const ctx = document.getElementById('savingsChart').getContext('2d');
    const months = {!! $chartMonths !!};
    const data = {!! $chartData !!};
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Cumulative Savings',
                data: data,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#1f2937',
                pointBorderWidth: 2,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#cbd5e1',
                        font: { size: 12, weight: 'bold' }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#cbd5e1',
                        font: { weight: 'bold', size: 11 },
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(51, 65, 85, 0.2)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: { color: '#cbd5e1', font: { weight: 'bold', size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endsection
