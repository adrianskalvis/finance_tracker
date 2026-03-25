<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@300;400;500;600;700&family=IBM+Plex+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --green: #1E9E58;
            --green-glow: rgba(30,158,88,0.15);
            --green-faint: rgba(30,158,88,0.06);
            --red: #e05252;
            --bg: #080c0a;
            --bg2: #0d1410;
            --bg3: #111a14;
            --grid: rgba(30,158,88,0.06);
            --text: #c8d8c8;
            --text-dim: #5a7a5a;
            --border: rgba(30,158,88,0.18);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'IBM Plex Mono', monospace;
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg2); }
        ::-webkit-scrollbar-thumb { background: var(--border); }
        ::-webkit-scrollbar-thumb:hover { background: var(--green); }

        /* Nav */
        .app-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(8,12,10,0.92);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 52px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-brand .brand-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 8px var(--green);
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .nav-brand span {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-link {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--text-dim);
            text-decoration: none;
            padding: 6px 14px;
            border: 1px solid transparent;
            transition: all 0.15s;
        }

        .nav-link:hover {
            color: var(--green);
            border-color: var(--border);
            background: var(--green-faint);
        }

        .nav-link.active {
            color: var(--green);
            border-color: var(--border);
        }

        .nav-divider {
            width: 1px;
            height: 20px;
            background: var(--border);
            margin: 0 8px;
        }

        .nav-user {
            font-size: 11px;
            letter-spacing: 0.1em;
            color: var(--text-dim);
            padding: 0 8px;
        }

        .nav-logout {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--red);
            text-decoration: none;
            padding: 6px 14px;
            border: 1px solid rgba(224,82,82,0.25);
            transition: all 0.15s;
            background: none;
            cursor: pointer;
        }

        .nav-logout:hover {
            background: rgba(224,82,82,0.08);
            box-shadow: 0 0 12px rgba(224,82,82,0.2);
        }

        /* Toast */
        .toast {
            position: fixed;
            top: 64px;
            right: 24px;
            z-index: 200;
            background: var(--bg2);
            border: 1px solid var(--green);
            color: var(--green);
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            letter-spacing: 0.08em;
            padding: 12px 20px;
            box-shadow: 0 0 24px rgba(30,158,88,0.2);
            animation: toastIn 0.3s ease, toastOut 0.3s ease 3.7s forwards;
        }

        .toast::before {
            content: '▶ ';
            color: var(--green);
        }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(20px); }
        }

        /* Glass kept for compat */
        .glass { background: rgba(13,20,16,0.9); border: 1px solid var(--border); }
        .glass-dark { background: var(--bg2); border: 1px solid var(--border); }
    </style>
</head>
<body>

    @if (auth()->check())
    <nav class="app-nav">
        <a href="{{ route('dashboard') }}" class="nav-brand">
            <span class="brand-dot"></span>
            <span>Finance Tracker</span>
        </a>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('analytics') }}"
               class="nav-link {{ request()->routeIs('analytics') ? 'active' : '' }}">Analytics</a>
            <div class="nav-divider"></div>
            <span class="nav-user">{{ auth()->user()->name }}</span>
            <a href="{{ route('logout') }}" class="nav-logout">Logout</a>
        </div>
    </nav>
    @endif

    @if (session('success'))
        <div class="toast">{{ session('success') }}</div>
    @endif

    <main>
        @yield('content')
    </main>

    @if (session('success'))
    <script>
        setTimeout(() => {
            const t = document.querySelector('.toast');
            if (t) t.remove();
        }, 4000);
    </script>
    @endif

</body>
</html>
