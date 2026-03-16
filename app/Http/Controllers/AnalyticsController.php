<?php

namespace App\Http\Controllers;

use App\Models\IncomeEntry;
use App\Models\ExpenseEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function __invoke()
    {
        $userId = Auth::id();
        $year = now()->year;
        
        // Get all months data for current year
        $monthsData = [];
        $monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        for ($m = 1; $m <= 12; $m++) {
            $income = IncomeEntry::where('user_id', $userId)
                ->where('month', $m)
                ->where('year', $year)
                ->sum('amount');
            
            $expense = ExpenseEntry::where('user_id', $userId)
                ->where('month', $m)
                ->where('year', $year)
                ->sum('amount');
            
            $monthsData[] = [
                'name' => $monthNames[$m - 1],
                'month' => $m,
                'income' => (float) $income,
                'expense' => (float) $expense,
                'net' => (float) ($income - $expense),
                'isCurrentMonth' => $m === now()->month && $year === now()->year,
            ];
        }
        
        // Get expense breakdown for doughnut chart
        $expenseTags = ['Rent/Mortgage', 'Utilities', 'Miscellaneous', 'Retail', 'Other'];
        $expenseByTag = [];
        
        $colors = [
            'Rent/Mortgage' => '#ec4899',  // pink
            'Utilities' => '#eab308',      // yellow
            'Miscellaneous' => '#3b82f6', // blue
            'Retail' => '#8b5cf6',        // purple
            'Other' => '#9ca3af',          // gray
        ];
        
        foreach ($expenseTags as $tag) {
            $amount = ExpenseEntry::where('user_id', $userId)
                ->where('year', $year)
                ->where('tag', $tag)
                ->sum('amount');
            
            if ($amount > 0) {
                $expenseByTag[] = [
                    'tag' => $tag,
                    'amount' => (float) $amount,
                    'color' => $colors[$tag],
                ];
            }
        }
        
        // Prepare chart data
        $chartLabels = array_map(fn($item) => $item['tag'], $expenseByTag);
        $chartData = array_map(fn($item) => $item['amount'], $expenseByTag);
        $chartColors = array_map(fn($item) => $item['color'], $expenseByTag);
        
        return view('analytics', [
            'monthsData' => $monthsData,
            'expenseByTag' => $expenseByTag,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
            'chartColors' => json_encode($chartColors),
            'year' => $year,
        ]);
    }
}
