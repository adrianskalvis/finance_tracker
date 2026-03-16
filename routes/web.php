<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Http\Request;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| SETUP INSTRUCTIONS
|--------------------------------------------------------------------------
| 1. composer install
| 2. cp .env.example .env → set DB_DATABASE, DB_USERNAME, DB_PASSWORD
| 3. php artisan key:generate
| 4. php artisan migrate
| 5. php artisan db:seed
| 6. npm install && npm run dev
| 7. php artisan serve
| 8. Login with demo@demo.com / password
*/

Route::get('/', function () {
    return view('index');
});

// Authentication routes (built-in Laravel Auth)
Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (auth()->attempt($credentials, $request->boolean('remember'))) {
            return redirect()->intended('dashboard');
        }
        
        return back()->withErrors(['email' => 'Invalid credentials']);
    });
    
    Route::get('register', function () {
        return view('auth.register');
    })->name('register');
    
    Route::post('register', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        
        auth()->login($user);
        
        return redirect('dashboard');
    });
    
    Route::get('forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

Route::middleware('auth')->group(function () {
    Route::get('logout', function (Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
    
    // Finance routes
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('analytics', AnalyticsController::class)->name('analytics');
    
    // Income routes
    Route::post('income', [IncomeController::class, 'store'])->name('income.store');
    Route::put('income/{incomeEntry}', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('income/{incomeEntry}', [IncomeController::class, 'destroy'])->name('income.destroy');
    
    // Expense routes
    Route::post('expense', [ExpenseController::class, 'store'])->name('expense.store');
    Route::put('expense/{expenseEntry}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('expense/{expenseEntry}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
});
