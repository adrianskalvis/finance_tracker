# Finance Tracker

A fully functional finance tracking application built with Laravel, featuring income/expense management, visual analytics, and cumulative savings tracking.

## Setup Instructions

### Prerequisites
- PHP 8.2+
- MySQL 5.7+
- Composer
- Node.js & npm

### Installation

1. **Install dependencies**
   ```bash
   composer install
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   ```
   Update the following in `.env`:
   - `DB_DATABASE=finace_tracker` (or your database name)
   - `DB_USERNAME=root` (your MySQL username)
   - `DB_PASSWORD=` (your MySQL password, leave empty if no password)

3. **Generate application key**
   ```bash
   php artisan key:generate
   ```

4. **Run migrations**
   ```bash
   php artisan migrate
   ```

5. **Seed the database with demo data**
   ```bash
   php artisan db:seed
   ```

6. **Install frontend dependencies**
   ```bash
   npm install
   ```

7. **Build assets**
   ```bash
   npm run dev
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Visit the application**
   Open your browser and go to `http://localhost:8000`

## Demo Account

Use these credentials to test the application:
- **Email:** demo@demo.com
- **Password:** password

The demo account comes pre-populated with sample income and expense data for January 2026.

## Features

### Authentication
- User registration and login
- Password-protected finance data (user-scoped)
- Session-based authentication

### Dashboard
- Month/year selector to view specific periods
- Income and expense tables with CRUD operations
- Total income and expense calculations
- Cumulative savings line chart (Jan-Dec current year)
- Color-coded tags for quick categorization

### Analytics
- Monthly summary grid (3-column layout)
- Expense breakdown doughnut chart by category
- Income/Expenses/Net for all 12 months
- Current month highlighting

### Data Categories

**Income Tags:** Salary, Freelance, Other

**Expense Tags:** Rent/Mortgage, Utilities, Miscellaneous, Retail, Other

### Chart Visualization
- Chart.js for line and doughnut charts
- Real-time data updates
- Dark green theme for dashboard chart
- Color-coded expense categories in analytics

## Technology Stack

- **Backend:** Laravel 12 with Eloquent ORM
- **Frontend:** Blade templates with Tailwind CSS
- **Database:** MySQL
- **Charts:** Chart.js (CDN)
- **Bundler:** Vite

## Project Structure

```
app/
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── IncomeController.php
│   ├── ExpenseController.php
│   └── AnalyticsController.php
└── Models/
    ├── User.php
    ├── IncomeEntry.php
    └── ExpenseEntry.php

database/
├── migrations/
│   ├── create_income_entries_table.php
│   └── create_expense_entries_table.php
└── seeders/
    └── DatabaseSeeder.php

resources/views/
├── layouts/
│   └── app.blade.php
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   └── forgot-password.blade.php
├── dashboard.blade.php
├── analytics.blade.php
└── index.blade.php
```

## API Routes

All routes are protected by the `auth` middleware.

### Authentication
- `GET /login` - Login page
- `POST /login` - Process login
- `GET /register` - Registration page
- `POST /register` - Process registration
- `GET /logout` - Logout user

### Finance
- `GET /dashboard` - Main dashboard
- `GET /analytics` - Analytics view

### Income Management
- `POST /income` - Create income entry
- `PUT /income/{id}` - Update income entry
- `DELETE /income/{id}` - Delete income entry

### Expense Management
- `POST /expense` - Create expense entry
- `PUT /expense/{id}` - Update expense entry
- `DELETE /expense/{id}` - Delete expense entry

## Database Schema

### income_entries
- id
- user_id (FK)
- source (string)
- amount (decimal 10,2)
- tag (enum: Salary, Freelance, Other)
- date (date)
- month (tinyint)
- year (smallint)
- timestamps

### expense_entries
- id
- user_id (FK)
- source (string)
- amount (decimal 10,2)
- tag (enum: Rent/Mortgage, Utilities, Miscellaneous, Retail, Other)
- date (date)
- month (tinyint)
- year (smallint)
- timestamps

## Validation

All form inputs are validated:
- Amount must be numeric and > 0
- Date must be valid
- Source must be provided
- Tag must match enum values
- User can only access/modify their own data

## License

MIT


We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
