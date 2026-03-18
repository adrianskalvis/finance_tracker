@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-slate-800/60 backdrop-blur-sm border border-slate-700/50 rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white italic">Finance Tracker</h1>
            <p class="text-slate-400 mt-2">Login to your account</p>
        </div>

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

            input[type="checkbox"] {
                appearance: none;
                -webkit-appearance: none;
                width: 1rem;
                height: 1rem;
                background-color: #1e293b;
                border: 1.5px solid #475569;
                border-radius: 0.25rem;
                cursor: pointer;
                position: relative;
                transition: all 0.15s ease;
            }

            input[type="checkbox"]:checked {
                background-color: #1E9E58;
                border-color: #1E9E58;
            }

            input[type="checkbox"]:checked::after {
                content: '';
                position: absolute;
                left: 4px;
                top: 1px;
                width: 5px;
                height: 9px;
                border: 2px solid white;
                border-top: none;
                border-left: none;
                transform: rotate(45deg);
            }

            input[type="checkbox"]:focus {
                outline: none;
                box-shadow: 0 0 0 2px #1E9E5840;
                border-color: #1E9E58;
            }
        </style>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-500 focus:outline-none transition"
                    placeholder="you@example.com"
                    onfocus="this.style.borderColor='#1E9E58'; this.style.boxShadow='0 0 0 2px #1E9E5840';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2.5 bg-slate-900/60 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-500 focus:outline-none transition"
                    placeholder="••••••••"
                    onfocus="this.style.borderColor='#1E9E58'; this.style.boxShadow='0 0 0 2px #1E9E5840';"
                    onblur="this.style.borderColor=''; this.style.boxShadow='';">
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" class="text-sm text-slate-400 cursor-pointer select-none">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}"
                    class="text-sm font-medium transition"
                    style="color: #1E9E58;"
                    onmouseover="this.style.color='#17c96e';"
                    onmouseout="this.style.color='#1E9E58';">Forgot password?</a>
            </div>

            <button type="submit"
                class="w-full text-white font-semibold py-2.5 rounded-lg transition mt-2"
                style="background: linear-gradient(to right, #1E9E58, #17864a);"
                onmouseover="this.style.background='linear-gradient(to right, #17864a, #136b3c)';"
                onmouseout="this.style.background='linear-gradient(to right, #1E9E58, #17864a)';">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center border-t border-slate-700/50 pt-6">
            <p class="text-slate-400 text-sm">Don't have an account?
                <a href="{{ route('register') }}"
                    class="font-semibold ml-1 transition"
                    style="color: #1E9E58;"
                    onmouseover="this.style.color='#17c96e';"
                    onmouseout="this.style.color='#1E9E58';">Sign up</a>
            </p>
        </div>
    </div>
</div>
@endsection