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
        return Inertia::render('Microsites/Plans/create');
    }

    public function create(Microsites $microsite)
    {
        return Inertia::render('Microsites/Plans/create', [
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

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
