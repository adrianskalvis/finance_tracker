@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@300;400;500;600;700&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap');

    :root {
        --green: #1E9E58;
        --green-dim: #157a42;
        --green-glow: rgba(30, 158, 88, 0.15);
        --green-faint: rgba(30, 158, 88, 0.06);
        --bg: #080c0a;
        --bg2: #0d1410;
        --grid: rgba(30, 158, 88, 0.07);
        --text: #c8d8c8;
        --text-dim: #5a7a5a;
        --border: rgba(30, 158, 88, 0.2);
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    .terminal-page {
        min-height: 100vh;
        background-color: var(--bg);
        font-family: 'IBM Plex Mono', monospace;
        color: var(--text);
        position: relative;
        overflow: hidden;
    }

    /* Animated grid background */
    .grid-bg {
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(var(--grid) 1px, transparent 1px),
            linear-gradient(90deg, var(--grid) 1px, transparent 1px);
        background-size: 48px 48px;
        animation: gridDrift 40s linear infinite;
        pointer-events: none;
        z-index: 0;
    }

    @keyframes gridDrift {
        0%   { background-position: 0 0; }
        100% { background-position: 48px 48px; }
    }

    /* Slow scanning line */
    .scan-line {
        position: fixed;
        left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--green), transparent);
        opacity: 0.25;
        animation: scan 12s linear infinite;
        pointer-events: none;
        z-index: 1;
    }

    @keyframes scan {
        0%   { top: -2px; }
        100% { top: 100vh; }
    }

    /* Radial glow center */
    .center-glow {
        position: fixed;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 800px; height: 800px;
        background: radial-gradient(circle, rgba(30,158,88,0.07) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    /* Top status bar */
    .status-bar {
        position: relative;
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 32px;
        border-bottom: 1px solid var(--border);
        background: rgba(8,12,10,0.8);
        backdrop-filter: blur(4px);
        font-size: 11px;
        color: var(--text-dim);
        letter-spacing: 0.08em;
    }

    .status-bar .status-left { display: flex; gap: 24px; }
    .status-bar .status-dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--green);
        margin-right: 6px;
        animation: blink 2s ease-in-out infinite;
        vertical-align: middle;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    /* Ticker tape */
    .ticker-wrap {
        position: relative;
        z-index: 10;
        overflow: hidden;
        border-bottom: 1px solid var(--border);
        background: rgba(13,20,16,0.9);
        padding: 7px 0;
    }

    .ticker-track {
        display: flex;
        gap: 0;
        white-space: nowrap;
        animation: tickerScroll 35s linear infinite;
    }

    @keyframes tickerScroll {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .ticker-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 28px;
        font-size: 11px;
        letter-spacing: 0.05em;
        border-right: 1px solid var(--border);
    }

    .ticker-item .sym { color: var(--green); font-weight: 600; }
    .ticker-item .val { color: var(--text); }
    .ticker-item .chg-up { color: #4ade80; }
    .ticker-item .chg-dn { color: #f87171; }

    /* Main content */
    .main-content {
        position: relative;
        z-index: 10;
        max-width: 1100px;
        margin: 0 auto;
        padding: 60px 32px 40px;
    }

    /* Hero */
    .hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 64px;
    }

    .logo-wrap {
        margin-bottom: 28px;
    }

    .logo-wrap img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 2px solid var(--border);
        box-shadow: 0 0 32px var(--green-glow), 0 0 80px rgba(30,158,88,0.06);
        display: block;
        margin: 0 auto 12px;
    }

    .logo-name {
        font-size: 11px;
        letter-spacing: 0.25em;
        color: var(--text-dim);
        text-transform: uppercase;
    }

    .hero-eyebrow {
        font-size: 11px;
        letter-spacing: 0.3em;
        color: var(--green);
        text-transform: uppercase;
        margin-bottom: 20px;
        opacity: 0;
        animation: fadeUp 0.6s ease forwards 0.2s;
    }

    .hero-title {
        font-size: clamp(2.8rem, 6vw, 5rem);
        font-weight: 700;
        line-height: 1.05;
        letter-spacing: -0.02em;
        color: #ffffff;
        margin-bottom: 20px;
        opacity: 0;
        animation: fadeUp 0.6s ease forwards 0.4s;
    }

    .hero-title span {
        color: var(--green);
        text-shadow: 0 0 40px rgba(30,158,88,0.4);
    }

    .hero-sub {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1.05rem;
        color: var(--text-dim);
        max-width: 480px;
        line-height: 1.7;
        margin-bottom: 40px;
        opacity: 0;
        animation: fadeUp 0.6s ease forwards 0.6s;
    }

    .hero-cta {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        justify-content: center;
        opacity: 0;
        animation: fadeUp 0.6s ease forwards 0.8s;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .btn-primary {
        display: inline-block;
        background: var(--green);
        color: #fff;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 13px 32px;
        border: 1px solid var(--green);
        text-decoration: none;
        transition: all 0.2s;
        clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);
    }

    .btn-primary:hover {
        background: #17c96e;
        border-color: #17c96e;
        box-shadow: 0 0 24px rgba(30,158,88,0.5);
    }

    .btn-secondary {
        display: inline-block;
        background: transparent;
        color: var(--green);
        font-family: 'IBM Plex Mono', monospace;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 13px 32px;
        border: 1px solid var(--border);
        text-decoration: none;
        transition: all 0.2s;
        clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);
    }

    .btn-secondary:hover {
        border-color: var(--green);
        background: var(--green-faint);
        box-shadow: 0 0 16px rgba(30,158,88,0.2);
    }

    /* Feature panels */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1px;
        background: var(--border);
        border: 1px solid var(--border);
        margin-bottom: 64px;
        opacity: 0;
        animation: fadeUp 0.6s ease forwards 1s;
    }

    .feature-panel {
        background: var(--bg2);
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
        transition: background 0.2s;
    }

    .feature-panel:hover {
        background: var(--green-faint);
    }

    .feature-panel::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 3px; height: 100%;
        background: var(--green);
        opacity: 0;
        transition: opacity 0.2s;
    }

    .feature-panel:hover::before { opacity: 1; }

    .feature-tag {
        font-size: 10px;
        letter-spacing: 0.25em;
        color: var(--green);
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .feature-title {
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 8px;
        letter-spacing: 0.02em;
    }

    .feature-desc {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        color: var(--text-dim);
        line-height: 1.65;
    }

    /* Stats row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: var(--border);
        border: 1px solid var(--border);
        margin-bottom: 0;
        opacity: 0;
        animation: fadeUp 0.6s ease forwards 1.1s;
    }

    .stat-cell {
        background: var(--bg2);
        padding: 24px 32px;
        text-align: center;
    }

    .stat-val {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--green);
        letter-spacing: -0.02em;
        text-shadow: 0 0 20px rgba(30,158,88,0.3);
    }

    .stat-label {
        font-size: 10px;
        letter-spacing: 0.2em;
        color: var(--text-dim);
        text-transform: uppercase;
        margin-top: 4px;
    }

    /* Bottom bar */
    .bottom-bar {
        position: relative;
        z-index: 10;
        border-top: 1px solid var(--border);
        padding: 12px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 10px;
        color: var(--text-dim);
        letter-spacing: 0.1em;
        margin-top: 48px;
    }

    @media (max-width: 640px) {
        .features-grid, .stats-row { grid-template-columns: 1fr; }
        .hero-title { font-size: 2.2rem; }
        .status-bar .status-left { gap: 12px; }
    }
</style>

<div class="terminal-page">
    <div class="grid-bg"></div>
    <div class="scan-line"></div>
    <div class="center-glow"></div>

    <!-- Status bar -->
    <div class="status-bar">
        <div class="status-left">
            <span><span class="status-dot"></span>SYSTEM ONLINE</span>
            <span id="live-time">--:--:--</span>
        </div>
        <div>FINANCE TRACKER v1.0 &nbsp;|&nbsp; SECURE SESSION</div>
    </div>

    <!-- Ticker tape -->
    <div class="ticker-wrap">
        <div class="ticker-track" id="ticker">
            <!-- JS fills double set for seamless loop -->
        </div>
    </div>

    <!-- Main -->
    <div class="main-content">

        <!-- Hero -->
        <div class="hero">
            <div class="logo-wrap">
                <img src="{{ asset('images/finance_logo_nobg.png') }}" alt="Finance Tracker Logo">
                <div class="logo-name">Finance Tracker</div>
            </div>

            <h1 class="hero-title">
                Master Your<br><span>Money.</span>
            </h1>

            <p class="hero-sub">
                Track income, analyze expenses, and visualize savings trends — all in one terminal.
            </p>

            <div class="hero-cta">
                @if (auth()->check())
                    <a href="{{ route('dashboard') }}" class="btn-primary">Open Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-secondary">Create Account</a>
                @endif
            </div>
        </div>

        <!-- Features -->
        <div class="features-grid">
            <div class="feature-panel">
                <div class="feature-tag">01 / Income</div>
                <div class="feature-title">Track Income Sources</div>
                <div class="feature-desc">Log earnings from multiple sources. Categorize, tag, and monitor every inflow with precision.</div>
            </div>
            <div class="feature-panel">
                <div class="feature-tag">02 / Expenses</div>
                <div class="feature-title">Manage Expenses</div>
                <div class="feature-desc">Record every outflow. Detailed tagging reveals your spending patterns at a glance.</div>
            </div>
            <div class="feature-panel">
                <div class="feature-tag">03 / Charts</div>
                <div class="feature-title">Visualize Trends</div>
                <div class="feature-desc">Real-time charts surface your financial patterns. See the full picture, not just the numbers.</div>
            </div>
            <div class="feature-panel">
                <div class="feature-tag">04 / Savings</div>
                <div class="feature-title">Analyze Net Savings</div>
                <div class="feature-desc">Monitor cumulative savings across months. Track your trajectory toward financial freedom.</div>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-cell">
                <div class="stat-val">∞</div>
                <div class="stat-label">Transactions</div>
            </div>
            <div class="stat-cell">
                <div class="stat-val">Live</div>
                <div class="stat-label">Analytics</div>
            </div>
            <div class="stat-cell">
                <div class="stat-val">AES</div>
                <div class="stat-label">Encrypted</div>
            </div>
        </div>
    </div>

    <!-- Bottom bar -->
    <div class="bottom-bar">
        <span>© {{ date('Y') }} FINANCE TRACKER</span>
        <span>ALL RIGHTS RESERVED</span>
    </div>
</div>

<script>
    // Live clock
    function updateClock() {
        const now = new Date();
        document.getElementById('live-time').textContent =
            now.toTimeString().slice(0,8);
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Ticker data
    const tickers = [
        { sym: 'INCOME',  val: '+$4,200.00', chg: '+12.4%',  up: true },
        { sym: 'SAVINGS', val: '$18,540.00', chg: '+8.1%',   up: true },
        { sym: 'RENT',    val: '-$1,200.00', chg: '0.0%',    up: false },
        { sym: 'UTIL',    val: '-$180.00',   chg: '-2.3%',   up: false },
        { sym: 'FREELANCE', val: '+$950.00', chg: '+21.0%',  up: true },
        { sym: 'RETAIL',  val: '-$340.00',   chg: '+5.2%',   up: false },
        { sym: 'NET',     val: '+$3,430.00', chg: '+9.7%',   up: true },
        { sym: 'MISC',    val: '-$95.00',    chg: '-1.1%',   up: false },
    ];

    function buildTicker(items) {
        return items.map(t => `
            <span class="ticker-item">
                <span class="sym">${t.sym}</span>
                <span class="val">${t.val}</span>
                <span class="${t.up ? 'chg-up' : 'chg-dn'}">${t.chg}</span>
            </span>
        `).join('');
    }

    // Double the items for seamless infinite scroll
    const track = document.getElementById('ticker');
    const html = buildTicker(tickers);
    track.innerHTML = html + html;
</script>

@endsection