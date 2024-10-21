<?php

namespace App\Actions\Microsites;

use App\Models\Microsites;

class StoreAction
{
    public function execute(array $data): Microsites
    {
        // 'payment_retries' => 'nullable|integer|min:1',
        //     'retry_duration' => 'nullable|integer|min:1',
        //     'late_fee_percentage' => 'nullable|numeric|min:0|max:100',
        $microsite = new Microsites();
        $microsite->slug = $data['slug'];
        $microsite->name = $data['name'];
        $microsite->category_id = $data['category_id'];
        $microsite->document_type = $data['document_type'];
        $microsite->document_number = $data['document_number'];
        $microsite->logo = $data['logo'];
        $microsite->currency = $data['currency'];
        $microsite->site_type = $data['site_type'];
        $microsite->payment_expiration = $data['payment_expiration'];
        $microsite->user_id = $data['user_id'];
        $microsite->payment_retries = $data['payment_retries'];
        $microsite->retry_duration = $data['retry_duration'];
        $microsite->late_fee_percentage = $data['late_fee_percentage'];
        $microsite->payment_fields = $data['payment_fields'];
        $microsite->save();

        return $microsite;
    }
}
