<?php

namespace App\Actions\Microsites;

use App\Models\Microsites;

class StoreAction
{
    public function execute(array $data): Microsites
    {
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
        $microsite->payment_fields = $data['payment_fields'];
        $microsite->save();

        return $microsite;
    }
}
