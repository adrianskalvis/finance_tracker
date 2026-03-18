@extends('layouts.app')

@section('content')

<style>
    :root {
        --green: #1E9E58;
        --green-dim: #157a42;
        --green-glow: rgba(30,158,88,0.15);
        --green-faint: rgba(30,158,88,0.06);
        --red: #e05252;
        --red-faint: rgba(224,82,82,0.07);
        --bg: #080c0a;
        --bg2: #0d1410;
        --bg3: #111a14;
        --grid: rgba(30,158,88,0.06);
        --text: #c8d8c8;
        --text-dim: #5a7a5a;
        --border: rgba(30,158,88,0.18);
        --border-red: rgba(224,82,82,0.25);
    }

    .dash-wrap {
        min-height: calc(100vh - 52px);
        background: var(--bg);
        position: relative;
        overflow-x: hidden;
    }

    .dash-wrap::before {
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

    .dash-inner {
        position: relative;
        z-index: 1;
        max-width: 1400px;
        margin: 0 auto;
        padding: 28px 24px 56px;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .top-bar-left h2 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.02em;
    }

    .top-bar-left .period-label {
        font-size: 11px;
        letter-spacing: 0.22em;
        color: var(--green);
        text-transform: uppercase;
        margin-top: 3px;
    }

    .month-selector {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .month-selector select {
        background: var(--bg3);
        border: 1px solid var(--border);
        color: var(--text);
        font-family: 'IBM Plex Mono', monospace;
        font-size: 12px;
        padding: 7px 12px;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        transition: border-color 0.2s;
    }

    .month-selector select:focus {
        border-color: var(--green);
        box-shadow: 0 0 0 2px rgba(30,158,88,0.15);
    }

    .month-selector select option {
        background: var(--bg3);
        color: var(--text);
    }

    .btn-load {
        background: var(--green);
        color: #fff;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 8px 20px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);
    }

    .btn-load:hover {
        background: #17c96e;
        box-shadow: 0 0 16px rgba(30,158,88,0.45);
    }

    .dash-grid {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 16px;
        align-items: start;
    }

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
        z-index: 1;
    }

    .panel-red { border-color: var(--border-red); }
    .panel-red::before { background: linear-gradient(90deg, var(--red), transparent); }

    .panel-header {
        padding: 13px 18px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .panel-red .panel-header { border-bottom-color: var(--border-red); }

    .ptag {
        font-size: 10px;
        letter-spacing: 0.25em;
        color: var(--green);
        text-transform: uppercase;
        font-weight: 600;
    }

    .ptag-red { color: var(--red); }

    .panel-header h3 {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        letter-spacing: 0.03em;
    }

    .sidebar { display: flex; flex-direction: column; gap: 14px; }

    .summary-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        padding: 16px 18px;
        position: relative;
        overflow: hidden;
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 3px; height: 100%;
    }

    .summary-card.income::before  { background: var(--green); }
    .summary-card.expense::before { background: var(--red); }
    .summary-card.savings::before { background: #60a5fa; }

    .s-label {
        font-size: 10px;
        letter-spacing: 0.2em;
        color: var(--text-dim);
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .s-val {
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .summary-card.income  .s-val { color: var(--green); text-shadow: 0 0 20px rgba(30,158,88,0.3); }
    .summary-card.expense .s-val { color: var(--red); }
    .summary-card.savings .s-val { color: #60a5fa; }

    .s-sub {
        font-size: 10px;
        color: var(--text-dim);
        margin-top: 3px;
    }

    .chart-inner { padding: 16px 18px 18px; }

    .tables-col { display: flex; flex-direction: column; gap: 16px; }

    .table-wrap { overflow-x: auto; }

    table { width: 100%; border-collapse: collapse; font-size: 12px; }

    thead tr { border-bottom: 1px solid var(--border); }
    .panel-red thead tr { border-bottom-color: var(--border-red); }

    th {
        padding: 10px 16px;
        text-align: left;
        font-size: 10px;
        letter-spacing: 0.2em;
        color: var(--text-dim);
        text-transform: uppercase;
        font-weight: 600;
        white-space: nowrap;
    }

    tbody tr {
        border-bottom: 1px solid rgba(30,158,88,0.06);
        transition: background 0.15s;
    }

    .panel-red tbody tr { border-bottom-color: rgba(224,82,82,0.06); }

    tbody tr:hover { background: var(--green-faint); }
    .panel-red tbody tr:hover { background: var(--red-faint); }

    td { padding: 10px 16px; color: var(--text); vertical-align: middle; }

    td.amt-g { color: var(--green); font-weight: 600; }
    td.amt-r { color: var(--red);   font-weight: 600; }
    td.dim    { color: var(--text-dim); font-size: 11px; }

    .tag-pill {
        display: inline-block;
        padding: 2px 9px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.06em;
        border: 1px solid;
        white-space: nowrap;
    }

    .tp-green  { color: #6ee7b7; border-color: rgba(110,231,183,0.3); background: rgba(110,231,183,0.05); }
    .tp-pink   { color: #f9a8d4; border-color: rgba(249,168,212,0.3); background: rgba(249,168,212,0.05); }
    .tp-yellow { color: #fde68a; border-color: rgba(253,230,138,0.3); background: rgba(253,230,138,0.05); }
    .tp-blue   { color: #93c5fd; border-color: rgba(147,197,253,0.3); background: rgba(147,197,253,0.05); }
    .tp-purple { color: #c4b5fd; border-color: rgba(196,181,253,0.3); background: rgba(196,181,253,0.05); }
    .tp-slate  { color: #94a3b8; border-color: rgba(148,163,184,0.3); background: rgba(148,163,184,0.05); }

    tr.total-row td {
        font-weight: 700;
        font-size: 12px;
        padding: 11px 16px;
        border-top: 1px solid var(--border);
        background: rgba(30,158,88,0.04);
    }

    .panel-red tr.total-row td {
        border-top-color: var(--border-red);
        background: rgba(224,82,82,0.04);
    }

    tr.empty-row td {
        padding: 28px 16px;
        text-align: center;
        color: var(--text-dim);
        font-size: 11px;
        letter-spacing: 0.15em;
    }

    .act-btn {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.08em;
        background: none;
        border: 1px solid transparent;
        cursor: pointer;
        padding: 3px 7px;
        transition: all 0.15s;
        text-decoration: none;
    }

    .act-btn.edit { color: #60a5fa; }
    .act-btn.edit:hover { color: #93c5fd; border-color: rgba(96,165,250,0.3); background: rgba(96,165,250,0.05); }
    .act-btn.del  { color: var(--red); }
    .act-btn.del:hover  { color: #f87171; border-color: rgba(224,82,82,0.3); background: rgba(224,82,82,0.05); }

    .panel-footer {
        padding: 14px 18px;
        border-top: 1px solid var(--border);
        background: rgba(0,0,0,0.15);
    }

    .panel-red .panel-footer { border-top-color: var(--border-red); }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 7px 16px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        clip-path: polygon(5px 0%, 100% 0%, calc(100% - 5px) 100%, 0% 100%);
    }

    .btn-add.green { background: var(--green); color: #fff; }
    .btn-add.green:hover { background: #17c96e; box-shadow: 0 0 12px rgba(30,158,88,0.4); }
    .btn-add.red   { background: var(--red); color: #fff; }
    .btn-add.red:hover   { background: #f87171; box-shadow: 0 0 12px rgba(224,82,82,0.35); }

    .entry-form {
        display: none;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 12px;
    }

    .entry-form.open { display: grid; }

    .entry-form input,
    .entry-form select {
        background: var(--bg3);
        border: 1px solid var(--border);
        color: var(--text);
        font-family: 'IBM Plex Mono', monospace;
        font-size: 12px;
        padding: 8px 10px;
        outline: none;
        width: 100%;
        transition: border-color 0.2s;
    }

    .entry-form select option { background: var(--bg3); color: var(--text); }

    .entry-form input:-webkit-autofill,
    .entry-form input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 999px #111a14 inset !important;
        -webkit-text-fill-color: #c8d8c8 !important;
    }

    .entry-form input:focus,
    .entry-form select:focus {
        border-color: var(--green);
        box-shadow: 0 0 0 2px rgba(30,158,88,0.12);
    }

    .entry-form input::placeholder { color: var(--text-dim); }

    .btn-save {
        grid-column: span 2;
        background: var(--bg3);
        border: 1px solid var(--border);
        color: var(--green);
        font-family: 'IBM Plex Mono', monospace;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 9px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-save:hover {
        background: var(--green-faint);
        border-color: var(--green);
        box-shadow: 0 0 12px rgba(30,158,88,0.15);
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.8);
        backdrop-filter: blur(4px);
        z-index: 500;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.open { display: flex; }

    .modal-box {
        background: var(--bg2);
        border: 1px solid var(--border);
        width: 100%;
        max-width: 460px;
        margin: 20px;
        position: relative;
    }

    .modal-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 2px;
        background: linear-gradient(90deg, var(--green), transparent);
    }

    .modal-header {
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h4 {
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.15em;
        text-transform: uppercase;
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-dim);
        cursor: pointer;
        font-size: 16px;
        padding: 0;
        transition: color 0.15s;
        line-height: 1;
    }

    .modal-close:hover { color: #fff; }

    .modal-body { padding: 18px; }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .form-field { display: flex; flex-direction: column; gap: 5px; }

    .form-field label {
        font-size: 10px;
        letter-spacing: 0.2em;
        color: var(--text-dim);
        text-transform: uppercase;
    }

    .form-field input,
    .form-field select {
        background: var(--bg3);
        border: 1px solid var(--border);
        color: var(--text);
        font-family: 'IBM Plex Mono', monospace;
        font-size: 12px;
        padding: 8px 10px;
        outline: none;
        width: 100%;
        transition: border-color 0.2s;
    }

    .form-field select option { background: var(--bg3); color: var(--text); }

    .form-field input:focus,
    .form-field select:focus {
        border-color: var(--green);
        box-shadow: 0 0 0 2px rgba(30,158,88,0.12);
    }

    .modal-footer {
        padding: 12px 18px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .btn-modal-save {
        background: var(--green);
        color: #fff;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 8px 22px;
        border: none;
        cursor: pointer;
        clip-path: polygon(5px 0%, 100% 0%, calc(100% - 5px) 100%, 0% 100%);
        transition: all 0.2s;
    }

    .btn-modal-save:hover { background: #17c96e; }

    .btn-modal-cancel {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-dim);
        font-family: 'IBM Plex Mono', monospace;
        font-size: 11px;
        padding: 8px 16px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-cancel:hover { border-color: var(--text-dim); color: var(--text); }

    @media (max-width: 900px) {
        .dash-grid { grid-template-columns: 1fr; }
        .entry-form { grid-template-columns: 1fr; }
        .btn-save { grid-column: span 1; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>

<div class="dash-wrap">
<div class="dash-inner">

    <!-- Top bar -->
    <div class="top-bar">
        <div class="top-bar-left">
            <h2>Dashboard</h2>
            <div class="period-label">
                {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') }}
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="month-selector">
            <select name="month">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate(2000, $m, 1)->format('F') }}
                    </option>
                @endfor
            </select>
            <select name="year">
                @for ($y = 2020; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn-load">Load</button>
        </form>
    </div>

    <!-- Grid -->
    <div class="dash-grid">

        <!-- Sidebar -->
        <div class="sidebar">

            <div class="summary-card income">
                <div class="s-label">Monthly Income</div>
                <div class="s-val">${{ number_format($incomeTotal, 2) }}</div>
                <div class="s-sub">{{ $incomeEntries->count() }} {{ Str::plural('entry', $incomeEntries->count()) }}</div>
            </div>

            <div class="summary-card expense">
                <div class="s-label">Monthly Expenses</div>
                <div class="s-val">${{ number_format($expenseTotal, 2) }}</div>
                <div class="s-sub">{{ $expenseEntries->count() }} {{ Str::plural('entry', $expenseEntries->count()) }}</div>
            </div>

            @php $net = $incomeTotal - $expenseTotal; @endphp
            <div class="summary-card savings">
                <div class="s-label">Net Savings</div>
                <div class="s-val" style="color: {{ $net >= 0 ? '#60a5fa' : 'var(--red)' }}">
                    {{ $net >= 0 ? '+' : '' }}${{ number_format($net, 2) }}
                </div>
                <div class="s-sub">{{ $net >= 0 ? 'Positive balance' : 'Deficit this month' }}</div>
            </div>

            <!-- Chart -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="ptag">Savings Trend</div>
                        <h3>12-Month View</h3>
                    </div>
                </div>
                <div class="chart-inner">
                    <canvas id="savingsChart" height="200"></canvas>
                </div>
            </div>

        </div>

        <!-- Tables -->
        <div class="tables-col">

            <!-- Income -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <div class="ptag">Income</div>
                        <h3>{{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') }}</h3>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th>Amount</th>
                                <th>Tag</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($incomeEntries as $entry)
                            <tr>
                                <td>{{ $entry->source }}</td>
                                <td class="amt-g">${{ number_format($entry->amount, 2) }}</td>
                                <td><span class="tag-pill tp-green">{{ $entry->tag }}</span></td>
                                <td class="dim">{{ $entry->date->format('M j') }}</td>
                                <td>
                                    <button class="act-btn edit"
                                        onclick="openEdit('income',{{ $entry->id }},'{{ addslashes($entry->source) }}','{{ $entry->amount }}','{{ $entry->tag }}','{{ $entry->date->format('Y-m-d') }}','{{ $entry->month }}','{{ $entry->year }}')">
                                        EDIT
                                    </button>
                                    <form method="POST" action="{{ route('income.destroy', $entry) }}" style="display:inline" onsubmit="return confirm('Delete this entry?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn del">DEL</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row"><td colspan="5">NO INCOME ENTRIES FOR THIS PERIOD</td></tr>
                            @endforelse
                            <tr class="total-row">
                                <td style="color:var(--green);letter-spacing:0.1em;">TOTAL</td>
                                <td class="amt-g">${{ number_format($incomeTotal, 2) }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="panel-footer">
                    <button class="btn-add green" onclick="toggleForm('incomeForm')">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4"/></svg>
                        Add Income
                    </button>
                    <form id="incomeForm" method="POST" action="{{ route('income.store') }}" class="entry-form">
                        @csrf
                        <input type="hidden" name="month" value="{{ $currentMonth }}">
                        <input type="hidden" name="year"  value="{{ $currentYear }}">
                        <input type="text"   name="source" placeholder="Source" required>
                        <input type="number" name="amount" placeholder="Amount" step="0.01" required>
                        <select name="tag" required>
                            <option value="">Tag</option>
                            <option value="Salary">Salary</option>
                            <option value="Freelance">Freelance</option>
                            <option value="Other">Other</option>
                        </select>
                        <input type="date" name="date" required>
                        <button type="submit" class="btn-save">Save Entry</button>
                    </form>
                </div>
            </div>

            <!-- Expenses -->
            <div class="panel panel-red">
                <div class="panel-header">
                    <div>
                        <div class="ptag ptag-red">Expenses</div>
                        <h3>{{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') }}</h3>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th>Amount</th>
                                <th>Tag</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenseEntries as $entry)
                            @php
                                $tc = match($entry->tag) {
                                    'Rent/Mortgage' => 'tp-pink',
                                    'Utilities'     => 'tp-yellow',
                                    'Miscellaneous' => 'tp-blue',
                                    'Retail'        => 'tp-purple',
                                    default         => 'tp-slate',
                                };
                            @endphp
                            <tr>
                                <td>{{ $entry->source }}</td>
                                <td class="amt-r">${{ number_format($entry->amount, 2) }}</td>
                                <td><span class="tag-pill {{ $tc }}">{{ $entry->tag }}</span></td>
                                <td class="dim">{{ $entry->date->format('M j') }}</td>
                                <td>
                                    <button class="act-btn edit"
                                        onclick="openEdit('expense',{{ $entry->id }},'{{ addslashes($entry->source) }}','{{ $entry->amount }}','{{ $entry->tag }}','{{ $entry->date->format('Y-m-d') }}','{{ $entry->month }}','{{ $entry->year }}')">
                                        EDIT
                                    </button>
                                    <form method="POST" action="{{ route('expense.destroy', $entry) }}" style="display:inline" onsubmit="return confirm('Delete this entry?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn del">DEL</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row"><td colspan="5">NO EXPENSE ENTRIES FOR THIS PERIOD</td></tr>
                            @endforelse
                            <tr class="total-row">
                                <td style="color:var(--red);letter-spacing:0.1em;">TOTAL</td>
                                <td class="amt-r">${{ number_format($expenseTotal, 2) }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="panel-footer">
                    <button class="btn-add red" onclick="toggleForm('expenseForm')">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4"/></svg>
                        Add Expense
                    </button>
                    <form id="expenseForm" method="POST" action="{{ route('expense.store') }}" class="entry-form">
                        @csrf
                        <input type="hidden" name="month" value="{{ $currentMonth }}">
                        <input type="hidden" name="year"  value="{{ $currentYear }}">
                        <input type="text"   name="source" placeholder="Source" required>
                        <input type="number" name="amount" placeholder="Amount" step="0.01" required>
                        <select name="tag" required>
                            <option value="">Tag</option>
                            <option value="Rent/Mortgage">Rent/Mortgage</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Miscellaneous">Miscellaneous</option>
                            <option value="Retail">Retail</option>
                            <option value="Other">Other</option>
                        </select>
                        <input type="date" name="date" required>
                        <button type="submit" class="btn-save">Save Entry</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4 id="modalTitle">Edit Entry</h4>
            <button class="modal-close" onclick="closeEdit()">✕</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="month" id="edit_month">
            <input type="hidden" name="year"  id="edit_year">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-field">
                        <label>Source</label>
                        <input type="text" name="source" id="edit_source" required>
                    </div>
                    <div class="form-field">
                        <label>Amount</label>
                        <input type="number" name="amount" id="edit_amount" step="0.01" required>
                    </div>
                    <div class="form-field">
                        <label>Tag</label>
                        <select name="tag" id="edit_tag" required></select>
                    </div>
                    <div class="form-field">
                        <label>Date</label>
                        <input type="date" name="date" id="edit_date" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeEdit()">Cancel</button>
                <button type="submit" class="btn-modal-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleForm(id) {
        document.getElementById(id).classList.toggle('open');
    }

    const incomeTags  = ['Salary','Freelance','Other'];
    const expenseTags = ['Rent/Mortgage','Utilities','Miscellaneous','Retail','Other'];

    function openEdit(type, id, source, amount, tag, date, month, year) {
        document.getElementById('modalTitle').textContent =
            type === 'income' ? 'EDIT INCOME' : 'EDIT EXPENSE';

        document.getElementById('editForm').action =
            (type === 'income'
                ? '{{ url("/income") }}/'
                : '{{ url("/expense") }}/') + id;

        document.getElementById('edit_source').value = source;
        document.getElementById('edit_amount').value = amount;
        document.getElementById('edit_date').value   = date;
        document.getElementById('edit_month').value  = month;
        document.getElementById('edit_year').value   = year;

        const sel = document.getElementById('edit_tag');
        sel.innerHTML = '';
        (type === 'income' ? incomeTags : expenseTags).forEach(t => {
            const o = document.createElement('option');
            o.value = t; o.textContent = t;
            if (t === tag) o.selected = true;
            sel.appendChild(o);
        });

        document.getElementById('editModal').classList.add('open');
    }

    function closeEdit() {
        document.getElementById('editModal').classList.remove('open');
    }

    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEdit();
    });

    const isDeficit = {{ $expenseTotal > $incomeTotal ? 'true' : 'false' }};
    const lineColor = isDeficit ? '#e05252' : '#1E9E58';
    const fillColor = isDeficit ? 'rgba(224,82,82,0.07)' : 'rgba(30,158,88,0.07)';

    const ctx = document.getElementById('savingsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $chartMonths !!},
            datasets: [{
                label: 'Net Savings',
                data: {!! $chartData !!},
                borderColor: lineColor,
                backgroundColor: fillColor,
                borderWidth: 2,
                fill: true,
                pointRadius: 3,
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
                legend: {
                    labels: {
                        color: '#5a7a5a',
                        font: { family: 'IBM Plex Mono', size: 10 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#5a7a5a',
                        font: { family: 'IBM Plex Mono', size: 10 },
                        callback: v => '$' + v.toLocaleString()
                    },
                    grid: { color: 'rgba(30,158,88,0.06)', drawBorder: false }
                },
                x: {
                    ticks: { color: '#5a7a5a', font: { family: 'IBM Plex Mono', size: 10 } },
                    grid: { display: false }
                }
            }
        }
    });
</script>

@endsection