<?php

namespace App\Actions\Plans;

use App\Models\Plan;

class UpdateAction
{
    public function execute(array $data): Plan
    {
        $plan = Plan::find($data['id']);
        $plan->name = $data['plan']['name'];
        $plan->price = $data['plan']['price'];
        $plan->description = $data['plan']['description'];
        $plan->duration_unit = $data['plan']['duration_unit'];
        $plan->billing_frequency = $data['plan']['billing_frequency'];
        $plan->duration_period = $data['plan']['duration_period'];
        $plan->save();

        return $plan;
    }
}
