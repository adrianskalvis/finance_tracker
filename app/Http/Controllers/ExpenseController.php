<?php

namespace App\Http\Controllers;

use App\Models\ExpenseEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'tag' => 'required|in:Rent/Mortgage,Utilities,Miscellaneous,Retail,Other',
            'date' => 'required|date',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        ExpenseEntry::create($validated);
        
        return redirect()->back()->with('success', 'Expense entry added successfully');
    }
    
    public function update(Request $request, ExpenseEntry $expenseEntry)
    {
        // Check ownership
        if ($expenseEntry->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'tag' => 'required|in:Rent/Mortgage,Utilities,Miscellaneous,Retail,Other',
            'date' => 'required|date',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);
        
        $expenseEntry->update($validated);
        
        return redirect()->back()->with('success', 'Expense entry updated successfully');
    }
    
    public function destroy(ExpenseEntry $expenseEntry)
    {
        // Check ownership
        if ($expenseEntry->user_id !== Auth::id()) {
            abort(403);
        }
        
        $expenseEntry->delete();
        
        return redirect()->back()->with('success', 'Expense entry deleted successfully');
    }
}
