<?php

namespace App\Http\Controllers;

use App\Models\IncomeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IncomeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'tag' => 'required|in:Salary,Freelance,Other',
            'date' => 'required|date',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        IncomeEntry::create($validated);
        
        return redirect()->back()->with('success', 'Income entry added successfully');
    }
    
    public function update(Request $request, IncomeEntry $incomeEntry)
    {
        // Check ownership
        if ($incomeEntry->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'tag' => 'required|in:Salary,Freelance,Other',
            'date' => 'required|date',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);
        
        $incomeEntry->update($validated);
        
        return redirect()->back()->with('success', 'Income entry updated successfully');
    }
    
    public function destroy(IncomeEntry $incomeEntry)
    {
        // Check ownership
        if ($incomeEntry->user_id !== Auth::id()) {
            abort(403);
        }
        
        $incomeEntry->delete();
        
        return redirect()->back()->with('success', 'Income entry deleted successfully');
    }
}
