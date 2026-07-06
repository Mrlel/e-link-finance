<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $user = $request->user();
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $totals = $user->transactions()
            ->selectRaw("type, sum(amount) as total")
            ->groupBy('type')
            ->pluck('total', 'type');

        $totalIncome = (float) ($totals['income'] ?? 0);
        $totalExpense = (float) ($totals['expense'] ?? 0);

        $monthTotals = $user->transactions()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->selectRaw("type, sum(amount) as total")
            ->groupBy('type')
            ->pluck('total', 'type');

        $expenseByCategory = $user->transactions()
            ->where('transactions.type', 'expense')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->selectRaw('categories.name as category, categories.color as color, sum(transactions.amount) as total')
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderByDesc('total')
            ->get();

        $expenseByMonthTotals = $user->transactions()
            ->where('type', 'expense')
            ->whereYear('date', $year)
            ->selectRaw('MONTH(date) as month, sum(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $expenseByMonth = collect(range(1, 12))->map(fn ($m) => [
            'month' => $m,
            'total' => (float) ($expenseByMonthTotals[$m] ?? 0),
        ]);

        return response()->json([
            'balance' => $totalIncome - $totalExpense,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'month_income' => (float) ($monthTotals['income'] ?? 0),
            'month_expense' => (float) ($monthTotals['expense'] ?? 0),
            'expense_by_category' => $expenseByCategory,
            'expense_by_month' => $expenseByMonth,
            'total_savings' => (float) $user->savingsGoals()->sum('current_amount'),
        ]);
    }
}
