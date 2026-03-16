<?php

namespace App\Http\Controllers;

use App\Models\IncomeEntry;
use App\Models\ExpenseEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);
        
        $userId = Auth::id();
        
        // Get income entries for selected month/year
        $incomeEntries = IncomeEntry::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('date', 'desc')
            ->get();
        
        // Get expense entries for selected month/year
        $expenseEntries = ExpenseEntry::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('date', 'desc')
            ->get();
        
        // Calculate totals
        $incomeTotal = $incomeEntries->sum('amount');
        $expenseTotal = $expenseEntries->sum('amount');
        $netSavings = $incomeTotal - $expenseTotal;
        
        // Get all months for current year for chart
        $months = [];
        $cumulativeSavings = [];
        $cumulative = 0;
        
        for ($m = 1; $m <= 12; $m++) {
            $monthIncome = IncomeEntry::where('user_id', $userId)
                ->where('month', $m)
                ->where('year', $year)
                ->sum('amount');
            
            $monthExpense = ExpenseEntry::where('user_id', $userId)
                ->where('month', $m)
                ->where('year', $year)
                ->sum('amount');
            
            $cumulative += ($monthIncome - $monthExpense);
            $months[] = Carbon::createFromDate($year, $m, 1)->format('M');
            $cumulativeSavings[] = (float) $cumulative;
        }
        
        return view('dashboard', [
            'incomeEntries' => $incomeEntries,
            'expenseEntries' => $expenseEntries,
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'netSavings' => $netSavings,
            'currentMonth' => $month,
            'currentYear' => $year,
            'chartMonths' => json_encode($months),
            'chartData' => json_encode($cumulativeSavings),
        ]);
    }
}
