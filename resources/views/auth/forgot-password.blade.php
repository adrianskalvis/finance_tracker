@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-slate-800/60 backdrop-blur-sm border border-slate-700/50 rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white italic">Finance Tracker</h1>
            <p class="text-slate-400 mt-2">Reset your password</p>
        </div>

        @if (session('status'))
            <div class="bg-emerald-900/30 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-lg mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-900/30 border border-red-500/40 text-red-300 px-4 py-3 rounded-lg mb-4">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <style>
            input:-webkit-autofill,
            input:-webkit-autofill:hover,
            input:-webkit-autofill:focus,
            input:-webkit-autofill:active {
                -webkit-box-shadow: 0 0 0 999px #0f172a inset !important;
                box-shadow: 0 0 0 999px #0f172a inset !important;
                -webkit-text-fill-color: #e2e8f0 !important;
                caret-color: #e2e8f0;
                border-color: #475569 !important;
                transition: background-color 9999s ease-in-out 0s;
            }
        </style>

        <p class="text-slate-400 text-sm mb-6">Enter your email address to receive a password reset link.</p>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-500 focus:outline-none transition"
                    placeholder="you@example.com"
                    onfocus="this.style.borderColor='#1E9E58'; this.style.boxShadow='0 0 0 2px #1E9E5840';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';">
            </div>

            <button type="submit"
                class="w-full text-white font-semibold py-2.5 rounded-lg transition"
                style="background: linear-gradient(to right, #1E9E58, #17864a);"
                onmouseover="this.style.background='linear-gradient(to right, #17864a, #136b3c)';"
                onmouseout="this.style.background='linear-gradient(to right, #1E9E58, #17864a)';">
                Send Reset Link
            </button>
        </form>

        <div class="mt-6 text-center border-t border-slate-700/50 pt-6">
            <a href="{{ route('login') }}"
                class="text-sm font-medium transition"
                style="color: #1E9E58;"
                onmouseover="this.style.color='#17c96e';"
                onmouseout="this.style.color='#1E9E58';">← Back to login</a>
        </div>
    </div>
</div>
@endsection