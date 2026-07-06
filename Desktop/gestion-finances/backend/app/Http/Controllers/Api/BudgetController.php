<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $budgets = $request->user()->budgets()
            ->with('category')
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $spentByCategory = $request->user()->transactions()
            ->where('type', 'expense')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $budgets->each(function (Budget $budget) use ($spentByCategory) {
            $budget->spent = (float) ($spentByCategory[$budget->category_id] ?? 0);
        });

        return $budgets;
    }

    public function store(StoreBudgetRequest $request)
    {
        $budget = $request->user()->budgets()->create($request->validated());

        return response()->json($budget->load('category'), 201);
    }

    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $budget->update($request->validated());

        return $budget->load('category');
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return response()->json(null, 204);
    }
}
