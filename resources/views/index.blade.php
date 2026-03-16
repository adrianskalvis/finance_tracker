@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-green-500 via-green-500 to-green-600 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-7xl font-black text-white mb-4 drop-shadow-lg">Finance Tracker</h1>
            <p class="text-2xl text-green-50 font-light mb-2">Master Your Money. Visualize Your Future.</p>
            <div class="h-1 w-24 bg-white mx-auto rounded-full mt-6"></div>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div class="bg-white rounded-2xl shadow-2xl p-8 hover:shadow-3xl transition-shadow">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Track Income</h2>
                <p class="text-gray-600 text-lg">Log your income from multiple sources. Categorize, organize, and monitor your earnings with ease.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-2xl p-8 hover:shadow-3xl transition-shadow">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Manage Expenses</h2>
                <p class="text-gray-600 text-lg">Keep track of every dollar spent. Detailed categorization helps you understand your spending patterns.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-2xl p-8 hover:shadow-3xl transition-shadow">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Visualize Trends</h2>
                <p class="text-gray-600 text-lg">Beautiful charts and graphs reveal your financial patterns. See where your money goes at a glance.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-2xl p-8 hover:shadow-3xl transition-shadow">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Analyze Savings</h2>
                <p class="text-gray-600 text-lg">Track cumulative net savings across months. Monitor progress towards your financial freedom goals.</p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            @if (auth()->check())
                <div class="space-y-4">
                    <a href="{{ route('dashboard') }}" class="inline-block bg-white text-green-600 font-bold py-4 px-12 rounded-xl text-lg hover:bg-green-50 transition-all shadow-xl hover:shadow-2xl">
                        Go to Dashboard
                    </a>
                </div>
            @else
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a href="{{ route('login') }}" class="inline-block bg-white text-green-600 font-bold py-4 px-12 rounded-xl text-lg hover:bg-green-50 transition-all shadow-xl hover:shadow-2xl">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="inline-block bg-green-700 text-white font-bold py-4 px-12 rounded-xl text-lg hover:bg-green-800 transition-all shadow-xl hover:shadow-2xl border-2 border-white">
                        Create Account
                    </a>
                </div>

                
            @endif
        </div>

        <!-- Footer Stats -->
        <div class="mt-16 grid grid-cols-3 gap-4 text-center text-white">
            <div>
                <div class="text-3xl font-bold">Unlimited</div>
                <p class="text-green-100">Transactions</p>
            </div>
            <div>
                <div class="text-3xl font-bold">Real-time</div>
                <p class="text-green-100">Analytics</p>
            </div>
            <div>
                <div class="text-3xl font-bold">Your Data</div>
                <p class="text-green-100">Always Secure</p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    div[class*="grid"] > div {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endsection
