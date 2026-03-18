<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { transition: all 0.3s ease; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .glass-dark { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .gradient-text { background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-green-950 to-slate-950 min-h-screen">
    @if (auth()->check())
        <nav class="glass-dark sticky top-0 z-50 border-b">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold gradient-text">Finance Tracker</h1>
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-emerald-400 text-sm font-medium transition">Dashboard</a>
                    <a href="{{ route('analytics') }}" class="text-slate-400 hover:text-emerald-400 text-sm font-medium transition">Analytics</a>
                    <span class="text-slate-400 text-sm">{{ auth()->user()->name }}</span>
                    <a href="{{ route('logout') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:shadow-lg hover:shadow-red-500/50 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Logout
                    </a>
                </div>
            </div>
        </nav>
    @endif

    @if (session('success'))
        <div class="fixed top-6 right-6 bg-emerald-600/90 backdrop-blur text-white px-6 py-3 rounded-lg shadow-xl z-50 border border-emerald-400/30">
            {{ session('success') }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>
</body>
</html>
