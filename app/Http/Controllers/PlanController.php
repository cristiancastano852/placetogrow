<?php

namespace App\Http\Controllers;

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
        return Inertia::render('Microsites/Plans/Create');
    }

    public function create(Microsites $microsite)
    {
        $plans = Plan::where('microsite_id', $microsite->id)->get();

        return Inertia::render('Microsites/Plans/Create', [
            'plans' => $plans,
            'microsite_id' => $microsite->id,
            'duration_units' => TimeUnitSubscription::toArray(),
            'microsite_name' => $microsite->name,
        ]);
    }

    public function store(StorePlanRequest $request, Microsites $microsite)
    {
        $validated = $request->validated();
        foreach ($validated['plans'] as $plan) {
            $plan['microsite_id'] = $microsite->id;
            Plan::create($plan);
        }

        return redirect()->route('microsites.show', $microsite->id)->with('success', 'Planes creados correctamente');
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
            'microsite_name' => $microsite->name,
            'microsite_id' => $microsite->id,
        ]);
    }

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
