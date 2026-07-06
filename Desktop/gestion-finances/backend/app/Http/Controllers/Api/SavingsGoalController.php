<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSavingsGoalRequest;
use App\Http\Requests\UpdateSavingsGoalRequest;
use App\Models\SavingsGoal;
use Illuminate\Http\Request;

class SavingsGoalController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->savingsGoals()->orderBy('deadline')->get();
    }

    public function store(StoreSavingsGoalRequest $request)
    {
        $goal = $request->user()->savingsGoals()->create($request->validated());

        return response()->json($goal, 201);
    }

    public function update(UpdateSavingsGoalRequest $request, SavingsGoal $savingsGoal)
    {
        $this->authorize('update', $savingsGoal);

        $savingsGoal->update($request->validated());

        return $savingsGoal;
    }

    public function contribute(Request $request, SavingsGoal $savingsGoal)
    {
        $this->authorize('update', $savingsGoal);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $savingsGoal->increment('current_amount', $request->float('amount'));

        return $savingsGoal;
    }

    public function destroy(SavingsGoal $savingsGoal)
    {
        $this->authorize('delete', $savingsGoal);

        $savingsGoal->delete();

        return response()->json(null, 204);
    }
}
