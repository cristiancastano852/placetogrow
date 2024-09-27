<?php

namespace App\Http\Controllers;

use App\Actions\Plans\UpdateAction;
use App\Constants\TimeUnitSubscription;
use App\Http\Requests\StorePlanRequest;
use App\Models\Microsites;
use App\Models\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanController extends Controller
{
    public function index(Microsites $microsite)
    {
        $plans = Plan::where('microsite_id', $microsite->id)->get();

        return Inertia::render('Microsites/Plans/ShowPlans', [
            'plans' => $plans,
            'microsite' => $microsite,
        ]);
    }

    public function create(Microsites $microsite)
    {
        $plans = Plan::where('microsite_id', $microsite->id)->get();

        return Inertia::render('Microsites/Plans/Create', [
            'plans' => $plans,
            'microsite' => $microsite,
            'duration_units' => TimeUnitSubscription::toArray(),
        ]);
    }

    public function store(StorePlanRequest $request, Microsites $microsite)
    {
        $validated = $request->validated();
        $plan = $validated['plan'];
        $plan['microsite_id'] = $microsite->id;
        Plan::create($plan);

        return redirect()->route('plans.index', $microsite->id)->with('success', 'Planes creados correctamente');
    }

    public function show(Microsites $microsite)
    {
        $plans = Plan::where('microsite_id', $microsite->id)->get();

        return Inertia::render('Microsites/Plans/Show', [
            'plans' => $plans,
            'microsite' => $microsite,
        ]);
    }

    public function edit(Microsites $microsite, Plan $plan)
    {
        return Inertia::render('Microsites/Plans/Edit', [
            'plan' => $plan,
            'microsite' => $microsite,
            'duration_units' => TimeUnitSubscription::toArray(),
        ]);
    }

    public function update(StorePlanRequest $request, Microsites $microsite, Plan $plan,  UpdateAction $updateAction) {
        $plan_data = $request->validated();
        $plan_data['id'] = $plan->id;
        $updateAction->execute($plan_data);

        return redirect()->route('plans.index', $microsite->id)->with('success', 'Plan actualizado correctamente');
    }

    public function destroy(Microsites $microsite, Plan $plan) {
        $microsite_id = $microsite->id;
        $plan->delete();
        
        return redirect()->route('plans.index', $microsite_id)->with('success', 'Plan eliminado correctamente');
    }
}
