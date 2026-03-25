@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        :root {
            --green: #1E9E58;
            --green-faint: rgba(30,158,88,0.06);
            --green-glow: rgba(30,158,88,0.3);
            --red: #e05252;
            --red-faint: rgba(224,82,82,0.07);
            --blue: #60a5fa;
            --purple: #c4b5fd;
            --bg: #080c0a;
            --bg2: #0d1410;
            --bg3: #111a14;
            --grid: rgba(30,158,88,0.06);
            --text: #c8d8c8;
            --text-dim: #5a7a5a;
            --border: rgba(30,158,88,0.18);
        }

        .analytics-wrap {
            min-height: calc(100vh - 52px);
            background: var(--bg);
            position: relative;
            overflow-x: hidden;
            font-family: 'IBM Plex Mono', monospace;
        }

        .analytics-wrap::before {
            content: '';
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

        .analytics-inner {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 28px 24px 56px;
        }

        /* ── Header ── */
        .page-header {
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header h2 { font-size: 1.4rem; font-weight: 700; color: #fff; letter-spacing: 0.02em; }
        .page-header .sub { font-size: 11px; letter-spacing: 0.22em; color: var(--green); text-transform: uppercase; margin-top: 3px; }

        .year-badge {
            font-size: 11px;
            letter-spacing: 0.2em;
            color: var(--text-dim);
            border: 1px solid var(--border);
            padding: 6px 14px;
            background: var(--bg2);
        }

        /* ── Panel base ── */
        .panel {
            background: var(--bg2);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 2px;
            background: linear-gradient(90deg, var(--green), transparent);
        }

        .panel-header {
            padding: 13px 18px;
            border-bottom: 1px solid var(--border);
        }

        .ptag { font-size: 10px; letter-spacing: 0.25em; color: var(--green); text-transform: uppercase; font-weight: 600; }
        .panel-header h3 { font-size: 13px; font-weight: 600; color: #fff; margin-top: 2px; }

        /* ── Top KPI row ── */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            padding: 16px 18px;
            position: relative;
            overflow: hidden;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 2px;
        }

        .kpi-card.k-green::before { background: var(--green); }
        .kpi-card.k-red::before   { background: var(--red); }
        .kpi-card.k-blue::before  { background: var(--blue); }
        .kpi-card.k-purple::before { background: var(--purple); }

        .kpi-label { font-size: 10px; letter-spacing: 0.2em; color: var(--text-dim); text-transform: uppercase; margin-bottom: 6px; }
        .kpi-val { font-size: 1.4rem; font-weight: 700; letter-spacing: -0.02em; }
        .kpi-card.k-green  .kpi-val { color: var(--green); text-shadow: 0 0 20px var(--green-glow); }
        .kpi-card.k-red    .kpi-val { color: var(--red); }
        .kpi-card.k-blue   .kpi-val { color: var(--blue); }
        .kpi-card.k-purple .kpi-val { color: var(--purple); }
        .kpi-sub { font-size: 10px; color: var(--text-dim); margin-top: 3px; }

        /* ── Charts row ── */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .chart-body { padding: 16px 18px; }

        /* Donut neon glow wrapper */
        .donut-wrap {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 20px;
            filter: drop-shadow(0 0 12px rgba(30,158,88,0.25));
        }

        /* Legend */
        .legend-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; }
        .legend-list li { display: flex; align-items: center; justify-content: space-between; font-size: 11px; }
        .legend-dot { width: 8px; height: 8px; border-radius: 50%; margin-right: 8px; flex-shrink: 0; box-shadow: 0 0 6px currentColor; }
        .legend-name { color: var(--text); flex: 1; }
        .legend-val { color: var(--text-dim); font-weight: 600; }

        /* Bar chart padding */
        .bar-chart-wrap { padding: 4px 0; }

        /* ── PNL ticker strip ── */
        .pnl-section { margin-bottom: 0; }

        .pnl-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pnl-title { font-size: 13px; font-weight: 700; color: #fff; letter-spacing: 0.05em; text-transform: uppercase; }
        .pnl-sub { font-size: 10px; color: var(--text-dim); letter-spacing: 0.15em; }

        .scroll-controls { display: flex; gap: 6px; }

        .scroll-btn {
            background: var(--bg2);
            border: 1px solid var(--border);
            color: var(--text-dim);
            font-family: 'IBM Plex Mono', monospace;
            font-size: 14px;
            width: 30px; height: 30px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            user-select: none;
        }

        .scroll-btn:hover { border-color: var(--green); color: var(--green); }
        .scroll-btn.paused { border-color: var(--green); color: var(--green); background: var(--green-faint); }

        /* Scrolling track */
        .pnl-track-outer {
            overflow: hidden;
            position: relative;
        }

        .pnl-track-outer::before,
        .pnl-track-outer::after {
            content: '';
            position: absolute;
            top: 0; bottom: 0;
            width: 60px;
            z-index: 2;
            pointer-events: none;
        }

        .pnl-track-outer::before { left: 0; background: linear-gradient(90deg, var(--bg), transparent); }
        .pnl-track-outer::after  { right: 0; background: linear-gradient(-90deg, var(--bg), transparent); }

        .pnl-track {
            display: flex;
            gap: 12px;
            animation: pnlScroll 40s linear infinite;
            width: max-content;
            padding: 4px 0 8px;
        }

        .pnl-track.paused { animation-play-state: paused; }

        @keyframes pnlScroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* PNL Card */
        .pnl-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            padding: 16px 18px;
            width: 200px;
            flex-shrink: 0;
            position: relative;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .pnl-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 2px;
            transition: opacity 0.2s;
        }

        .pnl-card.positive::before { background: linear-gradient(90deg, var(--green), transparent); }
        .pnl-card.negative::before { background: linear-gradient(90deg, var(--red), transparent); }
        .pnl-card.current-month { border-color: var(--green); box-shadow: 0 0 16px rgba(30,158,88,0.15); }

        .pnl-card:hover { border-color: var(--green); box-shadow: 0 0 12px rgba(30,158,88,0.1); }
        .pnl-card.negative:hover { border-color: rgba(224,82,82,0.5); box-shadow: 0 0 12px rgba(224,82,82,0.1); }

        .pnl-month { font-size: 10px; letter-spacing: 0.2em; color: var(--text-dim); text-transform: uppercase; margin-bottom: 10px; }
        .pnl-current-badge { font-size: 9px; letter-spacing: 0.15em; color: var(--green); margin-left: 6px; }

        .pnl-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
        .pnl-row-label { font-size: 10px; color: var(--text-dim); letter-spacing: 0.1em; }
        .pnl-row-val { font-size: 11px; font-weight: 600; }
        .pnl-row-val.g { color: var(--green); }
        .pnl-row-val.r { color: var(--red); }

        .pnl-divider { height: 1px; background: var(--border); margin: 8px 0; }

        .pnl-net { font-size: 1.1rem; font-weight: 700; letter-spacing: -0.01em; }
        .pnl-net.positive { color: var(--green); text-shadow: 0 0 12px var(--green-glow); }
        .pnl-net.negative { color: var(--red); }

        .pnl-bar {
            margin-top: 10px;
            height: 3px;
            background: rgba(255,255,255,0.06);
            position: relative;
            overflow: hidden;
        }

        .pnl-bar-fill {
            height: 100%;
            transition: width 0.6s ease;
        }

        .pnl-bar-fill.positive { background: var(--green); box-shadow: 0 0 6px var(--green); }
        .pnl-bar-fill.negative { background: var(--red); }

        @media (max-width: 900px) {
            .kpi-row { grid-template-columns: repeat(2, 1fr); }
            .charts-row { grid-template-columns: 1fr; }
        }
    </style>

    <div class="analytics-wrap">
        <div class="analytics-inner">

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2>Analytics</h2>
                    <div class="sub">Financial Overview</div>
                </div>
                <div class="year-badge">FY {{ $year }}</div>
            </div>

            <!-- KPI Row -->
            @php
                $totalIncome  = collect($monthsData)->sum('income');
                $totalExpense = collect($monthsData)->sum('expense');
                $totalNet     = $totalIncome - $totalExpense;
                $savingsRate  = $totalIncome > 0 ? round(($totalNet / $totalIncome) * 100, 1) : 0;
                $bestMonth    = collect($monthsData)->sortByDesc('net')->first();
                $activeMonths = collect($monthsData)->filter(fn($m) => $m['income'] > 0 || $m['expense'] > 0)->count();
                $avgMonthly   = $activeMonths > 0 ? $totalNet / $activeMonths : 0;
            @endphp

            <div class="kpi-row">
                <div class="kpi-card k-green">
                    <div class="kpi-label">Total Income</div>
                    <div class="kpi-val">${{ number_format($totalIncome, 0) }}</div>
                    <div class="kpi-sub">{{ $activeMonths }} active months</div>
                </div>
                <div class="kpi-card k-red">
                    <div class="kpi-label">Total Expenses</div>
                    <div class="kpi-val">${{ number_format($totalExpense, 0) }}</div>
                    <div class="kpi-sub">Across all categories</div>
                </div>
                <div class="kpi-card k-blue">
                    <div class="kpi-label">Net Savings</div>
                    <div class="kpi-val" style="color: {{ $totalNet >= 0 ? 'var(--blue)' : 'var(--red)' }}">
                        {{ $totalNet >= 0 ? '+' : '' }}${{ number_format($totalNet, 0) }}
                    </div>
                    <div class="kpi-sub">Savings rate: {{ $savingsRate }}%</div>
                </div>
                <div class="kpi-card k-purple">
                    <div class="kpi-label">Best Month</div>
                    <div class="kpi-val">${{ number_format($bestMonth['net'] ?? 0, 0) }}</div>
                    <div class="kpi-sub">{{ $bestMonth['name'] ?? '—' }}</div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="charts-row">

                <!-- Expense Donut -->
                <div class="panel">
                    <div class="panel-header">
                        <div class="ptag">Breakdown</div>
                        <h3>Expense Distribution</h3>
                    </div>
                    <div class="chart-body">
                        <div class="donut-wrap">
                            <canvas id="expenseChart"></canvas>
                        </div>
                        <ul class="legend-list">
                            @foreach ($expenseByTag as $item)
                                <li>
                                    <span class="legend-dot" style="background:{{ $item['color'] }}; box-shadow: 0 0 6px {{ $item['color'] }}"></span>
                                    <span class="legend-name">{{ $item['tag'] }}</span>
                                    <span class="legend-val">${{ number_format($item['amount'], 0) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Monthly Income vs Expense Bar -->
                <div class="panel">
                    <div class="panel-header">
                        <div class="ptag">Comparison</div>
                        <h3>Income vs Expenses</h3>
                    </div>
                    <div class="chart-body bar-chart-wrap">
                        <canvas id="barChart" height="280"></canvas>
                    </div>
                </div>

                <!-- Cumulative Savings Line -->
                <div class="panel">
                    <div class="panel-header">
                        <div class="ptag">Trajectory</div>
                        <h3>Cumulative Savings</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="lineChart" height="280"></canvas>
                    </div>
                </div>

            </div>

            <!-- PNL Cards — auto-scroll -->
            <div class="pnl-section">
                <div class="pnl-section-header">
                    <div>
                        <div class="pnl-title">Monthly P&amp;L</div>
                        <div class="pnl-sub">Hover or pause to inspect</div>
                    </div>
                    <div class="scroll-controls">
                        <div class="scroll-btn" id="pauseBtn" onclick="toggleScroll()" title="Pause/Resume">⏸</div>
                        <div class="scroll-btn" onclick="nudge(-1)">‹</div>
                        <div class="scroll-btn" onclick="nudge(1)">›</div>
                    </div>
                </div>

                <div class="pnl-track-outer" id="pnlOuter">
                    <div class="pnl-track" id="pnlTrack">
                        @php
                            $maxAbsNet = collect($monthsData)->map(fn($m) => abs($m['net']))->max() ?: 1;
                            // Double the cards for seamless loop
                            $cards = array_merge($monthsData, $monthsData);
                        @endphp
                        @foreach ($cards as $month)
                            @php
                                $isPos = $month['net'] >= 0;
                                $pct   = min(100, round((abs($month['net']) / $maxAbsNet) * 100));
                            @endphp
                            <div class="pnl-card {{ $isPos ? 'positive' : 'negative' }} {{ $month['isCurrentMonth'] ? 'current-month' : '' }}"
                                 onmouseenter="pauseScroll()" onmouseleave="resumeScroll()">
                                <div class="pnl-month">
                                    {{ $month['name'] }}
                                    @if ($month['isCurrentMonth'])<span class="pnl-current-badge">● NOW</span>@endif
                                </div>
                                <div class="pnl-row">
                                    <span class="pnl-row-label">INC</span>
                                    <span class="pnl-row-val g">${{ number_format($month['income'], 0) }}</span>
                                </div>
                                <div class="pnl-row">
                                    <span class="pnl-row-label">EXP</span>
                                    <span class="pnl-row-val r">${{ number_format($month['expense'], 0) }}</span>
                                </div>
                                <div class="pnl-divider"></div>
                                <div class="pnl-row">
                                    <span class="pnl-row-label">NET</span>
                                    <span class="pnl-net {{ $isPos ? 'positive' : 'negative' }}">
                            {{ $isPos ? '+' : '' }}${{ number_format($month['net'], 0) }}
                        </span>
                                </div>
                                <div class="pnl-bar">
                                    <div class="pnl-bar-fill {{ $isPos ? 'positive' : 'negative' }}" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // ── Chart data from PHP ──
        const chartLabels = {!! $chartLabels !!};
        const chartData   = {!! $chartData !!};
        const chartColors = {!! $chartColors !!};
        const months      = @json(collect($monthsData)->pluck('name'));
        const incomes     = @json(collect($monthsData)->pluck('income'));
        const expenses    = @json(collect($monthsData)->pluck('expense'));

        // Cumulative savings
        const cumulative = [];
        let running = 0;
        @foreach ($monthsData as $month)
            running += {{ $month['net'] }};
        cumulative.push(running);
        @endforeach

        const FONT = { family: 'IBM Plex Mono', size: 10 };
        const GRID = 'rgba(30,158,88,0.06)';

        // ── 1. Expense Donut ──
        new Chart(document.getElementById('expenseChart'), {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors,
                    borderColor: '#0d1410',
                    borderWidth: 3,
                    hoverBorderColor: '#fff',
                    hoverBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0d1410',
                        borderColor: 'rgba(30,158,88,0.3)',
                        borderWidth: 1,
                        titleColor: '#c8d8c8',
                        bodyColor: '#1E9E58',
                        titleFont: FONT,
                        bodyFont: { ...FONT, weight: '600' },
                        callbacks: {
                            label: ctx => ` $${ctx.parsed.toLocaleString()}`
                        }
                    }
                }
            }
        });

        // ── 2. Income vs Expense Bar ──
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Income',
                        data: incomes,
                        backgroundColor: 'rgba(30,158,88,0.7)',
                        borderColor: '#1E9E58',
                        borderWidth: 1,
                        borderRadius: 0,
                    },
                    {
                        label: 'Expenses',
                        data: expenses,
                        backgroundColor: 'rgba(224,82,82,0.6)',
                        borderColor: '#e05252',
                        borderWidth: 1,
                        borderRadius: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#5a7a5a', font: FONT, boxWidth: 10 }
                    },
                    tooltip: {
                        backgroundColor: '#0d1410',
                        borderColor: 'rgba(30,158,88,0.3)',
                        borderWidth: 1,
                        titleColor: '#c8d8c8',
                        bodyColor: '#c8d8c8',
                        titleFont: FONT,
                        bodyFont: FONT,
                        callbacks: { label: ctx => ` ${ctx.dataset.label}: $${ctx.parsed.y.toLocaleString()}` }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#5a7a5a', font: FONT },
                        grid: { display: false },
                        border: { color: 'rgba(30,158,88,0.1)' }
                    },
                    y: {
                        ticks: { color: '#5a7a5a', font: FONT, callback: v => '$' + v.toLocaleString() },
                        grid: { color: GRID },
                        border: { color: 'transparent' }
                    }
                }
            }
        });

        // ── 3. Cumulative Savings Line ──
        const lineCtx = document.getElementById('lineChart');
        const allPositive = cumulative.every(v => v >= 0);
        const lineColor   = allPositive ? '#1E9E58' : '#e05252';
        const lineFill    = allPositive ? 'rgba(30,158,88,0.07)' : 'rgba(224,82,82,0.07)';

        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Cumulative Savings',
                    data: cumulative,
                    borderColor: lineColor,
                    backgroundColor: lineFill,
                    borderWidth: 2,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: lineColor,
                    pointBorderColor: '#0d1410',
                    pointBorderWidth: 2,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#5a7a5a', font: FONT } },
                    tooltip: {
                        backgroundColor: '#0d1410',
                        borderColor: 'rgba(30,158,88,0.3)',
                        borderWidth: 1,
                        titleColor: '#c8d8c8',
                        bodyColor: lineColor,
                        titleFont: FONT,
                        bodyFont: { ...FONT, weight: '600' },
                        callbacks: { label: ctx => ` $${ctx.parsed.y.toLocaleString()}` }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#5a7a5a', font: FONT },
                        grid: { display: false },
                        border: { color: 'rgba(30,158,88,0.1)' }
                    },
                    y: {
                        ticks: { color: '#5a7a5a', font: FONT, callback: v => '$' + v.toLocaleString() },
                        grid: { color: GRID },
                        border: { color: 'transparent' }
                    }
                }
            }
        });

        // ── PNL scroll controls ──
        let scrollPaused = false;
        const track    = document.getElementById('pnlTrack');
        const pauseBtn = document.getElementById('pauseBtn');

        function pauseScroll() {
            track.classList.add('paused');
        }

        function resumeScroll() {
            if (!scrollPaused) track.classList.remove('paused');
        }

        function toggleScroll() {
            scrollPaused = !scrollPaused;
            if (scrollPaused) {
                track.classList.add('paused');
                pauseBtn.textContent = '⏵';
                pauseBtn.classList.add('paused');
            } else {
                track.classList.remove('paused');
                pauseBtn.textContent = '⏸';
                pauseBtn.classList.remove('paused');
            }
        }

        function nudge(dir) {
            const outer = document.getElementById('pnlOuter');
            outer.scrollLeft += dir * 220;
        }
    </script>

@endsection
