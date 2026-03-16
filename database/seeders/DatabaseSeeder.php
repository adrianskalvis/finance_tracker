<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\IncomeEntry;
use App\Models\ExpenseEntry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@demo.com',
            'password' => bcrypt('password'),
        ]);
        
        // Seed January 2026 income entries
        IncomeEntry::create([
            'user_id' => $user->id,
            'source' => 'Wardiere Inc.',
            'amount' => 5000,
            'tag' => 'Salary',
            'date' => '2026-01-15',
            'month' => 1,
            'year' => 2026,
        ]);
        
        IncomeEntry::create([
            'user_id' => $user->id,
            'source' => 'Design Agency',
            'amount' => 3000,
            'tag' => 'Freelance',
            'date' => '2026-01-10',
            'month' => 1,
            'year' => 2026,
        ]);
        
        IncomeEntry::create([
            'user_id' => $user->id,
            'source' => 'Digital Store',
            'amount' => 1500,
            'tag' => 'Freelance',
            'date' => '2026-01-20',
            'month' => 1,
            'year' => 2026,
        ]);
        
        // Seed January 2026 expense entries
        ExpenseEntry::create([
            'user_id' => $user->id,
            'source' => 'Mortgage',
            'amount' => 2500,
            'tag' => 'Rent/Mortgage',
            'date' => '2026-01-01',
            'month' => 1,
            'year' => 2026,
        ]);
        
        ExpenseEntry::create([
            'user_id' => $user->id,
            'source' => 'Tyke Inc.',
            'amount' => 120,
            'tag' => 'Utilities',
            'date' => '2026-01-05',
            'month' => 1,
            'year' => 2026,
        ]);
        
        ExpenseEntry::create([
            'user_id' => $user->id,
            'source' => 'Misc expenses',
            'amount' => 300,
            'tag' => 'Miscellaneous',
            'date' => '2026-01-25',
            'month' => 1,
            'year' => 2026,
        ]);
    }
}
